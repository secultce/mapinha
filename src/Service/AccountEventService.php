<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountEvent;
use App\Entity\User;
use App\Enum\AccountEventTypeEnum;
use App\Enum\UserRolesEnum;
use App\Enum\UserStatusEnum;
use App\Exception\AccountEvent\ExpiredTokenException;
use App\Exception\AccountEvent\InvalidTokenException;
use App\Exception\User\UserResourceNotFoundException;
use App\Repository\Interface\AccountEventRepositoryInterface;
use App\Repository\Interface\UserRepositoryInterface;
use App\Service\Interface\AccountEventServiceInterface;
use App\Service\Interface\EmailServiceInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class AccountEventService extends AbstractEntityService implements AccountEventServiceInterface
{
    public const string TIME_TO_EXPIRATION = '+24 hours';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailServiceInterface $emailService,
        private AccountEventRepositoryInterface $accountEventRepository,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
        private PasswordHasherFactoryInterface $passwordHasherFactory,
        private UserRepositoryInterface $userRepository,
        private Security $security,
    ) {
        parent::__construct($this->security);
    }

    public function confirmAccount(string $token): void
    {
        $accountEvent = $this->entityManager->getRepository(AccountEvent::class)->findOneBy(['token' => $token]);

        if (null === $accountEvent || $accountEvent->getType() !== AccountEventTypeEnum::ACTIVATION->value) {
            throw new InvalidTokenException();
        }

        $user = $this->entityManager->find(User::class, $accountEvent->getUser()->getId()->toRfc4122());

        if ($accountEvent->getExpirationAt() < new DateTimeImmutable()) {
            $this->sendConfirmationEmail($user);

            throw new ExpiredTokenException();
        }

        $user->setStatus(UserStatusEnum::ACTIVE->value);

        $this->entityManager->remove($accountEvent);
        $this->entityManager->flush();
    }

    public function sendConfirmationEmail(User $user): void
    {
        $token = Uuid::v4();

        $confirmationUrl = $this->urlGenerator->generate('web_account_event_confirmation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $expirationAt = new DateTimeImmutable(self::TIME_TO_EXPIRATION);

        $this->emailService->sendTemplatedEmail(
            [$user->getEmail()],
            $this->translator->trans('account_confirmation'),
            '_emails/account-event/account-confirmation.html.twig',
            [
                'first_name' => $user->getFirstName(),
                'confirmation_link' => $confirmationUrl,
                'expiration_date' => $expirationAt,
            ]
        );

        $this->create([
            'id' => Uuid::v4(),
            'user' => $user,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => $token,
            'expirationAt' => $expirationAt,
        ]);
    }

    public function resetPassword(string $token, Request $request): void
    {
        $accountEvent = $this->entityManager->getRepository(AccountEvent::class)->findOneBy(['token' => $token]);

        if (null === $accountEvent || $accountEvent->getType() !== AccountEventTypeEnum::RECOVERY->value) {
            throw new InvalidTokenException();
        }

        $user = $this->entityManager->find(User::class, $accountEvent->getUser()->getId()->toRfc4122());

        if ($accountEvent->getExpirationAt() < new DateTimeImmutable()) {
            $this->sendResetPasswordEmail($user->getEmail(), isNewUser: true);

            throw new ExpiredTokenException();
        }

        $password = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash($request->request->get('password'));
        $user->setPassword($password);
        $user->setStatus(UserStatusEnum::ACTIVE->value);

        $this->entityManager->remove($accountEvent);
        $this->entityManager->flush();

        $this->sendPasswordChangedEmail($user, $request->server->get('HTTP_USER_AGENT'));
    }

    public function sendResetPasswordEmail(string $email, bool $isNewUser = false): void
    {
        $token = Uuid::v4();

        $resetUrl = $this->urlGenerator->generate('web_account_event_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $expirationAt = new DateTimeImmutable(self::TIME_TO_EXPIRATION);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null === $user) {
            throw new UserResourceNotFoundException();
        }

        $template = $isNewUser
            ? '_emails/account-event/password-reset-new-user.html.twig'
            : '_emails/account-event/password-reset.html.twig';

        $this->emailService->sendTemplatedEmail(
            [$user->getEmail()],
            $isNewUser ? $this->translator->trans('welcome_reset_password') : $this->translator->trans('reset_password'),
            $template,
            [
                'first_name' => $user->getFirstName(),
                'reset_link' => $resetUrl,
                'expiration_date' => $expirationAt,
            ]
        );

        $this->create([
            'id' => Uuid::v4(),
            'user' => $user,
            'type' => AccountEventTypeEnum::RECOVERY->value,
            'token' => $token,
            'expirationAt' => $expirationAt,
        ]);
    }

    public function create(array $data): AccountEvent
    {
        $accountEvent = new AccountEvent();

        $accountEvent->setId($data['id']);
        $accountEvent->setUser($data['user']);
        $accountEvent->setType($data['type']);
        $accountEvent->setToken($data['token'] ?? null);
        $accountEvent->setExpirationAt($data['expirationAt'] ?? null);

        $this->accountEventRepository->save($accountEvent);

        return $accountEvent;
    }

    private function getManagerEmail(): string
    {
        $user = $this->userRepository->findOneByRole(UserRolesEnum::ROLE_MANAGER->value);

        if (!$user) {
            throw new UserResourceNotFoundException();
        }

        return $user->getEmail();
    }

    public function notifyManagerOfNewRegistration(
        string $userName,
        string $organizationName,
        string $organizationType,
        DateTimeImmutable $organizationCreatedAt
    ): void {
        $managerEmail = $this->getManagerEmail();

        $this->emailService->sendTemplatedEmail(
            [$managerEmail],
            $this->translator->trans('new_registration_notification'),
            '_emails/notifications/manager/new-registration.html.twig',
            [
                'manager_name' => $this->translator->trans('manager_name'),
                'user_name' => $userName,
                'organization_name' => $organizationName,
                'organization_type' => $organizationType,
                'organization_created_at' => $organizationCreatedAt->format('Y/m/d \à\s H:i:s'),
            ]
        );
    }

    public function notifyManagerOfNewMunicipalityDocument(
        string $organizationName,
    ): void {
        $managerEmail = $this->getManagerEmail();

        $this->emailService->sendTemplatedEmail(
            [$managerEmail],
            $this->translator->trans('resend_term_notification'),
            '_emails/notifications/manager/new-municipality-document.html.twig',
            [
                'organization_name' => $organizationName,
            ]
        );
    }

    public function sendPasswordChangedEmail(User $user, ?string $userAgent): void
    {
        if (null === $user->getEmail()) {
            throw new UserResourceNotFoundException();
        }

        $this->emailService->sendTemplatedEmail(
            [$user->getEmail()],
            $this->translator->trans('changed_password'),
            '_emails/account-event/password-changed.html.twig',
            [
                'firstname' => $user->getFirstName(),
                'device' => $this->getDevice($userAgent),
                'datetime' => new DateTimeImmutable(),
            ]
        );

        $this->create([
            'id' => Uuid::v4(),
            'user' => $user,
            'type' => AccountEventTypeEnum::PASSWORD_CHANGED->value,
        ]);
    }

    private function getDevice(?string $userAgent): string
    {
        if (null === $userAgent) {
            return 'unknown';
        }

        $browser = match (1) {
            preg_match('/msie/i', $userAgent) => 'Internet explorer',
            preg_match('/firefox/i', $userAgent) => 'Firefox',
            preg_match('/opr/i', $userAgent) => 'Opera',
            preg_match('/edg/i', $userAgent) => 'Edge',
            preg_match('/chrome/i', $userAgent) => 'Chrome',
            preg_match('/safari/i', $userAgent) => 'Safari',
            preg_match('/mobile/i', $userAgent) => 'Mobile browser',
            default => 'unknown',
        };

        $operationalSystem = match (1) {
            preg_match('/android/i', $userAgent) => 'Android',
            preg_match('/linux/i', $userAgent) => 'Linux',
            preg_match('/windows|win32/i', $userAgent) => 'Windows',
            preg_match('/macintosh|mac os x/i', $userAgent) => 'MacOS',
            preg_match('/iphone|ipad/i', $userAgent) => 'iOS',
            default => 'unknown',
        };

        return $this->translator->trans('device_description', [
            '{browser}' => $browser,
            '{operationalSystem}' => $operationalSystem,
        ]);
    }
}

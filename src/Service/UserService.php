<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserDto;
use App\Entity\Agent;
use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Exception\User\UserResourceNotFoundException;
use App\Repository\Interface\UserRepositoryInterface;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\UserServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class UserService extends AbstractEntityService implements UserServiceInterface
{
    private const string DIR_USER_PROFILE = 'app.dir.user.profile';

    public function __construct(
        private AgentServiceInterface $agentService,
        private UserRepositoryInterface $repository,
        private Security $security,
        private SerializerInterface $serializer,
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private AccountEventService $accountEventService,
        private PasswordHasherFactoryInterface $passwordHasherFactory,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct(
            $this->security,
            $this->serializer,
            $this->validator,
            $this->entityManager,
            User::class,
            $this->fileService,
            $this->parameterBag,
            self::DIR_USER_PROFILE,
        );
    }

    public function create(array $user): User
    {
        $user = $this->validateInput($user, UserDto::class, UserDto::CREATE);

        $password = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash($user['password']);

        /** @var User $userObj */
        $userObj = $this->serializer->denormalize($user, User::class);
        $userObj->setPassword($password);

        try {
            $this->repository->beginTransaction();
            $this->repository->save($userObj);
            $this->repository->commit();

            $agent = $this->agentService->createFromUser($user, $user['extraFields'] ?? []);

            $userObj->addAgent($agent);
        } catch (Exception $exception) {
            $this->repository->rollback();
            throw $exception;
        }

        return $userObj;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function get(Uuid $id): User
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findOneBy(array $params): User
    {
        $user = $this->repository->findOneBy([
            ...$params,
            ...self::DEFAULT_FILTERS,
        ]);

        if (null === $user) {
            throw new UserResourceNotFoundException();
        }

        return $user;
    }

    public function update(Uuid $id, array $user, ?string $browserUserAgent = null): User
    {
        $userObj = $this->get($id);

        $user = $this->validateInput($user, UserDto::class, UserDto::UPDATE);

        $userObj = $this->serializer->denormalize($user, User::class, context: [
            'object_to_populate' => $userObj,
        ]);

        $userObj->setUpdatedAt(new DateTime());

        $userObj = $this->repository->save($userObj);

        if (true === isset($user['password'])) {
            $this->accountEventService->sendPasswordChangedEmail($userObj, $browserUserAgent);
        }

        return $userObj;
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): User
    {
        $user = $this->get($id);

        $userDto = new UserDto();
        $userDto->image = $uploadedFile;

        $violations = $this->validator->validate($userDto, groups: [UserDto::UPDATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        if ($user->getImage()) {
            $this->fileService->deleteFileByUrl($user->getImage());
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get(self::DIR_USER_PROFILE),
            $uploadedFile
        );

        $relativePath = '/uploads'.$this->parameterBag->get(self::DIR_USER_PROFILE).'/'.$uploadedImage->getFilename();
        $user->setImage($relativePath);

        $user->setUpdatedAt(new DateTime());

        $this->repository->save($user);

        return $user;
    }

    public function authenticate(User $user, $password): bool
    {
        return $this->userPasswordHasher->isPasswordValid($user, $password);
    }

    public function getMainAgent(User $user): Agent
    {
        return $this->agentService->getMainAgentByEmail($user->getEmail());
    }

    public function confirmAccount(Uuid $id): void
    {
        $user = $this->get($id);

        if (null === $user) {
            throw new UserResourceNotFoundException();
        }

        $user->setStatus(UserStatusEnum::ACTIVE->value);
        $user->setUpdatedAt(new DateTime());

        $this->repository->save($user);
    }
}

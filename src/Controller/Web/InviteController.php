<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Enum\FlashMessageTypeEnum;
use App\Exception\UnauthorizedException;
use App\Resolver\ExceptionResolver\InviteExceptionResolver;
use App\Service\Interface\InviteServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class InviteController extends AbstractWebController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly InviteServiceInterface $inviteService,
        private readonly InviteExceptionResolver $exceptionResolver
    ) {
    }

    /**
     * @throws Exception
     */
    public function accept(Request $request): Response
    {
        $user = $this->getUser();

        try {
            $this->inviteService->accept(
                Uuid::fromString($request->get('id')),
                Uuid::fromString($request->get('invite')),
                $user
            );
        } catch (Exception $exception) {
            [$message, $route, $params] = $this->exceptionResolver->resolve($request, $exception);

            $this->addFlash(
                in_array(get_class($exception), [UnauthorizedException::class, UserNotFoundException::class])
                    ? FlashMessageTypeEnum::SUCCESS->value
                    : FlashMessageTypeEnum::ERROR->value,
                $message
            );

            return $this->redirectToRoute($route, $params);
        }

        $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('invite_accept'));

        return $this->redirectToRoute('admin_organization_get', [
            'id' => $request->get('invite'),
        ]);
    }
}

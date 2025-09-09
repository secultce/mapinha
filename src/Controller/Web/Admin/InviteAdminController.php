<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\AbstractWebController;
use App\Exception\Agent\AgentAlreadyMemberException;
use App\Service\Interface\InviteServiceInterface;
use App\Service\Interface\OrganizationServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class InviteAdminController extends AbstractWebController
{
    public function __construct(
        private readonly OrganizationServiceInterface $organizationService,
        private readonly TranslatorInterface $translator,
        private readonly InviteServiceInterface $inviteService,
    ) {
    }

    public function send(Uuid $id, Request $request): Response
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');

        $error = [];

        if (false === is_string($name)) {
            $error[] = $this->translator->trans('view.authentication.error.first_name_length');
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = $this->translator->trans('view.authentication.error.invalid_email');
        }

        if ($this->getUser()->getEmail() === $email) {
            $error[] = $this->translator->trans('this_member_already_belongs_to_organization');
        }

        if ([] === $error) {
            $organization = $this->organizationService->findOneBy([
                'id' => $id,
            ]);

            try {
                $this->inviteService->send($organization, $name, $email);

                $this->addFlash('success', $this->translator->trans('invite_sent'));
            } catch (AgentAlreadyMemberException) {
                $error[] = $this->translator->trans('this_member_already_belongs_to_organization');
            }
        }

        foreach ($error as $errorMessage) {
            $this->addFlash('error', $errorMessage);
        }

        return $this->redirectToRoute('admin_organization_get', ['id' => $id->toRfc4122()]);
    }
}

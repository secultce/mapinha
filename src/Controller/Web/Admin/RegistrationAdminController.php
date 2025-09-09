<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\UserRolesEnum;
use App\Service\Interface\InscriptionOpportunityServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

class RegistrationAdminController extends AbstractAdminController
{
    public function __construct(private readonly InscriptionOpportunityServiceInterface $service)
    {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $inscriptions = $this->service->findUserInscriptionsWithDetails();

        return $this->render('registration/list.html.twig', [
            'inscriptions' => $inscriptions,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function get(Uuid $id): Response
    {
        $inscription = $this->service->findInscriptionWithDetails($id);

        return $this->render('registration/one.html.twig', [
            'inscription' => $inscription,
        ]);
    }
}

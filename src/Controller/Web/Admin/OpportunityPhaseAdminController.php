<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\DocumentService\PhaseTimelineDocumentService;
use App\Enum\UserRolesEnum;
use App\Service\Interface\OpportunityServiceInterface;
use App\Service\Interface\PhaseServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class OpportunityPhaseAdminController extends AbstractAdminController
{
    public const CREATE_FORM_ID = 'add-opportunity-phase';

    public function __construct(
        private readonly PhaseServiceInterface $phaseService,
        private readonly PhaseTimelineDocumentService $documentService,
        private readonly OpportunityServiceInterface $opportunityService,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function timeline(Uuid $opportunityId, Uuid $phaseId): Response
    {
        $events = $this->documentService->getEventsByEntityId($phaseId);

        return $this->render('opportunity-phase/timeline.html.twig', [
            'phase' => $this->phaseService->get($opportunityId, $phaseId),
            'events' => $events,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Uuid $opportunityId): Response
    {
        $opportunity = $this->opportunityService->get($opportunityId);

        return $this->render('opportunity/details.html.twig', [
            'opportunity' => $opportunity,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function store(Request $request): Response
    {
        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $data = $request->request->all();

        try {
            $opportunityId = Uuid::fromString($data['opportunityId']);
            $data['id'] = Uuid::v4()->toString();
            $data['status'] = (bool) $data['status'];

            $this->phaseService->create($opportunityId, $data);
            $this->addFlash('success', $this->translator->trans('phase_success'));
        } catch (Exception $exception) {
            $this->addFlash('error', $this->translator->trans('phase_error', [
                '%message%' => $exception->getMessage(),
            ]));

            return $this->redirectToRoute('admin_phase_create', [
                'opportunityId' => $opportunityId->toRfc4122(),
            ]);
        }

        return $this->redirectToRoute('admin_opportunity_get', [
            'id' => $opportunityId->toRfc4122(),
            '_fragment' => 'phases',
        ]);
    }
}

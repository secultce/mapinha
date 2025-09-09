<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Document\OpportunityTimeline;
use App\DocumentService\OpportunityTimelineDocumentService;
use App\Enum\UserRolesEnum;
use App\Exception\InscriptionOpportunity\AlreadyInscriptionOpportunityException;
use App\Exception\UnauthorizedException;
use App\Exception\ValidatorException;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\EventServiceInterface;
use App\Service\Interface\InitiativeServiceInterface;
use App\Service\Interface\InscriptionOpportunityServiceInterface;
use App\Service\Interface\OpportunityServiceInterface;
use App\Service\Interface\SpaceServiceInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class OpportunityAdminController extends AbstractAdminController
{
    public const CREATE_FORM_ID = 'add-opportunity';
    public const EDIT_FORM_ID = 'edit-opportunity';
    public const CREATE_PHASE_FORM_ID = 'add-opportunity-phase';

    public function __construct(
        private readonly OpportunityServiceInterface $service,
        private readonly OpportunityTimelineDocumentService $documentService,
        private readonly AgentServiceInterface $agentService,
        private readonly EventServiceInterface $eventService,
        private readonly InitiativeServiceInterface $initiativeService,
        private readonly SpaceServiceInterface $spaceService,
        private readonly TranslatorInterface $translator,
        private readonly InscriptionOpportunityServiceInterface $inscriptionOpportunityService,
        private readonly Security $security,
        private readonly OpportunityTimeline $opportunityTimeline,
    ) {
    }

    private static function hidrate(array $data): array
    {
        $externalLinks = ['links' => $data['extraFields']['links'] ?? [], 'videos' => $data['extraFields']['videos'] ?? []];
        foreach ($externalLinks as $field => $values) {
            $data['extraFields'][$field] = array_filter(
                $values,
                fn ($value) => '' !== $value['name'] || '' !== $value['url'],
            );
        }

        if (isset($data['entity'])) {
            [$entity ,$associatedEntity] = explode('_', $data['entity']);
            $data[$entity] = $associatedEntity;
            unset($data['entity']);
        }

        return $data;
    }

    #[IsGranted(new Expression('
        is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or 
        is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")
    '), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if ('POST' !== $request->getMethod()) {
            $opportunities = $this->service->findBy();
            $agents = $this->agentService->findBy();
            $events = $this->eventService->findBy();
            $initiatives = $this->initiativeService->findBy();
            $spaces = $this->spaceService->findBy();

            return $this->render('opportunity/create.html.twig', [
                'id' => Uuid::v4()->toRfc4122(),
                'opportunities' => $opportunities,
                'agents' => $agents,
                'events' => $events,
                'initiatives' => $initiatives,
                'spaces' => $spaces,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $data = $request->request->all();
        $files = $request->files->all();

        $data = $this->hidrate($data);

        try {
            $opportunity = $this->service->create($data);
            if ($files['extraFields']['coverImage'] ?? null instanceof UploadedFile) {
                $this->service->updateCoverImage($opportunity->getId(), $files['extraFields']['coverImage']);
            }

            $this->addFlash('success', $this->translator->trans('view.opportunity.message.created'));
        } catch (ValidatorException $exception) {
            $this->addFlash('error', $exception->getConstraintViolationList());

            return $this->redirectToRoute('admin_opportunity_create');
        } catch (Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin_opportunity_create');
        }

        return $this->redirectToRoute('admin_opportunity_list');
    }

    #[IsGranted(new Expression('
        is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or 
        is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")
    '), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $opportunities = $this->service->findBy();

        return $this->render('opportunity/list.html.twig', [
            'opportunities' => $opportunities,
        ]);
    }

    #[IsGranted(new Expression('
        is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or 
        is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")
    '), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(?Uuid $id, Request $request): Response
    {
        if ('POST' !== $request->getMethod()) {
            return $this->render('opportunity/edit.html.twig', [
                'opportunity' => $this->service->get($id),
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        $data = self::hidrate($request->request->all());
        $files = $request->files->all();

        try {
            $this->service->update($id, $data);
            if ($files['image'] instanceof UploadedFile) {
                $this->service->updateImage($id, $data['image']);
            }
            if ($files['extraFields']['coverImage'] instanceof UploadedFile) {
                $this->service->updateCoverImage($id, $data['extraFields']['coverImage']);
            }

            $this->addFlash('success', $this->translator->trans('view.opportunity.message.updated'));
        } catch (ValidatorException $exception) {
            $this->addFlash('error', $exception->getConstraintViolationList());

            return $this->redirectToRoute('admin_opportunity_edit', ['id' => $id]);
        } catch (Exception $exception) {
            $this->addFlash('error', [$exception->getMessage()]);

            return $this->redirectToRoute('admin_opportunity_edit', ['id' => $id]);
        }

        return $this->redirectToRoute('admin_opportunity_list');
    }

    #[IsGranted(new Expression('
        is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or 
        is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")
    '), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $this->service->remove($id);
        $this->addFlash('success', 'view.opportunity.message.deleted');

        return $this->redirectToRoute('admin_opportunity_list');
    }

    #[IsGranted(new Expression('
        is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or 
        is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")
    '), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function timeline(Uuid $id): Response
    {
        $events = $this->documentService->getEventsByEntityId($id);

        return $this->render('opportunity/timeline.html.twig', [
            'opportunity' => $this->service->get($id),
            'events' => $events,
        ]);
    }

    #[IsGranted(new Expression('
        is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or 
        is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")
    '), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function get(Uuid $id): Response
    {
        $opportunity = $this->service->get($id);

        $inscriptions = $this->inscriptionOpportunityService->list($id);
        $phases = $opportunity->getPhases();

        return $this->render('opportunity/details.html.twig', [
            'opportunity' => $opportunity,
            'inscriptions' => $inscriptions,
            'phases' => $phases,
            'create_phase_form_id' => self::CREATE_PHASE_FORM_ID,
        ]);
    }

    public function subscribe(Uuid $id): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            $this->addFlashError($this->translator->trans('view.opportunity.registration_not_logged'));

            return $this->redirectToRoute('web_auth_login');
        }
        $agent = $user->getAgents()->getValues()[0];

        try {
            $this->inscriptionOpportunityService->create($id, [
                'id' => Uuid::v4(),
                'agent' => $agent->getId()->toRfc4122(),
                'status' => 'active',
            ]);
        } catch (AlreadyInscriptionOpportunityException) {
            $this->addFlashError($this->translator->trans('view.opportunity.registration_duplicated'));

            return $this->redirectToRoute('web_opportunity_list');
        } catch (UnauthorizedException) {
            $this->addFlashError($this->translator->trans('view.opportunity.registration_unauthorized'));

            return $this->redirectToRoute('web_opportunity_list');
        }
        $this->addFlashSuccess($this->translator->trans('view.opportunity.registration_success'));

        return $this->redirectToRoute('admin_registration_list');
    }
}

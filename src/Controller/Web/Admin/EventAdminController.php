<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\DocumentService\EventTimelineDocumentService;
use App\Enum\EventFormatEnum;
use App\Enum\SocialNetworkEnum;
use App\Enum\UserRolesEnum;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\CulturalLanguageServiceInterface;
use App\Service\Interface\EventServiceInterface;
use App\Service\Interface\InscriptionEventServiceInterface;
use App\Service\Interface\StateServiceInterface;
use App\Service\Interface\TagServiceInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use TypeError;

class EventAdminController extends AbstractAdminController
{
    private const string VIEW_ADD = 'event/create.html.twig';
    public const string CREATE_FORM_ID = 'add-event';
    public const string EDIT_FORM_ID = 'edit-event';

    public function __construct(
        private readonly EventServiceInterface $service,
        private readonly InscriptionEventServiceInterface $inscriptionService,
        private readonly StateServiceInterface $stateService,
        private readonly CityServiceInterface $cityService,
        private readonly TranslatorInterface $translator,
        private readonly EventTimelineDocumentService $documentService,
        private readonly Security $security,
        private readonly TagServiceInterface $tagService,
        private readonly CulturalLanguageServiceInterface $culturalLanguageService,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function getOne(Uuid $id): Response
    {
        $event = $this->service->get($id);

        $createdById = $event->getCreatedBy()->getId()->toRfc4122();

        $inscriptions = $this->inscriptionService->list($id);

        return $this->render('_admin/event/one.html.twig', [
            'event' => $event,
            'inscriptions' => $inscriptions,
            'createdById' => $createdById,
        ], parentPath: '');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $events = $this->service->findBy();

        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function timeline(?Uuid $id): Response
    {
        $events = $this->documentService->getEventsByEntityId($id);

        return $this->render('event/timeline.html.twig', [
            'event' => $this->service->get($id),
            'events' => $events,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $this->service->remove($id);

        $this->addFlash('success', $this->translator->trans('view.event.message.deleted'));

        return $this->redirectToRoute('admin_event_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function togglePublish(?Uuid $id): Response
    {
        $this->service->togglePublish($id);

        return $this->redirectToRoute('admin_event_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if (false === $request->isMethod('POST')) {
            $culturalLanguageItems = $this->culturalLanguageService->list();
            $type = EventFormatEnum::cases();

            return $this->render(self::VIEW_ADD, [
                'form_id' => self::CREATE_FORM_ID,
                'culturalLanguageItems' => $culturalLanguageItems,
                'typeItems' => $type,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $culturalLanguages = $request->get('culturalLanguages', []);
        $eventFormatType = (int) $request->request->get('eventFormatType', 1);
        $startDate = $request->request->get('startDate');

        $event = [
            'id' => Uuid::v4(),
            'name' => $name,
            'shortDescription' => $description,
            'culturalLanguages' => $culturalLanguages,
            'agentGroup' => null,
            'format' => $eventFormatType,
            'startDate' => $startDate,
        ];

        try {
            $this->service->create($event);
            $this->addFlashSuccess($this->translator->trans('view.event.message.created'));
        } catch (TypeError $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->render(self::VIEW_ADD, [
                'error' => $exception->getMessage(),
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        return $this->list();
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(Uuid $id, Request $request): Response
    {
        try {
            $event = $this->service->get($id);
        } catch (Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin_event_list');
        }

        if (Request::METHOD_POST !== $request->getMethod()) {
            $states = $this->stateService->list();

            $cities = [];
            if ($event->getAddress()) {
                $filtersToCities = [
                    'state' => $event->getAddress()->getCity()->getState()->getId(),
                ];
                $cities = $this->cityService->findBy($filtersToCities);
            }

            $culturalLanguageItems = $this->culturalLanguageService->list();
            $tagItems = $this->tagService->list();
            $type = EventFormatEnum::cases();

            return $this->render('event/edit.html.twig', [
                'event' => $event,
                'form_id' => self::EDIT_FORM_ID,
                'culturalLanguageItems' => $culturalLanguageItems,
                'states' => $states,
                'cities' => $cities,
                'tagItems' => $tagItems,
                'typeItems' => $type,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        $name = $request->request->get('name');
        $subtitle = $request->request->get('subtitle');
        $description = $request->request->get('description');
        $shortDescription = $request->request->get('short_description');
        $longDescription = $request->request->get('long_description');
        $site = $request->request->get('site');
        $ageRating = $request->request->get('age_rating') ?? null;
        $format = (int) $request->request->get('format');
        $maxCapacity = (int) $request->request->get('max_capacity') ?? null;
        $culturalLanguages = $request->get('culturalLanguages') ?? [];
        $tags = $request->get('tags') ?? [];

        $networks = [];
        foreach (SocialNetworkEnum::getValues() as $network) {
            if ('' !== $request->get("social_networks_{$network}")) {
                $networks[$network] = $request->get("social_networks_{$network}");
            }
        }

        $dataToUpdate = [
            'name' => $name,
            'subtitle' => $subtitle,
            'description' => $description,
            'shortDescription' => $shortDescription,
            'longDescription' => $longDescription,
            'site' => $site,
            'extraFields' => [
                'ageRating' => $ageRating,
            ],
            'agentGroup' => null,
            'format' => $format,
            'maxCapacity' => $maxCapacity,
            'culturalLanguages' => $culturalLanguages,
            'socialNetworks' => $networks,
            'tags' => $tags,
            'addressData' => [
                'id' => $event->getAddress()?->getId() ?? Uuid::v4(),
                'owner' => $event->getId()->toRfc4122(),
                'zipcode' => $request->request->get('address_cep'),
                'street' => $request->request->get('address_street'),
                'number' => $request->request->get('address_number'),
                'neighborhood' => $request->request->get('address_neighborhood'),
                'complement' => $request->request->get('address_complement'),
                'state' => $request->request->get('address_state'),
                'city' => $request->request->get('address_city'),
            ],
            'updatedBy' => $this->security->getUser()->getAgents()->getValues()[0]->getId(),
        ];

        try {
            $this->service->update($id, $dataToUpdate);

            if ($uploadedImage = $request->files->get('profileImage')) {
                $this->service->updateImage($id, $uploadedImage);
            }

            if ($uploadedCover = $request->files->get('coverImage')) {
                $this->service->updateCoverImage($id, $uploadedCover);
            }

            $this->addFlashSuccess($this->translator->trans('view.event.message.updated'));

            return $this->redirectToRoute('admin_event_list');
        } catch (TypeError|Exception $exception) {
            $this->addFlashError($exception->getMessage());

            $states = $this->stateService->findBy();
            $cities = $this->cityService->findBy();

            $culturalLanguageItems = $this->culturalLanguageService->list();
            $tagItems = $this->tagService->list();
            $type = EventFormatEnum::cases();

            return $this->render('event/edit.html.twig', [
                'event' => $event,
                'error' => $exception->getMessage(),
                'form_id' => self::EDIT_FORM_ID,
                'culturalLanguageItems' => $culturalLanguageItems,
                'states' => $states,
                'cities' => $cities,
                'tagItems' => $tagItems,
                'typeItems' => $type,
            ]);
        }
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function checkinInscription(Uuid $id, Uuid $inscription): Response
    {
        $this->inscriptionService->checkIn($id, $inscription);

        $this->addFlashSuccess($this->translator->trans('check_in'));

        return $this->getOne($id);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function suspendInscription(Uuid $id, Uuid $inscription): Response
    {
        $this->inscriptionService->suspend($id, $inscription);

        $this->addFlashSuccess($this->translator->trans('inscription_suspended'));

        return $this->getOne($id);
    }
}

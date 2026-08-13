<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\DocumentService\SpaceTimelineDocumentService;
use App\Enum\SocialNetworkEnum;
use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\ActivityAreaServiceInterface;
use App\Service\Interface\ArchitecturalAccessibilityServiceInterface;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\SpaceServiceInterface;
use App\Service\Interface\StateServiceInterface;
use App\Service\Interface\TagServiceInterface;
use App\Service\SpaceTypeService;
use DateTime;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use TypeError;

class SpaceAdminController extends AbstractAdminController
{
    private const string VIEW_LIST = 'space/list.html.twig';
    private const string VIEW_ADD = 'space/create.html.twig';
    private const string VIEW_EDIT = 'space/edit.html.twig';

    public const string CREATE_FORM_ID = 'add-space';
    public const string EDIT_FORM_ID = 'edit-space';

    public function __construct(
        private readonly SpaceServiceInterface $service,
        private readonly SpaceTimelineDocumentService $documentService,
        private readonly TranslatorInterface $translator,
        private readonly Security $security,
        private readonly ArchitecturalAccessibilityServiceInterface $architecturalAccessibilityService,
        private readonly ActivityAreaServiceInterface $activityAreaService,
        private readonly TagServiceInterface $tagService,
        private readonly StateServiceInterface $stateService,
        private readonly CityServiceInterface $cityService,
        private readonly SpaceTypeService $spaceTypeService,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $spaces = $this->service->findBy();

        return $this->render(self::VIEW_LIST, [
            'spaces' => $spaces,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $this->service->remove($id);

        $this->addFlashSuccess($this->translator->trans('view.space.message.deleted'));

        return $this->redirectToRoute('admin_space_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        $userAgents = $this->security->getUser()->getAgents();

        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_ADD, [
                'form_id' => self::CREATE_FORM_ID,
                'agents' => $userAgents->getValues(),
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $name = $request->request->get('name');
        $shortDescription = $request->request->get('shortDescription');
        $createdBy = $request->request->get('createdBy');
        $maxCapacity = $request->request->get('maxCapacity');

        $space = [
            'id' => Uuid::v4(),
            'name' => $name,
            'shortDescription' => $shortDescription,
            'createdBy' => $createdBy,
            'maxCapacity' => (int) $maxCapacity,
        ];

        try {
            $this->service->create($space);
            $this->addFlashSuccess($this->translator->trans('view.space.message.created'));
        } catch (Exception|TypeError $exception) {
            $this->addFlashError($exception->getMessage());

            return $this->render(self::VIEW_ADD, [
                'error' => $exception->getMessage(),
                'form_id' => self::CREATE_FORM_ID,
                'agents' => $userAgents->getValues(),
            ]);
        }

        return $this->redirectToRoute('admin_space_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function timeline(Uuid $id): Response
    {
        $events = $this->documentService->getEventsByEntityId($id);

        return $this->render('space/timeline.html.twig', [
            'space' => $this->service->get($id),
            'events' => $events,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(Uuid $id, Request $request): Response
    {
        try {
            $space = $this->service->get($id);
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());

            return $this->redirectToRoute('admin_space_list');
        }

        if (Request::METHOD_POST !== $request->getMethod()) {
            $accessibilities = $this->architecturalAccessibilityService->list();
            $activityAreaItems = $this->activityAreaService->list();
            $tagItems = $this->tagService->list();
            $states = $this->stateService->list();
            $types = $this->spaceTypeService->list();

            $cities = [];
            if ($space->getAddress()) {
                $filtersToCities = [
                    'state' => $space->getAddress()->getCity()->getState()->getId(),
                ];
                $cities = $this->cityService->findBy($filtersToCities);
            }

            return $this->render(self::VIEW_EDIT, [
                'space' => $space,
                'types' => $types,
                'form_id' => self::EDIT_FORM_ID,
                'accessibilities' => $accessibilities,
                'activityAreaItems' => $activityAreaItems,
                'tagItems' => $tagItems,
                'states' => $states,
                'cities' => $cities,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        $name = $request->request->get('name');
        $date = $request->request->get('date') ?? null;
        $tags = $request->get('tags') ?? [];
        $activityAreas = $request->get('activityAreas') ?? [];
        $isAccessible = (bool) $request->request->get('architectural_accessibility_option');
        $accessibilities = $request->get('architectural_accessibility') ?? [];

        $networks = [];
        foreach (SocialNetworkEnum::getValues() as $network) {
            if ('' !== $request->get("social_networks_{$network}")) {
                $networks[$network] = $request->get("social_networks_{$network}");
            }
        }

        $currentExtraFields = $space->getExtraFields() ?? [];

        $extraFields = $this->mountOpeningHours($request->request->get('opening_hours'), $currentExtraFields);

        $dataToUpdate = [
            'name' => $name,
            'shortDescription' => $request->request->get('short_description'),
            'longDescription' => $request->request->get('long_description'),
            'site' => $request->request->get('site'),
            'phoneNumber' => $request->request->get('phone_number'),
            'email' => $request->request->get('email'),
            'maxCapacity' => (int) $request->request->get('capacity'),
            'spaceType' => $request->request->get('type'),
            'isAccessible' => $isAccessible,
            'tags' => $tags,
            'activityAreas' => $activityAreas,
            'socialNetworks' => $networks,
            'accessibilities' => $isAccessible ? $accessibilities : [],
            'date' => $date ? new DateTime($date) : null,
            'createdBy' => $space->getCreatedBy()->getId(),
            'updatedBy' => $this->security->getUser()->getAgents()->getValues()[0]->getId(),
            'extraFields' => $extraFields,
            'addressData' => [
                'id' => $space->getAddress()?->getId() ?? Uuid::v4(),
                'owner' => $space->getId()->toRfc4122(),
                'zipcode' => $request->request->get('address_cep'),
                'street' => $request->request->get('address_street'),
                'number' => $request->request->get('address_number'),
                'neighborhood' => $request->request->get('address_neighborhood'),
                'complement' => $request->request->get('address_complement'),
                'state' => $request->request->get('address_state'),
                'city' => $request->request->get('address_city'),
            ],
            'entityAssociation' => [
                'id' => $space->getEntityAssociation()?->getId() ?? Uuid::v4(),
                'space' => $space->getId(),
                'withAgent' => (bool) $request->request->get('association_with_agent', default: false),
                'withEvent' => (bool) $request->request->get('association_with_event', default: false),
                'withInitiative' => (bool) $request->request->get('association_with_initiative', default: false),
                'withOpportunity' => (bool) $request->request->get('association_with_opportunity', default: false),
                'withOrganization' => (bool) $request->request->get('association_with_organization', default: false),
                'withSpace' => (bool) $request->request->get('association_with_space', default: false),
            ],
        ];
        try {
            $this->service->update($id, $dataToUpdate);

            if ($uploadedImage = $request->files->get('profileImage')) {
                $this->service->updateImage($id, $uploadedImage);
            }

            if ($uploadedCover = $request->files->get('coverImage')) {
                $this->service->updateCoverImage($id, $uploadedCover);
            }

            $portfolioImages = $request->files->get('portfolioImages') ?? [];
            $portfolioDescriptions = $request->request->all('portfolioDescriptions') ?? [];
            foreach ($portfolioImages as $index => $portfolioImage) {
                $description = $portfolioDescriptions[$index] ?? null;
                $this->service->addPortfolioImage($space, $portfolioImage, $description);
            }

            $this->addFlashSuccess($this->translator->trans('view.space.message.updated'));

            return $this->redirectToRoute('admin_space_list');
        } catch (TypeError|Exception|ValidatorException $exception) {
            $this->addFlashErrorByException($exception);

            $accessibilities = $this->architecturalAccessibilityService->list();
            $activityAreaItems = $this->activityAreaService->list();
            $tagItems = $this->tagService->list();
            $states = $this->stateService->findBy();
            $cities = $this->cityService->findBy();

            return $this->render(self::VIEW_EDIT, [
                'space' => $space,
                'error' => $exception->getMessage(),
                'form_id' => self::EDIT_FORM_ID,
                'accessibilities' => $accessibilities,
                'activityAreaItems' => $activityAreaItems,
                'tagItems' => $tagItems,
                'states' => $states,
                'cities' => $cities,
                'types' => $this->spaceTypeService->list(),
            ]);
        }
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function togglePublish(?Uuid $id): Response
    {
        $this->service->togglePublish($id);

        return $this->redirectToRoute('admin_space_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function removePortfolioPhoto(Uuid $id, Uuid $photoId): Response
    {
        try {
            $this->service->removePortfolioImage($id, $photoId);
            $this->addFlashSuccess($this->translator->trans('photo_removed'));
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());
        }

        return $this->redirectToRoute('admin_space_edit', ['id' => $id]);
    }

    public function addFlashErrorByException(Exception $exception): void
    {
        if ($exception instanceof ValidatorException) {
            foreach ($exception->getConstraintViolationList() as $error) {
                $this->addFlashError($error->getPropertyPath().': '.$error->getMessage());
            }

            return;
        }

        $this->addFlashError($exception->getMessage());
    }

    private function mountOpeningHours(?string $openingHours, array $extraFields): array
    {
        if (!empty($openingHours)) {
            $openingHoursData = json_decode($openingHours, true);
            if (JSON_ERROR_NONE === json_last_error() && !empty($openingHoursData)) {
                $extraFields['openingHours'] = $openingHoursData;
            }
        } else {
            unset($extraFields['openingHours']);
        }

        return $extraFields;
    }
}

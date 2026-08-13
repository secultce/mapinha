<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\DocumentService\AgentTimelineDocumentService;
use App\Enum\EducationEnum;
use App\Enum\FlashMessageTypeEnum;
use App\Enum\GenderEnum;
use App\Enum\RaceEnum;
use App\Enum\SexualOrientationEnum;
use App\Enum\SocialNetworkEnum;
use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\ActivityAreaServiceInterface;
use App\Service\Interface\AddressServiceInterface;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\CulturalFunctionServiceInterface;
use App\Service\Interface\StateServiceInterface;
use App\Service\Interface\TagServiceInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class AgentAdminController extends AbstractAdminController
{
    private const string VIEW_ADD = 'agent/create.html.twig';
    private const string VIEW_EDIT = 'agent/edit.html.twig';

    public const string CREATE_FORM_ID = 'add-agent';
    public const string EDIT_FORM_ID = 'edit-agent';

    public function __construct(
        private readonly AgentServiceInterface $service,
        private readonly AgentTimelineDocumentService $documentService,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly TranslatorInterface $translator,
        private readonly StateServiceInterface $stateService,
        private readonly ActivityAreaServiceInterface $activityAreaService,
        private readonly TagServiceInterface $tagService,
        private readonly CulturalFunctionServiceInterface $culturalFunctionService,
        private readonly AddressServiceInterface $addressService,
        private readonly Security $security,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(UserInterface $user): Response
    {
        $agents = $this->service->findBy();

        $token = $this->jwtManager->create($user);

        return $this->render('agent/list.html.twig', [
            'agents' => $agents,
            'token' => $token,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_ADD, [
                'form_id' => self::CREATE_FORM_ID,
                'states' => $this->stateService->list(),
                'cities' => [],
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $errors = [];

        try {
            $agentData = $this->extractAgentDataFromRequest($request);
            $addressData = $this->extractAddressDataFromRequest($request);

            $agent = $this->service->create($agentData);

            if (!empty($addressData)) {
                $this->addressService->create($agent, $addressData);
            }

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.agent.message.created'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::VIEW_ADD, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
                'states' => $this->stateService->list(),
                'cities' => [],
            ]);
        }

        return $this->redirectToRoute('admin_agent_list');
    }

    private function extractAgentDataFromRequest(Request $request): array
    {
        return [
            'id' => Uuid::v4(),
            'name' => $request->get('name'),
            'shortBio' => $request->get('shortBio'),
            'longBio' => $request->get('shortBio'),
            'culture' => false,
            'user' => $this->security->getUser()->getId(),
            'fiscalCode' => $request->get('cpf') ?: $request->get('mei'),
            'extraFields' => $this->extractExtraFieldsFromRequest($request),
        ];
    }

    private function extractExtraFieldsFromRequest(Request $request): array
    {
        $extraFields = [];

        $optionalFields = ['social_name', 'full_name', 'public_email', 'private_phone1', 'private_phone2', 'site', 'link_description'];

        foreach ($optionalFields as $field) {
            if ($request->get($field)) {
                $extraFields[$field] = $request->get($field);
            }
        }

        $sensitiveFields = $this->extractSensitiveDataFromRequest($request);

        return array_merge($extraFields, $sensitiveFields);
    }

    private function extractSensitiveDataFromRequest(Request $request): array
    {
        $sensitiveData = [];

        if ($request->get('birthday')) {
            $sensitiveData['birthday'] = $request->get('birthday');
            $sensitiveData['birthday_public'] = (bool) $request->get('birthday_public');
        }

        if ($request->get('gender')) {
            $sensitiveData['gender'] = $request->get('gender');
            $sensitiveData['gender_public'] = (bool) $request->get('gender_public');
        }

        if ($request->get('sexual_orientation')) {
            $sensitiveData['sexual_orientation'] = $request->get('sexual_orientation');
            $sensitiveData['sexual_orientation_public'] = (bool) $request->get('sexual_orientation_public');
        }

        if ($request->get('race')) {
            $sensitiveData['race'] = $request->get('race');
            $sensitiveData['race_public'] = (bool) $request->get('race_public');
        }

        if ($request->get('education')) {
            $sensitiveData['education'] = $request->get('education');
            $sensitiveData['education_public'] = (bool) $request->get('education_public');
        }

        if (null !== $request->get('is_disabled') && '' !== $request->get('is_disabled')) {
            $sensitiveData['is_disabled'] = (bool) (int) $request->get('is_disabled');
            $sensitiveData['disabled_public'] = (bool) $request->get('disabled_public');
        }

        if (null !== $request->get('is_indigenous') && '' !== $request->get('is_indigenous')) {
            $sensitiveData['is_indigenous'] = (bool) (int) $request->get('is_indigenous');
            $sensitiveData['indigenous_public'] = (bool) $request->get('indigenous_public');
        }

        if (null !== $request->get('is_quilombola') && '' !== $request->get('is_quilombola')) {
            $sensitiveData['is_quilombola'] = (bool) (int) $request->get('is_quilombola');
            $sensitiveData['quilombola_public'] = (bool) $request->get('quilombola_public');
        }

        if (null !== $request->get('is_traditional_people') && '' !== $request->get('is_traditional_people')) {
            $sensitiveData['is_traditional_people'] = (bool) (int) $request->get('is_traditional_people');
            $sensitiveData['traditional_people_public'] = (bool) $request->get('traditional_people_public');
        }

        return array_filter($sensitiveData);
    }

    private function extractAddressDataFromRequest(Request $request): array
    {
        if (!$request->get('postal_code') && !$request->get('street')) {
            return [];
        }

        return [
            'zipcode' => $request->get('postal_code') ?: '',
            'street' => $request->get('street') ?: '',
            'number' => $request->get('number') ?: '',
            'neighborhood' => $request->get('neighborhood') ?: '',
            'complement' => $request->get('complement_or_reference_point') ?: '',
            'cityId' => $request->get('address_city'),
        ];
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function timeline(?Uuid $id): Response
    {
        $agent = $this->service->get($id);

        $this->denyAccessUnlessGranted('get', $agent);

        $events = $this->documentService->getEventsByEntityId($id);

        return $this->render('agent/timeline.html.twig', [
            'agent' => $agent,
            'events' => $events,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $agent = $this->service->get($id);

        $this->denyAccessUnlessGranted('remove', $agent);

        $this->service->remove($id);

        $this->addFlash('success', 'view.agent.message.deleted');

        return $this->redirectToRoute('admin_agent_list');
    }

    public function edit(Uuid $id, Request $request): Response
    {
        $agent = $this->service->get($id);

        $this->denyAccessUnlessGranted('edit', $agent);

        if (false === $request->isMethod(Request::METHOD_POST)) {
            $activityAreaItems = $this->activityAreaService->list();
            $tagItems = $this->tagService->list();
            $culturalFunctionItems = $this->culturalFunctionService->list();

            return $this->render(self::VIEW_EDIT, [
                'activityAreaItems' => $activityAreaItems,
                'tagItems' => $tagItems,
                'culturalFunctionItems' => $culturalFunctionItems,
                'genderOptions' => GenderEnum::getValues(),
                'raceOptions' => RaceEnum::getValues(),
                'educationOptions' => EducationEnum::getValues(),
                'sexualOrientationOptions' => SexualOrientationEnum::getValues(),
                'agent' => $agent,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        $networks = [];
        foreach (SocialNetworkEnum::getValues() as $network) {
            if ('' !== $request->get("social_networks_{$network}")) {
                $networks[$network] = $request->get("social_networks_{$network}");
            }
        }

        $extraFields = array_filter([
            'site' => $request->request->get('site'),
            'link_description' => $request->request->get('link_description'),
            'public_email' => $request->request->get('public_email'),
            'public_phone' => $request->request->get('public_phone'),
        ]);

        $sensitiveFields = $this->extractSensitiveDataFromRequest($request);
        $extraFields = array_merge($extraFields, $sensitiveFields);

        $rolesInCultureIds = $request->request->all('roles_in_culture') ?? [];

        $errors = [];

        try {
            $this->service->update($id, [
                'name' => $request->request->get('name'),
                'shortBio' => $request->request->get('short_description'),
                'longBio' => $request->request->get('long_description'),
                'culture' => $agent->isCulture(),
                'user' => $agent->getUser()->getId()->toRfc4122(),
                'socialNetworks' => $networks,
                'extraFields' => $extraFields ?: null,
                'culturalFunction' => $rolesInCultureIds,
            ]);

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
                $this->service->addPortfolioImage($agent, $portfolioImage, $description);
            }

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.agent.message.updated'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            $activityAreaItems = $this->activityAreaService->list();
            $tagItems = $this->tagService->list();
            $culturalFunctionItems = $this->culturalFunctionService->list();

            return $this->render(self::VIEW_EDIT, [
                'activityAreaItems' => $activityAreaItems,
                'tagItems' => $tagItems,
                'culturalFunctionItems' => $culturalFunctionItems,
                'genderOptions' => GenderEnum::getValues(),
                'raceOptions' => RaceEnum::getValues(),
                'sexualOrientationOptions' => SexualOrientationEnum::getValues(),
                'educationOptions' => EducationEnum::getValues(),
                'agent' => $agent,
                'errors' => $errors,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        return $this->redirectToRoute('admin_agent_edit', ['id' => $id]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function removePortfolioPhoto(Uuid $id, Uuid $photoId): Response
    {
        try {
            $this->service->removePortfolioImage($id, $photoId);
            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('photo_removed'));
        } catch (Exception $exception) {
            $this->addFlash(FlashMessageTypeEnum::ERROR->value, $exception->getMessage());
        }

        return $this->redirectToRoute('admin_agent_edit', ['id' => $id]);
    }
}

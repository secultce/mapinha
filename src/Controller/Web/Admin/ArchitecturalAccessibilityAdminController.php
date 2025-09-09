<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\ArchitecturalAccessibilityServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArchitecturalAccessibilityAdminController extends AbstractAdminController
{
    private const string VIEW_LIST = 'architectural-accessibility/list.html.twig';
    private const string VIEW_ADD = 'architectural-accessibility/create.html.twig';
    private const string VIEW_EDIT = 'architectural-accessibility/edit.html.twig';
    private const string CREATE_FORM_ID = 'add-architectural-accessibility';
    private const string EDIT_FORM_ID = 'edit-architectural-accessibility';

    public function __construct(
        private ArchitecturalAccessibilityServiceInterface $architecturalAccessibilityService,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $accessibilities = $this->architecturalAccessibilityService->list();

        return $this->render(self::VIEW_LIST, [
            'accessibilities' => $accessibilities,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function add(Request $request): Response
    {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_ADD, [
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        try {
            $this->architecturalAccessibilityService->create([
                'id' => Uuid::v4(),
                'name' => $request->get('name'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.architectural_accessibility.message.created'));

            return $this->redirectToRoute('admin_architectural_accessibility_list');
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();

            return $this->render(self::VIEW_ADD, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(Uuid $id, Request $request): Response
    {
        try {
            $accessibility = $this->architecturalAccessibilityService->getOne($id);
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());

            return $this->redirectToRoute('admin_architectural_accessibility_list');
        }

        if (Request::METHOD_POST !== $request->getMethod()) {
            return $this->render(self::VIEW_EDIT, [
                'accessibility' => $accessibility,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        try {
            $this->architecturalAccessibilityService->update($id, [
                'name' => $request->get('name'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.architectural_accessibility.message.updated'));

            return $this->redirectToRoute('admin_architectural_accessibility_list');
        } catch (ValidatorException $exception) {
            return $this->render(self::VIEW_EDIT, [
                'accessibility' => $accessibility,
                'errors' => $exception->getConstraintViolationList(),
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $this->architecturalAccessibilityService->remove($id);

        $this->addFlashSuccess($this->translator->trans('view.architectural_accessibility.message.removed'));

        return $this->redirectToRoute('admin_architectural_accessibility_list');
    }
}

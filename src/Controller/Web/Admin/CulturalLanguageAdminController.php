<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\CulturalLanguageServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class CulturalLanguageAdminController extends AbstractAdminController
{
    private const string LIST = 'cultural-language/list.html.twig';
    private const string CREATE = 'cultural-language/create.html.twig';
    public const string CREATE_FORM_ID = 'add-cultural-language';
    private const string EDIT = 'cultural-language/edit.html.twig';
    private const string EDIT_FORM_ID = 'edit-cultural-language';

    public function __construct(
        private readonly CulturalLanguageServiceInterface $culturalLanguageService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $culturalLanguages = $this->culturalLanguageService->list();

        return $this->render(self::LIST, [
            'culturalLanguages' => $culturalLanguages,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::CREATE, [
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $errors = [];

        try {
            $this->culturalLanguageService->create([
                'id' => Uuid::v4(),
                'name' => $request->get('name'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.cultural_language.message.created'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::CREATE, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        return $this->list();
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(Uuid $id, Request $request): Response
    {
        try {
            $culturalLanguage = $this->culturalLanguageService->get($id);
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());

            return $this->list();
        }

        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::EDIT, [
                'culturalLanguage' => $culturalLanguage,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $errors = [];

        try {
            $this->culturalLanguageService->update($id, [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.cultural_language.message.edited'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::EDIT, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        return $this->list();
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function delete(Uuid $id): Response
    {
        try {
            $this->culturalLanguageService->remove($id);
            $this->addFlashSuccess($this->translator->trans('view.cultural_language.message.deleted'));
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());
        }

        return $this->list();
    }
}

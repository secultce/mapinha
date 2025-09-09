<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\FlashMessageTypeEnum;
use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\TagService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagAdminController extends AbstractAdminController
{
    private const string ADD = 'tag/create.html.twig';
    private const string LIST = 'tag/list.html.twig';
    private const string EDIT = 'tag/edit.html.twig';

    public const CREATE_FORM_ID = 'add-tag';
    public const EDIT_FORM_ID = 'edit-tag';

    public function __construct(
        private readonly TagService $tagService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        return $this->render(self::LIST, [
            'tags' => $this->tagService->list(),
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::ADD, [
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $errors = [];

        try {
            $this->tagService->create([
                'id' => Uuid::v4(),
                'name' => $request->get('name'),
            ]);

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.tag.message.created'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::ADD, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        return $this->redirectToRoute('admin_tag_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(string $id): Response
    {
        try {
            $this->tagService->remove(Uuid::fromString($id));
            $this->addFlashSuccess($this->translator->trans('view.tag.message.deleted'));
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());
        }

        return $this->redirectToRoute('admin_tag_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(Request $request, string $id): Response
    {
        try {
            $tag = $this->tagService->get(Uuid::fromString($id));
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());

            return $this->redirectToRoute('admin_tag_list');
        }

        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->render(self::EDIT, [
                'tag' => $tag,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        $errors = [];

        try {
            $this->tagService->update(Uuid::fromString($id), [
                'name' => $request->request->get('name'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.tag.message.updated'));

            return $this->redirectToRoute('admin_tag_list');
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        return $this->render(self::EDIT, [
            'tag' => $tag,
            'errors' => $errors,
            'form_id' => self::EDIT_FORM_ID,
        ]);
    }
}

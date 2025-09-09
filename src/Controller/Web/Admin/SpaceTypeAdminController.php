<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\SpaceTypeServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class SpaceTypeAdminController extends AbstractAdminController
{
    private const string LIST = 'space-type/list.html.twig';
    private const string CREATE = 'space-type/create.html.twig';
    private const string CREATE_FORM_ID = 'add-space-type';
    private const string EDIT = 'space-type/edit.html.twig';
    private const string EDIT_FORM_ID = 'edit-space-type';

    public function __construct(
        private readonly SpaceTypeServiceInterface $service,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $spaceTypes = $this->service->list();

        return $this->render(self::LIST, [
            'spaceTypes' => $spaceTypes,
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
            $this->service->create([
                'id' => Uuid::v4(),
                'name' => $request->get('name'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.space_type.message.created'));
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

        return $this->redirectToRoute('admin_space_type_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(string $id): Response
    {
        try {
            $this->service->remove(Uuid::fromString($id));

            $this->addFlashSuccess($this->translator->trans('space_type_deleted'));
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());
        }

        return $this->redirectToRoute('admin_space_type_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(Request $request, string $id): Response
    {
        try {
            $spaceType = $this->service->get(Uuid::fromString($id));
        } catch (Exception $exception) {
            $this->addFlashError($exception->getMessage());

            return $this->redirectToRoute('admin_space_type_list');
        }

        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->render(self::EDIT, [
                'spaceType' => $spaceType,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $erros = [];

        try {
            $this->service->update(Uuid::fromString($id), [
                'name' => $request->get('name'),
            ]);

            $this->addFlashSuccess($this->translator->trans('view.space_type.message.updated'));

            return $this->redirectToRoute('admin_space_type_list');
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        return $this->render(self::EDIT, [
            'errors' => $errors,
            'spaceType' => $spaceType,
            'form_id' => self::EDIT_FORM_ID,
        ]);
    }
}

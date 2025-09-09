<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\SealServiceInterface;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class SealAdminController extends AbstractAdminController
{
    public const VIEW_ADD = 'seal/add.html.twig';
    public const VIEW_EDIT = 'seal/edit.html.twig';

    public const CREATE_FORM_ID = 'add-seal';
    public const EDIT_FORM_ID = 'edit-seal';

    public function __construct(
        private SealServiceInterface $sealService,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $seals = $this->sealService->list();

        return $this->render('seal/list.html.twig', [
            'seals' => $seals,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function getOne(int $id): Response
    {
        $seal = [
            'name' => 'Selo'.$id,
            'status' => 'Ativo',
            'createdAt' => new DateTime('2023-12-01 10:00:00'),
        ];

        return $this->render('seal/one.html.twig', [
            'seal' => $seal,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function add(Request $request): Response
    {
        if ('POST' !== $request->getMethod()) {
            return $this->render(self::VIEW_ADD, [
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        try {
            $this->sealService->create([
                'id' => Uuid::v4(),
                'name' => $request->get('name'),
                'active' => null === $request->get('active') ? false : true,
                'description' => $request->get('description'),
            ]);
        } catch (ValidatorException $exception) {
            return $this->render(self::VIEW_ADD, [
                'errors' => $exception->getConstraintViolationList(),
                'form_id' => self::CREATE_FORM_ID,
            ]);
        } catch (Exception $exception) {
            return $this->render(self::VIEW_ADD, [
                'errors' => [$exception->getMessage()],
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->addFlash('success', $this->translator->trans('view.seal.message.created'));

        return $this->redirectToRoute('admin_seal_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $this->sealService->remove($id);

        $this->addFlash('success', $this->translator->trans('view.seal.message.deleted'));

        return $this->redirectToRoute('admin_seal_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(string $id, Request $request): Response
    {
        try {
            $seal = $this->sealService->get(Uuid::fromString($id));
        } catch (Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin_seal_list');
        }

        if (Request::METHOD_POST !== $request->getMethod()) {
            return $this->render(self::VIEW_EDIT, [
                'seal' => $seal,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        try {
            $this->sealService->update(Uuid::fromString($id), [
                'name' => $request->get('name'),
                'active' => null === $request->get('active') ? false : true,
                'description' => $request->get('description'),
            ]);

            $this->addFlash('success', $this->translator->trans('view.seal.message.updated'));

            return $this->redirectToRoute('admin_seal_list');
        } catch (ValidatorException $exception) {
            return $this->render(self::VIEW_EDIT, [
                'seal' => $seal,
                'errors' => $exception->getConstraintViolationList(),
                'form_id' => self::EDIT_FORM_ID,
            ]);
        } catch (Exception $exception) {
            return $this->render(self::VIEW_EDIT, [
                'seal' => $seal,
                'errors' => [$exception->getMessage()],
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }
    }
}

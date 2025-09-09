<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Enum\FlashMessageTypeEnum;
use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\FaqServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FaqAdminController extends AbstractAdminController
{
    public const VIEW_LIST = 'faq/list.html.twig';
    public const VIEW_ADD = 'faq/add.html.twig';
    public const VIEW_EDIT = 'faq/edit.html.twig';

    public const CREATE_FORM_ID = 'add-faq';
    public const EDIT_FORM_ID = 'edit-faq';

    public function __construct(
        private FaqServiceInterface $faqService,
        private readonly TranslatorInterface $translator
    ) {
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

        $errors = [];

        try {
            $this->faqService->create([
                'id' => Uuid::v4(),
                'answer' => $request->get('answer'),
                'question' => $request->get('question'),
            ]);

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.faq.message.created'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::VIEW_ADD, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        return $this->redirectToRoute('admin_faq_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $faqs = $this->faqService->list();

        return $this->render(self::VIEW_LIST, [
            'faqs' => $faqs,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(string $id): Response
    {
        try {
            $this->faqService->remove(Uuid::fromString($id));
            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.faq.message.deleted'));
        } catch (Exception $exception) {
            $this->addFlash(FlashMessageTypeEnum::ERROR->value, $exception->getMessage());
        }

        return $this->redirectToRoute('admin_faq_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function edit(string $id, Request $request, ValidatorInterface $validator): Response
    {
        try {
            $faq = $this->faqService->get(Uuid::fromString($id));
        } catch (Exception $exception) {
            $this->addFlash(FlashMessageTypeEnum::ERROR->value, $exception->getMessage());

            return $this->redirectToRoute('admin_faq_list');
        }

        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_EDIT, [
                'faq' => $faq,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);

        try {
            $this->faqService->update(Uuid::fromString($id), [
                'question' => $request->get('question'),
                'answer' => $request->get('answer'),
            ]);

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.faq.message.updated'));

            return $this->redirectToRoute('admin_faq_list');
        } catch (ValidatorException $exception) {
            return $this->render(self::VIEW_EDIT, [
                'faq' => $faq,
                'errors' => $exception->getConstraintViolationList(),
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }
    }
}

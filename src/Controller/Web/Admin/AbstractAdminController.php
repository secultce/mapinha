<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\AbstractWebController;
use App\Enum\FlashMessageTypeEnum;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAdminController extends AbstractWebController
{
    public const int ACCESS_DENIED_RESPONSE_CODE = Response::HTTP_NOT_FOUND;

    protected function render(string $view, array $parameters = [], ?Response $response = null, string $parentPath = '_admin/'): Response
    {
        return parent::render("{$parentPath}{$view}", $parameters, $response);
    }

    public function addFlashSuccess(mixed $message): void
    {
        $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $message);
    }

    public function addFlashError(mixed $message): void
    {
        $this->addFlash(FlashMessageTypeEnum::ERROR->value, $message);
    }
}

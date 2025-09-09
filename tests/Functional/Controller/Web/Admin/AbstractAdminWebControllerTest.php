<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\AbstractAdminController;
use App\Enum\FlashMessageTypeEnum;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AbstractAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private AbstractAdminController $controller;

    public function testRender(): void
    {
        $container = self::getContainer();

        $this->controller = new class extends AbstractAdminController {
            public function render(string $view, array $parameters = [], ?Response $response = null): Response
            {
                return parent::render($view, $parameters, $response);
            }
        };

        $this->controller->setContainer($container);

        $faqs = $this->controller->setContainer($container)->get('App\Service\FaqService')->list();

        $response = $this->controller->render('faq/list.html.twig', [
            'faqs' => $faqs,
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddFlashSuccess(): void
    {
        $this->controller = $this->getMockBuilder(AbstractAdminController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addFlash'])
            ->getMock();

        $message = 'Success message';

        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with(FlashMessageTypeEnum::SUCCESS->value, $message);

        $this->controller->addFlashSuccess($message);
    }

    public function testAddFlashError(): void
    {
        $this->controller = $this->getMockBuilder(AbstractAdminController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addFlash'])
            ->getMock();

        $message = 'Error message';

        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with(FlashMessageTypeEnum::ERROR->value, $message);

        $this->controller->addFlashError($message);
    }
}

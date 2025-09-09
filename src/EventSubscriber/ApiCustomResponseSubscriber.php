<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\AccountEvent\AccountNotActivatedException;
use App\Exception\ResourceNotFoundException;
use App\Exception\ValidatorException;
use App\Log\Log;
use App\Response\ErrorGeneralResponse;
use App\Response\ErrorNotFoundResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ApiCustomResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        public ParameterBagInterface $parameterBag,
        public Environment $twig,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'generateCustomError',
        ];
    }

    public function generateCustomError(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException || $exception instanceof ResourceNotFoundException) {
            $this->generateNotFoundError($event);

            return;
        }

        if ($exception instanceof ValidatorException) {
            $this->generateValidationError($event);

            return;
        }

        if ($exception instanceof AccountNotActivatedException) {
            $this->generateAccountNotActivatedError($event);

            return;
        }

        Log::critical('critical', ['message' => $exception->getMessage()]);

        $event->setResponse(
            new ErrorGeneralResponse(
                message: 'error_general',
                details: ['description' => $exception->getMessage()],
            )
        );
    }

    private function generateValidationError(ExceptionEvent $event): void
    {
        $fields = [];

        foreach ($event->getThrowable()->getConstraintViolationList() as $error) {
            $fields[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        $event->setResponse(
            new ErrorGeneralResponse(
                'not_valid',
                Response::HTTP_BAD_REQUEST,
                $fields
            )
        );
    }

    private function generateNotFoundError(ExceptionEvent $event): void
    {
        $details = [];
        $exception = $event->getThrowable();

        if (false === $this->isApiRequest($event->getRequest())) {
            $response = new Response(
                $this->twig->render('not-found/not-found.html.twig'),
                Response::HTTP_NOT_FOUND
            );
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof ResourceNotFoundException) {
            $details = ['description' => $exception->getMessage()];

            $event->setResponse(new ErrorNotFoundResponse('not_found', Response::HTTP_NOT_FOUND, $details));
        }

        $event->setResponse(
            new ErrorNotFoundResponse(
                'not_found',
                Response::HTTP_NOT_FOUND,
                $details
            )
        );
    }

    private function generateAccountNotActivatedError(ExceptionEvent $event): void
    {
        $details = [];
        $exception = $event->getThrowable();

        if (false === $this->isApiRequest($event->getRequest())) {
            $response = new Response(
                $this->twig->render('authentication/login.html.twig', ['error' => $exception]),
                Response::HTTP_OK
            );
            $event->setResponse($response);

            return;
        }

        $event->setResponse(
            new ErrorGeneralResponse(
                'account_not_activated',
                Response::HTTP_BAD_REQUEST,
                $details
            )
        );
    }

    private function isApiRequest(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api');
    }
}

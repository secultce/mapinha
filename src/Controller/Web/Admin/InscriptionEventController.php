<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\AbstractWebController;
use App\Entity\Agent;
use App\Entity\Event;
use App\Enum\ExportFormatEnum;
use App\Enum\InscriptionEventStatusEnum;
use App\Exception\InscriptionEvent\AlreadyInscriptionEventException;
use App\Service\InscriptionEventExportService;
use App\Service\Interface\InscriptionEventServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class InscriptionEventController extends AbstractWebController
{
    public function __construct(
        private readonly InscriptionEventServiceInterface $inscriptionEventService,
        private readonly InscriptionEventExportService $exportService
    ) {
    }

    public function download(Event $event, ExportFormatEnum $format): Response
    {
        return $this->exportService->export($event, $format);
    }

    public function inscription(Event $event): RedirectResponse
    {
        $user = $this->getUser();
        /* @var Agent $agent */
        $agent = $user->getAgents()->first();

        try {
            $this->inscriptionEventService->create($event->getId(), [
                'id' => Uuid::v4(),
                'agent' => $agent->getId(),
                'event' => $event,
                'status' => InscriptionEventStatusEnum::getName(1),
            ]);

            $this->addFlash('success', 'inscription_realized');
        } catch (AlreadyInscriptionEventException) {
            $this->addFlash('error', 'exception.already_inscription_event');
        }

        return $this->redirectToRoute('web_event_show', ['id' => $event->getId()]);
    }
}

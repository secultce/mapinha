<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Service\Interface\InscriptionEventServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class MyInscriptionEventController extends AbstractAdminController
{
    public function __construct(
        private readonly InscriptionEventServiceInterface $inscriptionEventService,
    ) {
    }

    public function myInscriptions(): Response
    {
        $inscriptions = $this->inscriptionEventService->listMyInscriptions();

        return $this->render('my-event-inscription/list.html.twig', [
            'inscriptions' => $inscriptions,
        ]);
    }
}

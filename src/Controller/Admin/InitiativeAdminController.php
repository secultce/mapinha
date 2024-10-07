<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\Interface\InitiativeServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use Symfony\Component\HttpFoundation\Response;

class InitiativeAdminController extends AbstractAdminController
{
    private InitiativeServiceInterface $initiativeService;

    public function __construct(InitiativeServiceInterface $initiativeService)
    {
        $this->initiativeService = $initiativeService;
    }

    public function list(): Response
    {
        $initiatives = $this->initiativeService->list();
        $totalInitiatives = count($initiatives);

        $dashboard = [
            'color' => '#6b3fa0',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalInitiatives, text: 'view.initiative.quantity.total'),
                new CardItem(icon: 'event_available', quantity: 20, text: 'view.initiative.quantity.finished'),
                new CardItem(icon: 'event_note', quantity: 10, text: 'view.initiative.quantity.opened'),
                new CardItem(icon: 'today', quantity: 30, text: 'view.initiative.quantity.last_days'),
            ],
        ];

        return $this->render('initiative/list.html.twig', [
            'initiatives' => $initiatives,
            'dashboard' => $dashboard,
        ]);
    }
}

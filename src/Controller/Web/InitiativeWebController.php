<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Service\Interface\InitiativeServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class InitiativeWebController extends AbstractWebController
{
    public function __construct(
        public readonly InitiativeServiceInterface $initiativeService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function list(Request $request): Response
    {
        $filters = $request->query->all();

        $filters = $this->getOrderParam($filters);

        $initiatives = $this->initiativeService->list(params: $filters['filters'], order: $filters['order']);
        $totalInitiatives = count($initiatives);

        $days = $request->get('days', 7);
        $recentInitiatives = $this->initiativeService->countRecentRecords($days);
        $finishedInitiatives = $this->initiativeService->countByStatus('finalizados');
        $openedInitiatives = $this->initiativeService->countByStatus('em-andamento');

        $dashboard = [
            'color' => 'var(--navlink-initiative)',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalInitiatives, text: 'view.initiative.quantity.total'),
                new CardItem(icon: 'event_available', quantity: $finishedInitiatives, text: 'view.initiative.quantity.finished'),
                new CardItem(icon: 'event_note', quantity: $openedInitiatives, text: 'view.initiative.quantity.opened'),
                new CardItem(icon: 'today', quantity: $recentInitiatives, text: $this->translator->trans('view.initiative.quantity.last_days', ['{days}' => $days])),
            ],
        ];

        return $this->render('initiative/list.html.twig', [
            'initiatives' => $initiatives,
            'dashboard' => $dashboard,
            'totalInitiatives' => $totalInitiatives,
        ]);
    }

    public function show(Uuid $id): Response
    {
        $initiative = $this->initiativeService->get($id);

        return $this->render('initiative/details.html.twig', ['initiative' => $initiative]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Service\Interface\OpportunityServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class OpportunityWebController extends AbstractWebController
{
    public function __construct(
        public readonly OpportunityServiceInterface $service,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function list(Request $request): Response
    {
        $filters = $request->query->all();

        $filters = $this->getOrderParam($filters);

        $opportunities = $this->service->list(params: $filters['filters'], order: $filters['order']);
        $totalOpportunities = count($opportunities);

        $days = $request->get('days', 7);
        $recentOpportunities = $this->service->countRecentOpportunities($days);
        $openedOpportunities = $this->service->countOpenedOpportunities();
        $finishedOpportunities = $this->service->countFinishedOpportunities();

        $dashboard = [
            'color' => '#009874',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalOpportunities, text: 'view.opportunity.quantity.total'),
                new CardItem(icon: 'event_note', quantity: $openedOpportunities, text: 'view.opportunity.quantity.opened'),
                new CardItem(icon: 'event_available', quantity: $finishedOpportunities, text: 'view.opportunity.quantity.finished'),
                new CardItem(icon: 'today', quantity: $recentOpportunities, text: $this->translator->trans('view.opportunity.quantity.last_days', ['{days}' => $days])),
            ],
        ];

        return $this->render('opportunity/list.html.twig', [
            'dashboard' => $dashboard,
            'opportunities' => $opportunities,
            'totalOpportunities' => $totalOpportunities,
        ]);
    }

    public function details(Uuid $id): Response
    {
        $opportunity = $this->service->get($id);

        return $this->render('opportunity/details.html.twig', [
            'opportunity' => $opportunity,
        ]);
    }
}

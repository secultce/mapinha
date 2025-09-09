<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\EventServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class AgentWebController extends AbstractWebController
{
    public function __construct(
        public readonly AgentServiceInterface $service,
        private readonly TranslatorInterface $translator,
        private readonly EventServiceInterface $eventService,
    ) {
    }

    public function list(Request $request): Response
    {
        $filters = $request->query->all();

        $filters = $this->getOrderParam($filters);

        $agents = $this->service->list(params: $filters['filters'], order: $filters['order']);
        $totalAgents = count($agents);

        $days = $request->get('days', 7);
        $recentAgents = $this->service->countRecentRecords($days);

        $dashboard = [
            'color' => '#D0A020',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalAgents, text: 'view.agent.quantity.total'),
                new CardItem(icon: 'person', quantity: 30, text: 'view.agent.quantity.culture'),
                new CardItem(icon: 'block', quantity: 20, text: 'view.agent.quantity.inactive'),
                new CardItem(icon: 'today', quantity: $recentAgents, text: $this->translator->trans('view.agent.quantity.last_days', ['{days}' => $days])),
            ],
        ];

        return $this->render('agent/list.html.twig', [
            'dashboard' => $dashboard,
            'agents' => $agents,
            'totalAgents' => $totalAgents,
        ]);
    }

    public function getOne(Uuid $id): Response
    {
        $agent = $this->service->get($id);
        $events = $this->eventService->findByAgent($agent->getId()->toRfc4122());

        return $this->render('agent/one.html.twig', [
            'agent' => $agent,
            'events' => $events,
        ]);
    }
}

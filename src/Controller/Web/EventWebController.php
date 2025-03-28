<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Service\Interface\EventServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use DateInterval;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventWebController extends AbstractWebController
{
    public function __construct(
        public readonly EventServiceInterface $service,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function list(Request $request): Response
    {
        $filters = $request->query->all();

        $filters = $this->hidratePeriodParam($filters);
        $filters = $this->getOrderParam($filters);

        $events = $this->service->list(params: $filters['filters'], order: $filters['order']);

        $totalEvents = count($events);

        $days = $request->get('days', 7);
        $recentEvents = $this->service->countRecentRecords($days);

        $dashboard = [
            'color' => '#f5b932',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalEvents, text: 'view.event.quantity.total'),
                new CardItem(icon: 'event_note', quantity: 10, text: 'view.event.quantity.opened'),
                new CardItem(icon: 'event_available', quantity: 20, text: 'view.event.quantity.finished'),
                new CardItem(
                    icon: 'today',
                    quantity: $recentEvents,
                    text: $this->translator->trans('view.event.quantity.last_days', ['{days}' => $days]),
                ),
            ],
        ];

        return $this->render('event/list.html.twig', [
            'dashboard' => $dashboard,
            'events' => $events,
            'totalEvents' => $totalEvents,
        ]);
    }

    public function show(Uuid $id): Response
    {
        $event = $this->service->get($id);

        return $this->render('event/show.html.twig', ['event' => $event]);
    }

    private function hidratePeriodParam(array $filters): array
    {
        if (empty($filters['period'])) {
            unset($filters['period']);

            return $filters;
        }

        $dates = explode(',', (string) $filters['period']);
        unset($filters['period']);
        if (2 !== count($dates)) {
            return $filters;
        }

        [$start, $end] = array_map(fn ($date) => DateTimeImmutable::createFromFormat('Y-m-d', $date) ?: null, $dates);
        $end = $end->add(new DateInterval('PT23H59M59S'));

        return ($start && $end) ? array_merge($filters, ['period' => ['start' => $start, 'end' => $end]]) : $filters;
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\OrganizationServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationWebController extends AbstractWebController
{
    public function __construct(
        public readonly OrganizationServiceInterface $service,
        private readonly AgentServiceInterface $agentService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function list(Request $request): Response
    {
        $filters = $request->query->all();
        $filters = $this->getOrderParam($filters);

        $organizations = $this->service->list(params: $filters['filters'], order: $filters['order']);
        $totalOrganizations = count($organizations);

        $days = $request->get('days', 7);
        $recentOrganizations = $this->service->countRecentRecords($days);

        $dashboard = [
            'color' => '#6A5ACD',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalOrganizations, text: 'view.organization.quantity.total'),
                new CardItem(icon: 'person', quantity: 30, text: 'view.organization.quantity.culture'),
                new CardItem(icon: 'block', quantity: 20, text: 'view.organization.quantity.inactive'),
                new CardItem(icon: 'today', quantity: $recentOrganizations, text: $this->translator->trans('view.organization.quantity.last_days', ['{days}' => $days])),
            ],
        ];

        return $this->render('organization/list.html.twig', [
            'dashboard' => $dashboard,
            'organizations' => $organizations,
            'totalOrganizations' => $totalOrganizations,
        ]);
    }

    public function getOne(Uuid $id): Response
    {
        $organization = $this->service->get($id);
        $owner = $this->agentService->get($organization->getCreatedBy()->getId());

        return $this->render('organization/details.html.twig', [
            'organization' => $organization,
            'owner' => $owner,
        ]);
    }
}

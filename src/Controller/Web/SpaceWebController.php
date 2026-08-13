<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\Space;
use App\Service\Interface\ActivityAreaServiceInterface;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\ArchitecturalAccessibilityServiceInterface;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\EventServiceInterface;
use App\Service\Interface\SpaceServiceInterface;
use App\Service\Interface\SpaceTypeServiceInterface;
use App\Service\Interface\StateServiceInterface;
use App\Service\Interface\TagServiceInterface;
use App\ValueObject\DashboardCardItemValueObject as CardItem;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class SpaceWebController extends AbstractWebController
{
    public function __construct(
        private readonly SpaceServiceInterface $service,
        private readonly AgentServiceInterface $agentService,
        private readonly TranslatorInterface $translator,
        private readonly EventServiceInterface $eventService,
        private readonly SpaceTypeServiceInterface $spaceTypeService,
        private readonly ArchitecturalAccessibilityServiceInterface $architecturalAccessibilityService,
        private readonly ActivityAreaServiceInterface $activityAreaService,
        private readonly TagServiceInterface $tagService,
        private readonly StateServiceInterface $stateService,
        private readonly CityServiceInterface $cityService,
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function list(Request $request): Response
    {
        $requestFilters = $request->query->all();

        $parsedFilters = $this->getOrderParam($requestFilters);

        $parsedFilters['filters']['isDraft'] = 0;
        $spaces = $this->service->list(params: $parsedFilters['filters'], order: $parsedFilters['order']);
        $totalSpaces = count($spaces);

        $days = $request->get('days', 7);
        $recentSpaces = $this->service->countRecentRecords($days);

        $totalSpacesAccessible = array_filter($spaces, fn (Space $item) => $item->isAccessible());

        $dashboard = [
            'color' => '#088140',
            'items' => [
                new CardItem(icon: 'description', quantity: $totalSpaces, text: 'view.space.quantity.total'),
                new CardItem(icon: 'accessible', quantity: count($totalSpacesAccessible), text: 'view.space.quantity.accessible'),
                new CardItem(icon: 'today', quantity: $recentSpaces, text: $this->translator->trans('view.space.quantity.last_days', ['{days}' => $days])),
            ],
        ];

        $spaceTypes = $this->spaceTypeService->list();
        $architecturalAccessibility = $this->architecturalAccessibilityService->list();
        $tags = $this->tagService->list();
        $activityAreas = $this->activityAreaService->list();
        $states = $this->stateService->findBy();
        $cities = $this->cityService->findBy();

        $user = $this->getUser();
        $token = $user ? $this->jwtManager->create($user) : null;

        return $this->render('space/list.html.twig', [
            'spaces' => $spaces,
            'dashboard' => $dashboard,
            'totalSpaces' => $totalSpaces,
            'spaceTypes' => $spaceTypes,
            'architecturalAccessibility' => $architecturalAccessibility,
            'tags' => $tags,
            'activityAreas' => $activityAreas,
            'states' => $states,
            'cities' => $cities,
            'token' => $token,
        ]);
    }

    public function getOne(Uuid $id): Response
    {
        $space = $this->service->get($id);
        $owner = $this->agentService->get($space->getCreatedBy()->getId());
        $events = $this->eventService->findBy(['space' => $space]);

        return $this->render('space/details.html.twig', [
            'space' => $space,
            'owner' => $owner,
            'events' => $events,
        ]);
    }
}

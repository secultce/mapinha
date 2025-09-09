<?php

declare(strict_types=1);

namespace App\EventListener\Audit;

use App\Document\AgentTimeline;
use App\Document\EventTimeline;
use App\Document\InitiativeTimeline;
use App\Document\InscriptionEventTimeline;
use App\Document\InscriptionOpportunityTimeline;
use App\Document\OpportunityTimeline;
use App\Document\OrganizationTimeline;
use App\Document\PhaseTimeline;
use App\Document\SpaceTimeline;
use App\Document\UserTimeline;
use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\InscriptionEvent;
use App\Entity\InscriptionOpportunity;
use App\Entity\Opportunity;
use App\Entity\Organization;
use App\Entity\Phase;
use App\Entity\Space;
use App\Entity\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractAuditListener
{
    public const string UNKNOWN = 'unknown';

    public function __construct(
        protected DocumentManager $documentManager,
        protected RequestStack $requestStack,
        protected Security $security,
    ) {
    }

    protected function getDevice(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return self::UNKNOWN;
        }

        return $this->getBrowser($request);
    }

    protected function getPlatform(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return self::UNKNOWN;
        }

        return $this->getOperationalSystem($request);
    }

    protected function resolveNameDocument(string $class): ?string
    {
        return match ($class) {
            Agent::class => AgentTimeline::class,
            Event::class => EventTimeline::class,
            Initiative::class => InitiativeTimeline::class,
            InscriptionEvent::class => InscriptionEventTimeline::class,
            InscriptionOpportunity::class => InscriptionOpportunityTimeline::class,
            Opportunity::class => OpportunityTimeline::class,
            Organization::class => OrganizationTimeline::class,
            Phase::class => PhaseTimeline::class,
            Space::class => SpaceTimeline::class,
            User::class => UserTimeline::class,
            default => null,
        };
    }

    private function getBrowser(Request $request): string
    {
        $user_agent = $request->server->get('HTTP_USER_AGENT');

        return match (1) {
            preg_match('/msie/i', $user_agent) => 'Internet explorer',
            preg_match('/firefox/i', $user_agent) => 'Firefox',
            preg_match('/opr/i', $user_agent) => 'Opera',
            preg_match('/edg/i', $user_agent) => 'Edge',
            preg_match('/chrome/i', $user_agent) => 'Chrome',
            preg_match('/safari/i', $user_agent) => 'Safari',
            preg_match('/mobile/i', $user_agent) => 'Mobile browser',
            default => self::UNKNOWN,
        };
    }

    private function getOperationalSystem(Request $request): string
    {
        $user_agent = $request->server->get('HTTP_USER_AGENT');

        return match (1) {
            preg_match('/android/i', $user_agent) => 'Android',
            preg_match('/linux/i', $user_agent) => 'Linux',
            preg_match('/windows|win32/i', $user_agent) => 'Windows',
            preg_match('/macintosh|mac os x/i', $user_agent) => 'MacOS',
            preg_match('/iphone|ipad/i', $user_agent) => 'iOS',
            default => self::UNKNOWN,
        };
    }
}

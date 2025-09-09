<?php

declare(strict_types=1);

namespace App\EventListener\Audit;

use App\Document\InviteTimeline;
use App\Entity\Invite;
use App\Event\Invite\AcceptInviteEvent;
use App\Event\Invite\RemoveInviteEvent;
use App\Event\Invite\SendInviteEvent;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\Event;

#[AsEventListener(event: AcceptInviteEvent::class, priority: 1)]
#[AsEventListener(event: SendInviteEvent::class, priority: 1)]
class AuditInviteListener extends AbstractAuditListener
{
    public function __construct(
        protected DocumentManager $documentManager,
        protected RequestStack $requestStack,
        protected Security $security,
    ) {
        parent::__construct($documentManager, $requestStack, $security);
    }

    public function __invoke(Event $event): void
    {
        if ($event instanceof AcceptInviteEvent) {
            $this->acceptInviteEvent($event);
        }

        if ($event instanceof SendInviteEvent) {
            $this->sendInviteEvent($event);
        }

        if ($event instanceof RemoveInviteEvent) {
            $this->removeInviteEvent($event);
        }
    }

    public function acceptInviteEvent(AcceptInviteEvent $event): void
    {
        $data = [];

        $data['user'] = $event->user->getEmail();

        $this->createInvite($event, $event->invite, AcceptInviteEvent::TITLE, $data);
    }

    private function sendInviteEvent(SendInviteEvent $event): void
    {
        $invite = $event->invite;

        $data['user'] = $invite->getGuest()?->getUser()->getEmail() ?? $event->email;

        $this->createInvite($event, $invite, SendInviteEvent::TITLE, $data);
    }

    private function removeInviteEvent(RemoveInviteEvent $event): void
    {
        $this->createInvite($event, $event->invite, RemoveInviteEvent::TITLE);
    }

    private function createInvite(Event $event, Invite $invite, string $title, array $data = []): void
    {
        $document = new InviteTimeline();

        if (null !== $event->user) {
            $document->setUserId($event->user->getId()->toRfc4122());
        }

        $document->setOrganizationId($invite->getHost()->getId()->toRfc4122());
        $document->setTitle($title);
        $document->setResourceId($invite->getId()->toRfc4122());
        $document->setPriority(0);
        $document->setDatetime(new DateTime());
        $document->setDevice($this->getDevice());
        $document->setPlatform($this->getPlatform());
        $document->setData($data);

        $this->documentManager->persist($document);
        $this->documentManager->flush();
    }
}

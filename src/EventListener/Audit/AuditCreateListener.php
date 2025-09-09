<?php

declare(strict_types=1);

namespace App\EventListener\Audit;

use App\DocumentService\AbstractTimelineDocumentService;
use App\Entity\AccountEvent;
use App\Entity\Agent;
use App\Entity\Organization;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsDoctrineListener(event: Events::prePersist, priority: 4096)]
class AuditCreateListener extends AbstractAuditListener
{
    public function __construct(
        protected DocumentManager $documentManager,
        protected RequestStack $requestStack,
        protected Security $security
    ) {
        parent::__construct($documentManager, $requestStack, $security);
    }

    public function __invoke(PrePersistEventArgs $args): void
    {
        $object = $args->getObject();
        $nameEntity = ($object)::class;
        $nameDocument = $this->resolveNameDocument($nameEntity);

        if (null === $nameDocument) {
            return;
        }

        $document = new $nameDocument();
        $userId = $this->security->getUser()?->getId()->toRfc4122() ?? null;

        $isMandatory = $this->isMandatoryAuthenticated($nameEntity);

        if (true === $isMandatory || null !== $userId) {
            $document->setUserId($userId);
        }

        $document->setTitle(AbstractTimelineDocumentService::CREATED);
        $document->setResourceId($object->getId()->toRfc4122());
        $document->setPriority(0);
        $document->setDatetime(new DateTime());
        $document->setDevice($this->getDevice());
        $document->setPlatform($this->getPlatform());
        $document->setFrom([]);
        $document->setTo($object->toArray());

        $this->documentManager->persist($document);
        $this->documentManager->flush();
    }

    private function isMandatoryAuthenticated(string $class): bool
    {
        return match ($class) {
            User::class,
            Organization::class,
            AccountEvent::class,
            Agent::class => false,
            default => true,
        };
    }
}

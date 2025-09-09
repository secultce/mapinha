<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\InscriptionOpportunity;
use App\Entity\InscriptionPhase;
use App\Entity\Phase;
use App\Repository\Interface\InscriptionOpportunityRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class InscriptionOpportunityRepository extends AbstractRepository implements InscriptionOpportunityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscriptionOpportunity::class);
    }

    public function create(InscriptionOpportunity $inscriptionOpportunity, ?InscriptionPhase $inscriptionPhase = null): InscriptionOpportunity
    {
        if (null !== $inscriptionPhase) {
            $this->getEntityManager()->persist($inscriptionPhase);
        }

        return $this->save($inscriptionOpportunity);
    }

    public function save(InscriptionOpportunity $inscriptionOpportunity): InscriptionOpportunity
    {
        $this->getEntityManager()->persist($inscriptionOpportunity);
        $this->getEntityManager()->flush();

        return $inscriptionOpportunity;
    }

    public function findInscriptionsByOpportunity($opportunityId, array $agents, int $limit)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('io')
            ->from(InscriptionOpportunity::class, 'io')
            ->join('io.opportunity', 'o')
            ->where('io.opportunity = :opportunityId')
            ->andWhere('o.createdBy IN (:agents)')
            ->setParameter('opportunityId', $opportunityId)
            ->setParameter('agents', $agents)
            ->setMaxResults($limit)
            ->orderBy('io.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneInscriptionOpportunity(string $inscriptionId, string $opportunityId, array $agents)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('io')
            ->from(InscriptionOpportunity::class, 'io')
            ->join('io.opportunity', 'o')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        'o.createdBy IN (:agents)',
                        'io.agent NOT IN (:agents)'
                    ),
                    $qb->expr()->andX(
                        'io.agent IN (:agents)',
                        'o.createdBy NOT IN (:agents)'
                    )
                )
            )
            ->andWhere('io.id = :inscriptionId')
            ->andWhere('o.id = :opportunityId')
            ->setParameter('inscriptionId', $inscriptionId)
            ->setParameter('opportunityId', $opportunityId)
            ->setParameter('agents', $agents)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findRecentByUser(Uuid $userId, int $limit = 4): array
    {
        return $this->createQueryBuilder('io')
            ->join('io.agent', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $userId)
            ->orderBy('io.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findUserInscriptionsWithDetails(Uuid $agentId): iterable
    {
        $qb = $this->createQueryBuilder('i');

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('MAX(p2.startDate)')
            ->from(Phase::class, 'p2')
            ->where('p2.opportunity = o.id');

        return $qb->select('i', 'o', 'p')
            ->join('i.opportunity', 'o')
            ->join(Phase::class, 'p', 'WITH', 'p.opportunity = o.id AND p.startDate = ('.$subQuery->getDQL().')')
            ->where('i.agent = :agentId')
            ->setParameter('agentId', $agentId)
            ->getQuery()
            ->getResult();
    }

    public function findInscriptionWithDetails(Uuid $identifier, array $userAgents): ?array
    {
        $sql = <<<SQL
                select
                    inscription.id,
                    json_build_object(
                        'id', agent.id,
                        'name', agent.name,
                        'image', agent.image
                    ) as "agent",
                    json_build_object(
                        'id', opportunity.id,
                        'name', opportunity.name,
                        'image', opportunity.image
                    ) as "opportunity",
                    (
                        select
                            json_build_object(
                                'id', phase.id,
                                'name', phase.name,
                                'description', phase.description,
                                'startDate', phase.start_date,
                                'endDate', phase.end_date
                            )
                        from phase
                        where phase.opportunity_id = inscription.opportunity_id
                          and phase.deleted_at is null
                        order by phase.start_date desc
                        limit 1
                    ) as "lastPhase"
                from inscription_opportunity as inscription
                inner join opportunity on opportunity.id = inscription.opportunity_id
                inner join agent on agent.id = inscription.agent_id
                where inscription.id = :identifier
                  and inscription.deleted_at is null
                  and opportunity.created_by_id in (:agents)
            SQL;

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('agent', 'agent', Types::JSON);
        $rsm->addScalarResult('opportunity', 'opportunity', Types::JSON);
        $rsm->addScalarResult('lastPhase', 'lastPhase', Types::JSON);

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'identifier' => $identifier,
            'agents' => $userAgents,
        ], ['agents' => ArrayParameterType::STRING]);

        return $query->getOneOrNullResult();
    }

    public function findInscriptionWithDetailsByOrganizations(Uuid $identifier): ?array
    {
        $sql = <<<SQL
                select
                    inscription.id,
                    json_build_object(
                        'id', organization.id,
                        'name', organization.name,
                        'image', organization.image
                    ) as "organization",
                    json_build_object(
                        'id', opportunity.id,
                        'name', opportunity.name,
                        'image', opportunity.image
                    ) as "opportunity",
                    (
                        select
                            json_build_object(
                                'id', phase.id,
                                'name', phase.name,
                                'description', phase.description,
                                'startDate', phase.start_date,
                                'endDate', phase.end_date
                            )
                        from phase
                        where phase.opportunity_id = inscription.opportunity_id
                          and phase.deleted_at is null
                        order by phase.start_date desc
                        limit 1
                    ) as "lastPhase"
                from inscription_opportunity as inscription
                inner join opportunity on opportunity.id = inscription.opportunity_id
                inner join organization on organization.id = inscription.organization_id
                where inscription.id = :identifier
                  and inscription.deleted_at is null
            SQL;

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('organization', 'organization', Types::JSON);
        $rsm->addScalarResult('opportunity', 'opportunity', Types::JSON);
        $rsm->addScalarResult('lastPhase', 'lastPhase', Types::JSON);

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'identifier' => $identifier,
        ], ['agents' => ArrayParameterType::STRING]);

        return $query->getOneOrNullResult();
    }
}

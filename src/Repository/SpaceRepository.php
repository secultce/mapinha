<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Space;
use App\Enum\EntityEnum;
use App\Repository\Interface\SpaceRepositoryInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class SpaceRepository extends AbstractRepository implements SpaceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Space::class);
    }

    public function save(Space $space): Space
    {
        $this->getEntityManager()->persist($space);
        $this->getEntityManager()->flush();

        return $space;
    }

    public function findByNameAndEntityAssociation(?string $name, EntityEnum $entityAssociation, int $limit): array
    {
        $sql = <<<EOT
                select s.*
                from space s
                left join entity_association ea ON s.id = ea.space_id
                where ea.with_$entityAssociation->name is true
                  and (cast(:name as varchar) is null or s.name ilike '%' || :name || '%')
                limit :limit
            EOT;

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Space::class, 's');
        $rsm->addFieldResult('s', 'id', 'id');
        $rsm->addFieldResult('s', 'name', 'name');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $query->setParameter('name', $name);
        $query->setParameter('limit', $limit);

        return $query->getResult();
    }

    public function findByFilters(array $filters, array $orderBy, int $limit): array
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.createdAt', $orderBy['createdAt'])
            ->setMaxResults($limit);

        $this->applyFilters($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    private function applyFilters(QueryBuilder $qb, array $filters): void
    {
        $filterMappings = $this->getFilterMappings();

        foreach ($filters as $key => $value) {
            $map = $filterMappings[$key];

            if (true === isset($map['join'])) {
                $joins = is_array($map['join'][0]) ? $map['join'] : [$map['join']];
                foreach ($joins as $join) {
                    $qb->join($join[0], $join[1]);
                }
            }

            $map['condition']($qb, $value);
        }
    }

    private function getFilterMappings(): array
    {
        return [
            'name' => [
                'condition' => fn ($qb, $value) => $qb->andWhere('s.name LIKE :name')->setParameter('name', "%$value%"),
            ],
            'isDraft' => [
                'condition' => fn ($qb, $value) => $qb->andWhere('s.isDraft = :isDraft')->setParameter('isDraft', $value, ParameterType::BOOLEAN),
            ],
            'spaceType' => [
                'join' => ['s.spaceType', 'st'],
                'condition' => fn ($qb, $value) => $qb->andWhere('st.id = :spaceTypeId')->setParameter('spaceTypeId', $value),
            ],
            'accessibilities' => [
                'join' => ['s.accessibilities', 'a'],
                'condition' => fn ($qb, $value) => $qb->andWhere('a.id = :accessibilityId')->setParameter('accessibilityId', $value),
            ],
            'activityAreas' => [
                'join' => ['s.activityAreas', 'aa'],
                'condition' => fn ($qb, $value) => $qb->andWhere('aa.id = :activityAreaId')->setParameter('activityAreaId', $value),
            ],
            'tags' => [
                'join' => ['s.tags', 't'],
                'condition' => fn ($qb, $value) => $qb->andWhere('t.id = :tagId')->setParameter('tagId', $value),
            ],
            'state' => [
                'join' => [
                    ['s.address', 'a'],
                    ['a.city', 'c'],
                    ['c.state', 'ast'],
                ],
                'condition' => fn ($qb, $value) => $qb->andWhere('ast.id = :stateId')->setParameter('stateId', $value),
            ],
            'address' => [
                'join' => ['s.address', 'ac'],
                'condition' => fn ($qb, $value) => $qb->andWhere('ac.city = :city')->setParameter('city', $value),
            ],
        ];
    }
}

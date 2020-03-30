<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AbstractRepository
 * @package App\Repository
 */
abstract class AbstractRepository extends EntityRepository
{
    private const EDGE_FIRST = 0;
    private const EDGE_LAST = 1;

    /**
     * @param string $order
     * @param int|null $limit
     * @param int|null $offset
     * @param array $criteria
     *
     * @return EntityInterface[]
     */
    public function getList(string $order = 'DESC',
                            int $limit = null,
                            int $offset = null,
                            array $criteria = []
    ): array {
        $qb = $this->createQueryBuilder('entity');

        $qb->select('entity');

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        $qb->orderBy('entity.createdAt', $order);
        $qb->addOrderBy('entity.id', $order);

        $this->applyCriteria($qb, $criteria);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function getCount(array $criteria = []): int
    {
        $qb = $this->createQueryBuilder('entity');
        $qb->select($qb->expr()->count('entity'));

        $this->applyCriteria($qb, $criteria);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Applies an array of filters to given QueryBuilder.
     *
     * - Handles simple filters. Example: ['user' => 324]
     * - Handles inner joins filters. Example: ['user.group' => 22]
     *
     * /!\ Note that inner joins only support one level for now.
     * Something like ['user.group.company' => 54] won't work.
     *
     * @param QueryBuilder $qb
     * @param array $criteria
     *
     * @return QueryBuilder
     */
    protected function applyCriteria(QueryBuilder $qb, array $criteria): QueryBuilder
    {
        if (!empty($criteria)) {
            $i = 0;
            foreach ($criteria as $field => $value) {
                ++$i;
                $alias = 'c_' . $i;
                if (strpos($field, '.') === false) {
                    $qb->andWhere("entity.$field = :$alias");
                } else {
                    $f = explode('.', $field);
                    $qb->innerJoin("entity.$f[0]", "j_$i");
                    $qb->andWhere("$f[0].$f[1] = :$alias");
                }
                $qb->setParameter($alias, $value);
            }
        }

        return $qb;
    }

    /**
     * @return EntityInterface|null
     */
    public function findFirst(): ?EntityInterface
    {
        return $this->findFirstBy([]);
    }

    /**
     * @return EntityInterface|null
     */
    public function findLast(): ?EntityInterface
    {
        return $this->findLastBy([]);
    }

    /**
     * @param array $criteria
     *
     * @return EntityInterface|null
     */
    public function findFirstBy(array $criteria = []): ?EntityInterface
    {
        return $this->findEdgeElementBy(self::EDGE_FIRST, $criteria);
    }

    /**
     * @param array $criteria
     *
     * @return EntityInterface|null
     */
    public function findLastBy(array $criteria = []): ?EntityInterface
    {
        return $this->findEdgeElementBy(self::EDGE_LAST, $criteria);
    }

    /**
     * @param int $edge
     * @param string[] $criteria
     *
     * @return EntityInterface|null
     */
    private function findEdgeElementBy(int $edge, array $criteria = []): ?EntityInterface
    {
        $qb = $this->createQueryBuilder('entity');

        $qb->select('entity');

        $this->applyCriteria($qb, $criteria);

        $order = (function () use ($edge) {
            switch ($edge) {
                case self::EDGE_FIRST:
                    return 'ASC';
                case self::EDGE_LAST:
                    return 'DESC';
                default: throw new \RuntimeException("Unknown edge $edge");
            }
        })();
        $qb->orderBy('entity.createdAt', $order);
        $qb->addOrderBy('entity.id', $order);

        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}

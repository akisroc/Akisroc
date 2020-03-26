<?php

declare(strict_types=1);

namespace App\Repository\Traits;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trait PaginableEntityRepository
 * @package App\Repository\Traits
 */
trait PaginableEntityRepository
{
    /**
     * @param  int       $page
     * @param  int|null  $maxResults
     * @param  array     $criteria
     * @param  array     $orderBy
     * @param  bool      $getPaginator
     *
     * @return array|Paginator
     */
    public function getPaginatedList(int $page = 1,
                                     int $maxResults = null,
                                     array $criteria = [],
                                     array $orderBy = [],
                                     bool $getPaginator = false
    ) {
        if ($page < 1) {
            throw new \UnexpectedValueException(
                'Requested page must be positive integer.'
            );
        }

        if ($maxResults && $maxResults < 1) {
            throw new \UnexpectedValueException(
                'Max results must be positive integer.'
            );
        }

        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('entity');
        $qb->select('entity');

        // Calculating offset
        $firstResult = ($page - 1) * ($maxResults ?: 1);
        $qb->setFirstResult($firstResult);

        // Limiting query
        if ($maxResults) {
            $qb->setMaxResults($maxResults);
        }

        // Filtering query
        if (!empty($criteria)) {
            $i = 0;
            foreach ($criteria as $field => $value) {
                $alias = 'c_' . $i++;
                $qb->andWhere("entity.$field = :$alias");
                $qb->setParameter($alias, $value);
            }
        }

        // Ordering query
        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $order) {
                if (!in_array(strtolower($order), ['desc', 'asc'], true)) {
                    throw new \UnexpectedValueException(
                        "Order must be ASC or DESC, \"$order\" given."
                    );
                }
                $qb->addOrderBy("entity.$field", $order);
            }
        }

        // Returning results if paginator is not needed
        if ($getPaginator === false) {
            return $qb->getQuery()->getResult();
        }

        // Returning paginator
        $paginator = new Paginator($qb);
        if (($paginator->count() <= $firstResult) && $page !== 1) {
            throw new NotFoundHttpException('Requested page does not exist.');
        }

        return $paginator;
    }
}

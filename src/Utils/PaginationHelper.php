<?php

declare(strict_types=1);

namespace App\Utils;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class PaginationHelper
 * @package App\Utils
 */
final class PaginationHelper
{
    /**
     * /!\ Mutates passed QueryBuilder object /!\
     *
     * Adds offset and limit to QueryBuilder, according to given
     * page and max number of results.
     *
     * @param QueryBuilder $qb
     * @param int      $page       Must be greater than zero.
     * @param int|null $maxResults Must be greater than zero.
     *
     * @return QueryBuilder
     *
     * @throws \UnexpectedValueException If $page or $maxResults are not
     *                                   greater than zero.
     */
    public function paginateQb(QueryBuilder $qb, int $page, ?int $maxResults): QueryBuilder
    {
        if ($page < 1) {
            throw new \UnexpectedValueException('Requested page must be positive integer.');
        }

        if ($maxResults && $maxResults < 1) {
            throw new \UnexpectedValueException('Max results must be positive integer.');
        }

        $firstResult = ($page - 1) * ($maxResults ?: 1);
        $qb->setFirstResult($firstResult);

        if ($maxResults) {
            $qb->setMaxResults($maxResults);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return Paginator
     */
    public function getPaginatorFromQb(QueryBuilder $qb): Paginator
    {
        return new Paginator($qb);
    }

    /**
     * Calculates the total number of pages for the given Paginator and
     * max number of items per page.
     *
     * @param Paginator $paginator
     * @param $nbItemsPerPage
     *
     * @return int
     */
    public function calculateNumberOfPages(Paginator $paginator, $nbItemsPerPage): int
    {
        return (int) ceil($paginator->count() / $nbItemsPerPage);
    }
}

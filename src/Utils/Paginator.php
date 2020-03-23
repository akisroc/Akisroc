<?php

namespace App\Utils;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination as ORM;

/**
 * Class Paginator
 * @package App\Utils
 */
class Paginator
{
    /** @var int $page */
    protected $page;

    /** @var int $itemsPerPage */
    protected $itemsPerPage;

    /** @var int $count */
    protected $count;

    /**
     * Paginator constructor.
     * @param int $page
     * @param int $itemsPerPage
     */
    public function __construct(int $page, int $itemsPerPage)
    {
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param QueryBuilder|Query $query
     * @return ORM\Paginator
     */
    public function paginate($query): ORM\Paginator
    {
        if (!in_array(get_class($query), [QueryBuilder::class, Query::class], true)) {
            throw new \UnexpectedValueException('Query must be valid QueryBuilder or Query');
        }

        $firstResult = ($this->page - 1) * $this->itemsPerPage;
        $maxResult = $this->itemsPerPage;
        $query->setFirstResult($firstResult);
        $query->setMaxResults($maxResult);

        $ormPaginator = new ORM\Paginator($query, true);

        return $ormPaginator;
    }
}
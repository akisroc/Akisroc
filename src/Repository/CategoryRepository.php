<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package App\Repository
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findTopCategories(array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->findBy(['parent' => null], $orderBy, $limit, $offset);
    }

    /**
     * @param string $type
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByType(string $type, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return$this->findBy(['type' => $type], $orderBy, $limit, $offset);
    }
}

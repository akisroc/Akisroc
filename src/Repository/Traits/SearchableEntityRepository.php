<?php

declare(strict_types=1);

namespace App\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait SearchableEntityRepository
 * @package App\Repository\Traits
 */
trait SearchableEntityRepository
{
    /**
     * @param array $fields
     * @param string $term
     *
     * @return array
     */
    public function findByFulltextTerm(array $fields, string $term): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('entity');

        foreach ($fields as $field) {
            $qb->addSelect("entity.$field");
            $qb->orWhere($qb->expr()->like("entity.$field", ':term'));
        }
        $qb->setParameter('term', "%$term%");

        $qb->setMaxResults(100);

        return $qb->getQuery()->getResult();
    }
}

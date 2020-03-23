<?php

namespace App\Repository;

use App\Entity\Board;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TopicRepository
 * @package App\Repository
 */
class TopicRepository extends EntityRepository
{
    /**
     * @param Board $board
     * @param bool $returnBuilder
     * @return QueryBuilder|array
     */
    public function getBoardIndex(Board $board, bool $returnBuilder = true)
    {
        $qb = $this->createQueryBuilder('topic');

        $qb->where('topic.board = :board');
        $qb->setParameter('board', $board);

        $qb->orderBy('topic.id', 'DESC');

        return $returnBuilder === true ? $qb : $qb->getQuery()->getResult();
    }
}

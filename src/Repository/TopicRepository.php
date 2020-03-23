<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TopicRepository
 * @package App\Repository
 */
class TopicRepository extends ServiceEntityRepository
{
    /**
     * TopicRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Topic::class);
    }

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

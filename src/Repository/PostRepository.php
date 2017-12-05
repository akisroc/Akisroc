<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PostRepository
 * @package App\Repository
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * PostRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param Topic $topic
     * @return Post|null
     */
    public function findLastByTopic(Topic $topic): ?Post
    {
        return $this->findOneBy(['topic', $topic], ['id', 'DESC']);
    }
}

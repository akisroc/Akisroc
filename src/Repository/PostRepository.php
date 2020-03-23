<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository
 * @package App\Repository
 */
class PostRepository extends EntityRepository
{
    /**
     * @param Topic $topic
     * @return Post|null
     */
    public function findLastByTopic(Topic $topic): ?Post
    {
        return $this->findOneBy(['topic', $topic], ['id', 'DESC']);
    }
}

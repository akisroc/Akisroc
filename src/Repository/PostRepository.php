<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Topic;

/**
 * Class PostRepository
 * @package App\Repository
 */
class PostRepository extends AbstractRepository
{
    /**
     * @param Topic $topic
     * @return Post|null
     */
    public function findLastByTopic(Topic $topic): ?Post
    {
        return $this->findLastBy(['topic' => $topic]);
    }
}

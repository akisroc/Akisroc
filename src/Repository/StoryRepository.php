<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Story;
use App\Entity\Vote;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class StoryRepository
 * @package App\Repository
 */
class StoryRepository extends AbstractRepository
{
    /**
     * @param \DateInterval $interval
     * @return Story|null
     */
    public function getTrending(\DateInterval $interval): ?Story
    {
        return $this->findRandom();

        /** @todo */
//        $intervalInSeconds = (function () use ($interval): string {
//            $now = new \DateTimeImmutable();
//            $startDate = $now->sub($interval);
//            return (string) ($now->getTimestamp() - $startDate->getTimestamp());
//        })();
//
//        $sql = "
//            SELECT story.id, story.title, story.slug, vote.id
//            FROM story
//
//            INNER JOIN vote
//              ON vote.story_id = story.id
//              AND vote.created_at
//                BETWEEN (
//                  SELECT (MAX(vote.created_at) - INTERVAL {$intervalInSeconds} SECOND)
//                  FROM vote
//                )
//                AND (
//                  SELECT MAX(vote.created_at)
//                  FROM vote
//                )
//
//            GROUP BY story.id
//            ORDER BY COUNT(vote.id) DESC
//            LIMIT 1
//        ";
//
//        $rsm = new ResultSetMapping();
//
//        $rsm->addEntityResult(Story::class, 'story');
//        $rsm->addFieldResult('story', 'id', 'id');
//        $rsm->addFieldResult('story', 'title', 'title');
//        $rsm->addFieldResult('story', 'slug', 'slug');
//
//        $rsm->addJoinedEntityResult(Vote::class, 'vote', 'story', 'votes');
//        $rsm->addFieldResult('vote', 'id', 'id');
//
//        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
//
//        return $query->getOneOrNullResult();
    }
}

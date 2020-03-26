<?php

declare(strict_types=1);

namespace App\ViewDataGatherer;

use App\Entity\Board;
use App\Entity\Episode;
use App\Entity\Place;

/**
 * Class HomepageDataGatherer
 * @package App\ViewDataGatherer
 */
final class HomepageDataGatherer extends AbstractViewDataGatherer
{
    /**
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function gatherData(): array
    {
        return [
            'places' => $this->em->getRepository(Place::class)->findAll(),
            'last_episode' => $this->getLastEpisode(),
            'boards' => $this->em->getRepository(Board::class)->findAll()
        ];
    }

    /**
     * @return Episode|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getLastEpisode(): ?Episode
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('episode', 'story', 'protagonist', 'user');

        $qb->from(Episode::class, 'episode');

        $qb->innerJoin('episode.story', 'story');
        $qb->innerJoin('episode.protagonist', 'protagonist');
        $qb->innerJoin('protagonist.user', 'user');

        $qb->setMaxResults(1);

        $qb->orderBy('episode.createdAt', 'DESC');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
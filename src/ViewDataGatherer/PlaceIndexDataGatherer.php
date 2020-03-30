<?php

declare(strict_types=1);

namespace App\ViewDataGatherer;

use App\Entity\Place;
use App\Entity\Story;
use App\Utils\PaginationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PlaceIndexDataGatherer
 * @package App\ViewDataGatherer
 */
final class PlaceIndexDataGatherer extends AbstractViewDataGatherer
{
    /** @var PaginationHelper $paginationHelper */
    private PaginationHelper $paginationHelper;

    /**
     * PlaceIndexQueryHandler constructor.
     * @param EntityManagerInterface $em
     * @param PaginationHelper $paginationHelper
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginationHelper $paginationHelper
    ) {
        parent::__construct($em);

        $this->paginationHelper = $paginationHelper;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $slug
     * @param int $page
     * @param int|null $maxResults
     *
     * @return array<string, mixed>
     *
     * @throws NotFoundHttpException If no Place is found for given slug
     */
    public function gatherData(string $slug, int $page, ?int $maxResults): array
    {
        $place = $this->em
            ->getRepository(Place::class)
            ->findOneBy(['slug' => $slug]);

        if (!$place) {
            throw new NotFoundHttpException();
        }

        $storiesQb = $this->em->createQueryBuilder();

        $storiesQb->select('story', 'episodes', 'protagonist', 'user');

        $storiesQb->from(Story::class, 'story');

        $storiesQb->where('story.place = :place');
        $storiesQb->setParameter('place', $place);

        $storiesQb->innerJoin('story.episodes', 'episodes');
        $storiesQb->innerJoin('episodes.protagonist', 'protagonist');
        $storiesQb->innerJoin('protagonist.user', 'user');

        $storiesQb->orderBy('story.createdAt', 'DESC');

        $this->paginationHelper->paginateQb($storiesQb, $page, $maxResults);

        $storiesPaginator = $this->paginationHelper->getPaginatorFromQb(
            $storiesQb
        );

        return [
            'place' => $place,
            'stories' => $storiesPaginator,
            'pagination' => [
                'page' => $page,
                'nbPages' => $this->paginationHelper->calculateNumberOfPages(
                    $storiesPaginator, $maxResults
                )
            ]
        ];
    }
}

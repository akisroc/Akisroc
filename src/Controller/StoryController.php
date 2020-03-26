<?php

declare(strict_types=1);

namespace App\Controller;

use App\ViewDataGatherer\StoryIndexDataGatherer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StoryController
 * @package App\Controller
 */
class StoryController extends AbstractController
{
    /**
     * @Route(
     *     "/story/{slug}/{page}",
     *     name="story.index",
     *     requirements={"page"="[1-9]\d*"},
     *     defaults={"page"=1}
     * )
     *
     * @param StoryIndexDataGatherer $dataGatherer
     * @param string $slug
     * @param int $page
     *
     * @return Response
     */
    public function index(
        StoryIndexDataGatherer $dataGatherer,
        string $slug,
        int $page
    ): Response {

        return $this->render(
            'story/index.html.twig',
            $dataGatherer->gatherData($slug, $page, 10)
        );
    }
}

<?php

namespace App\Controller;

use App\ViewDataGatherer\PlaceIndexDataGatherer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PlaceController
 * @package App\Controller
 */
class PlaceController extends AbstractController
{
    /**
     * @Route(
     *     "/place/{slug}/{page}",
     *     name="place.index",
     *     requirements={"page"="[1-9]\d*"},
     *     defaults={"page"=1}
     * )
     *
     * @param PlaceIndexDataGatherer $dataGatherer
     * @param string $slug
     * @param int $page
     *
     * @return Response
     */
    public function index(
        PlaceIndexDataGatherer $dataGatherer,
        string $slug,
        int $page
    ): Response {

        return $this->render(
            'place/index.html.twig',
            $dataGatherer->gatherData($slug, $page, 20)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\ViewDataGatherer\HomepageDataGatherer;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageController
 * @package App\Controller
 */
class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     * @param HomepageDataGatherer $dataGatherer
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(HomepageDataGatherer $dataGatherer): Response
    {
        return $this->render('homepage.html.twig', $dataGatherer->gatherData());
    }
}
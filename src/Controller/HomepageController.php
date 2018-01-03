<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\Git;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HomepageController
 * @package App\Controller
 */
class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function index(): Response
    {
        $d = $this->getDoctrine();
        $categoryRepo = $d->getRepository(Category::class);
        $groups = [
            strtoupper(Category::TYPE_RP) => $categoryRepo->findByType(Category::TYPE_RP),
            strtoupper(Category::TYPE_HRP) => $categoryRepo->findByType(Category::TYPE_HRP)
        ];

        return $this->render('homepage.html.twig', [
            'groups' => $groups
        ]);
    }
}
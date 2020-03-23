<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\Git;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        // TODO: Optimize
        $em = $this->getDoctrine();
        $categoryRepo = $em->getRepository(Category::class);
//        $categories = $categoryRepo->findTopCategories();
        $groups = [
            strtoupper(Category::TYPE_RP) => $categoryRepo->findByType(Category::TYPE_RP),
            strtoupper(Category::TYPE_HRP) => $categoryRepo->findByType(Category::TYPE_HRP)
        ];

        return $this->render('homepage.html.twig', [
            'groups' => $groups
        ]);
    }
}
<?php

namespace App\Controller;

use App\Utils\Changelog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangelogController extends Controller
{
    /**
     * @Route("/changelog", name="changelog")
     *
     * @param Changelog $changelog
     * @param Request $request
     * @return Response
     */
    public function index(Changelog $changelog, Request $request): Response
    {
        // TODO: User's locale
//        $langCode = $request->getLocale();
        $langCode = 'fr_FR';
        $changelog = $changelog->parseRootChangelog($langCode);

        return $this->render('changelog/index.html.twig', [
            'changelog' => $changelog
        ]);
    }
}
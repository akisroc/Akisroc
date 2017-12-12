<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BoardController
 * @package App\Controller
 */
class BoardController extends Controller
{
    /**
     * @Route("/board/{id}", name="board", requirements={"id"="\d+"})
     *
     * @param Board $board
     * @return Response
     */
    public function index(Board $board): Response
    {
        $em = $this->getDoctrine();
        $topicRepo = $em->getRepository(Topic::class);
        $topics = $topicRepo->findBy(['board' => $board], ['id' => 'DESC']);

        return $this->render('board.html.twig', [
            'topics' => $topics
        ]);
    }

    /**
     * @Route("/board/{id}/add-topic", name="board.add-topic", requirements={"id"="\d+"})
     *
     * @param Board $board
     * @return Response
     */
    public function addTopic(Board $board): Response
    {
        $board->addTopic(new Topic());

//        $form = $this->createForm()
    }
}
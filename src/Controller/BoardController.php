<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use App\Security\Voter\BoardVoter;
use App\Utils\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/board")
 */
class BoardController extends AbstractController
{
    /**
     * @Route("/{id}/{page}", name="board.index", requirements={"id"="\d+", "page"="\d+"})
     *
     * @param Board $board
     * @param int $page
     * @return Response
     */
    public function index(Board $board, int $page = 1): Response
    {
        $d = $this->getDoctrine();
        /** @var TopicRepository $topicRepo */
        $topicRepo = $d->getRepository(Topic::class);
        $paginator = new Paginator($page, 30);
        $pagination = $paginator->paginate($topicRepo->getBoardIndex($board, true));

        return $this->render('board/index.html.twig', [
            'board' => $board,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/{id}/add-topic", name="board.add-topic", requirements={"id"="\d+"})
     *
     *
     * @param Board $board
     * @param Request $request
     * @return Response
     */
    public function addTopic(Board $board, Request $request): Response
    {
        $this->denyAccessUnlessGranted(BoardVoter::ADD_TOPIC, $board);

        $topic = new Topic();
        $topic->setBoard($board);
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $topic = $form->getData();
            $post = $form->get('post')->getData();
            $post->setUser($this->getUser());

            $topic->addPost($post);
            $em->persist($post);
            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('topic.index', ['id' => $topic->getId()]);
        }

        return $this->render('board/add-topic.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
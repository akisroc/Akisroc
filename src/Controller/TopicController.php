<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Entity\Topic;
use App\Form\PostType;
use App\Security\Voter\TopicVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/topic")
 */
class TopicController extends Controller
{
    /**
     * @Route("/{id}/{page}", name="topic.index", requirements={"id"="\d+", "page"="\d+"})
     *
     * @param Topic $topic
     * @param int $page
     * @return Response
     */
    public function index(Topic $topic, int $page = 1): Response
    {
        $this->denyAccessUnlessGranted(TopicVoter::SEE, $topic);

        $limit = 40;
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            ['topic' => $topic],
            ['id' => 'ASC'],
            $limit
        );

        $board = $topic->getBoard();
        $category = $board->getCategory();

        return $this->render('topic/index.html.twig', [
            'category' => $category,
            'board'=> $board,
            'topic' => $topic,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{id}/add-post", name="topic.add-post", requirements={"id"="\d+"})
     *
     * @param Topic $topic
     * @param Request $request
     * @return Response
     */
    public function addPost(Topic $topic, Request $request): Response
    {
        $this->denyAccessUnlessGranted(TopicVoter::ADD_POST, $topic);

        $post = new Post();
        $post->setTopic($topic);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $post->setUser($this->getUser());

            // @Todo Behaviour
            $now = new \DateTime();
            $post->setCreatedAt($now);
            $post->setUpdatedAt($now);

            $topic->addPost($post);
            $em->persist($topic);
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('topic.index', ['id' => $topic->getId(), '_fragment' => $post->getId()]);
        }

        return $this->render('topic/add-post.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
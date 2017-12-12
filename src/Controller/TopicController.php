<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Topic
 * @package App\Controller
 */
class TopicController extends Controller
{
    /**
     * @Route("/topic/{id}/{page}", name="topic", requirements={"id"="\d+", "page"="\d+"})
     *
     * @param Topic $topic
     * @param int $page
     * @return Response
     */
    public function index(Topic $topic, int $page = 1): Response
    {
        $limit = 15;
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            ['topic' => $topic],
            ['id' => 'DESC'],
            $limit,
            $page
        );

        return $this->render('topic.html.twig', [
            'topic' => $topic,
            'posts' => $posts
        ]);
    }
}
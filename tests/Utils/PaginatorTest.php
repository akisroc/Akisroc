<?php

namespace App\Tests\Utils;

use App\Entity\Board;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use App\Utils\Git;
use App\Utils\Paginator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PaginatorTest
 * @package App\Tests\Utils
 */
class PaginatorTest extends WebTestCase
{
    /** @var EntityManager $em */
    private $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()->get('doctrine');
    }

    /**
     * Must paginate query
     *
     * @group unit
     *
     * @return void
     */
    public function testPaginate(): void
    {
        /** @var Board $board */
        $board = $this->em->getRepository(Board::class)->findOneBy(['title' => 'En vrac']);

        /** @var TopicRepository $topicRepo */
        $topicRepo = $this->em->getRepository(Topic::class);

        $queryBuilder = $topicRepo->getBoardIndex($board, true);
        $paginator = new Paginator(1, 2);
        $pagination = $paginator->paginate($queryBuilder);

        $this->assertInstanceOf(\Doctrine\ORM\Tools\Pagination\Paginator::class, $pagination);
        $i = 0;
        foreach ($pagination as $p) {
            ++$i;
        }
        $this->assertEquals(2, $i);
    }
}

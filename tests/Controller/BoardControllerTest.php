<?php

namespace App\Tests\Controller;

use App\Entity\Board;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class BoardControllerTest
 * @package App\Tests\Controller
 */
class BoardControllerTest extends WebTestCase
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
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testPageIsSuccessful(string $url): void
    {
        /** @var Board $board */
        $board = $this->em->getRepository(Board::class)->findOneBy([]);

        $client = static::createClient();
        $client->request('GET', "{$url}/{$board->getId()}");

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield ['/board'];
    }
}
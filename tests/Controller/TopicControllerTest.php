<?php

namespace App\Tests\Controller;

use App\Entity\Topic;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TopicControllerTest
 * @package App\Tests\Controller
 */
class TopicControllerTest extends WebTestCase
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
        /** @var Topic $topic */
        $topic = $this->em->getRepository(Topic::class)->findOneBy([]);

        $client = static::createClient();
        $client->request('GET', "{$url}/{$topic->getId()}");

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield ['/topic'];
    }
}
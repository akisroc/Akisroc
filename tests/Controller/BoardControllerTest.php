<?php

namespace App\Tests\Controller;

use App\Entity\Board;
use App\Entity\Topic;
use App\Tests\MockData;
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
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testAddTopic(string $url): void
    {
        /** @var Board $board */
        $board = $this->em->getRepository(Board::class)->findOneBy([]);

        $client = static::createClient([], MockData::USER_CREDENTIALS);
        $crawler = $client->request('GET', "{$url}/{$board->getId()}/add-topic");

        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('Valider')->form();
        $form['topic[title]'] = 'Dummy Topic';
        $form['topic[post][content]'] = 'Dummy Content';
        $client->submit($form);
        $client->followRedirect();

        $topic = $this->em->getRepository(Topic::class)->findOneBy(['board' => $board], ['id' => 'DESC']);
        $this->assertRegExp(
            "~^https?:\/\/.+\/topic\/{$topic->getId()}\/?$~",
            $client->getHistory()->current()->getUri()
        );
        $this->assertContains('<h2>Dummy Topic</h2>', $client->getResponse()->getContent());
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield ['/board'];
    }
}
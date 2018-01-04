<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Tests\MockData;
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
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testAddPost(string $url): void
    {
        /** @var Topic $topic */
        $topic = $this->em->getRepository(Topic::class)->findOneBy([]);

        $client = static::createClient([], MockData::USER_CREDENTIALS);
        $crawler = $client->request('GET', "{$url}/{$topic->getId()}/add-post");

        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('Valider')->form();
        $form['post[content]'] = 'Dummy Post Content';
        $client->submit($form);
        $client->followRedirect();

        $this->assertRegExp(
            "~^https?:\/\/.+\/topic\/{$topic->getId()}#{$topic->getLastPost()->getId()}$~",
            $client->getHistory()->current()->getUri()
        );
        $this->assertContains('Dummy Post Content', $client->getResponse()->getContent());
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield ['/topic'];
    }
}
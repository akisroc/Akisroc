<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Tests\MockData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TopicControllerTest
 * @package App\Tests\Controller
 */
class TopicControllerTest extends WebTestCase
{
    /**
     * @group functional
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testPageIsSuccessful(string $url): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.category a.description')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.list a')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testAddPost(string $url): void
    {
        $client = static::createClient([], MockData::USER_CREDENTIALS);

        $crawler = $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.category a.description')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.list a')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.button-new')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->filter('form[name="post"]')->eq(0)->form();
        $form['post[content]'] = 'Dummy Post Content';
        $client->submit($form);
        $client->followRedirect();

        $this->assertContains('<h2>Dummy Topic</h2>', $client->getResponse()->getContent());
        $this->assertContains('Dummy Post Content', $client->getResponse()->getContent());
        $this->assertRegExp(
            "~^https?:\/\/.+\/topic\/\d+\#\d+/?$~",
            $client->getHistory()->current()->getUri()
        );
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield ['/'];
    }
}

<?php

namespace App\Tests\Controller;

use App\Tests\MockData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class BoardControllerTest
 * @package App\Tests\Controller
 */
class BoardControllerTest extends WebTestCase
{
    /**
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
    }

    /**
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testAddTopic(string $url): void
    {
        $client = static::createClient([], MockData::USER_CREDENTIALS);

        $crawler = $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.category a.description')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->filter('.button-new')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->filter('form[name="topic"]')->eq(0)->form();
        $form['topic[title]'] = 'Dummy Topic';
        $form['topic[post][content]'] = 'Dummy Content';
        $client->submit($form);
        $client->followRedirect();

        $this->assertContains('<h2>Dummy Topic</h2>', $client->getResponse()->getContent());
        $this->assertRegExp(
            "~^https?:\/\/.+\/topic\/\d+\/?$~",
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

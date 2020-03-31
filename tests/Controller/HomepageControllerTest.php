<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomepageControllerTest
 * @package App\Tests\Controller
 */
class HomepageControllerTest extends WebTestCase
{
    /**
     * @group functional
     * @dataProvider urlProvider
     * @param string $url
     */
    public function testPageIsSuccessful(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield ['/'];
    }
}

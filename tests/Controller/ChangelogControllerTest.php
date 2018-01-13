<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ChangelogControllerTest
 * @package App\Tests\Controller
 */
class ChangelogControllerTest extends WebTestCase
{
    public function testPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/changelog');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
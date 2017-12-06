<?php

namespace App\Tests\Utils;

use App\Entity\Post;
use App\Entity\Topic;
use App\Utils\FixturesService;
use PHPUnit\Framework\TestCase;

/**
 * Class FixturesServiceTest
 * @package App\Tests\Utils
 */
class FixturesServiceTest extends TestCase
{
    function testFilterReferences()
    {
        $refs = [new Post(), new Post(), new Post(), new Topic()];
        $this->assertCount(3, FixturesService::filterReferences($refs, Post::class));
        $this->assertCount(1, FixturesService::filterReferences($refs, Topic::class));
    }
}
<?php

namespace App\Tests\Utils;

use App\Entity\Board;
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
    /**
     * References must be filtered by class name.
     */
    function testFilterReferences()
    {
        $refs = [new Post(), new Post(), new Post(), new Topic()];

        $posts = FixturesService::filterReferences($refs, Post::class);
        $this->assertCount(3, $posts);
        array_map(function (Post $post): void {
            $this->assertInstanceOf(Post::class, $post);
            }, $posts
        );

        $topics = FixturesService::filterReferences($refs, Topic::class);
        $this->assertCount(1, $topics);
        array_map(function (Topic $topic): void {
            $this->assertInstanceOf(Topic::class, $topic);
            }, $topics
        );

        $boards = FixturesService::filterReferences($refs, Board::class);
        $this->assertEmpty($boards);
    }
}
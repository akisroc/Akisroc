<?php

namespace App\Tests\Utils;

use App\Entity\Board;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
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
     *
     * @group unit
     *
     * @return void
     */
    public function testFilterReferences(): void
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

    /**
     * Random entity must match with given class.
     *
     * @group unit
     *
     * @retun void
     */
    public function testRandEntity(): void
    {
        $entities = (function (): array {
            $entities = [];
            for ($i = 0; $i < 100; ++$i) { $entities[] = new Category(); }
            for ($i = 0; $i < 100; ++$i) { $entities[] = new Board(); }
            for ($i = 0; $i < 100; ++$i) { $entities[] = new Topic(); }
            for ($i = 0; $i < 100; ++$i) { $entities[] = new Post(); }
            for ($i = 0; $i < 100; ++$i) { $entities[] = new User(); }

            return $entities;
        })();

        $object = FixturesService::randEntity($entities);
        $this->assertTrue(
            in_array(
                get_class($object),
                [Category::class, Board::class, Topic::class, Post::class, User::class],
                true
            )
        );

        $category = FixturesService::randEntity($entities, Category::class);
        $this->assertInstanceOf(Category::class, $category);

        $topic = FixturesService::randEntity($entities, Topic::class);
        $this->assertInstanceOf(Topic::class, $topic);

        $post = FixturesService::randEntity($entities, Post::class);
        $this->assertInstanceOf(Post::class, $post);
    }
}

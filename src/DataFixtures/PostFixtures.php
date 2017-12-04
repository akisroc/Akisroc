<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture
{
    protected const COUNT = 20;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < self::COUNT; ++$i) {
            $post = new Post();
            $post->setContent($faker->paragraphs(mt_rand(1, 5), true));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
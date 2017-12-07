<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Protagonist;
use App\Entity\Topic;
use App\Entity\User;
use App\Utils\FixturesService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture implements DependentFixtureInterface
{
    protected const COUNT = 2000;

    /** @var \Faker\Generator $faker */
    protected $faker;

    /**
     * PostFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $references = $this->referenceRepository->getReferences();
        $userRepo = $manager->getRepository(User::class);
        for ($i = 0; $i < self::COUNT; ++$i) {
            $content = $this->faker->paragraphs(mt_rand(1, 5), true);
            $topic = FixturesService::randEntity($references, Topic::class);
            $user = FixturesService::randEntity($references, User::class);
            $protagonist = FixturesService::randEntity($user->getProtagonists()->toArray(), Protagonist::class);
            $post = Post::create($content, $topic, $user, $protagonist);

            $this->setReference("post_$i", $post);
            if (empty($userRepo->findBy(['name' => $post->getUser()->getName()]))) {
                $manager->persist($post);
            } else {
                $manager->merge($post);
            }
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            TopicFixtures::class,
            UserFixtures::class,
            ProtagonistFixtures::class
        ];
    }
}
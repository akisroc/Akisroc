<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Topic;
use App\Utils\FixturesService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class TopicFixtures
 * @package App\DataFixtures
 */
class TopicFixtures extends Fixture implements DependentFixtureInterface
{
    protected const COUNT = 100;

    /** @var \Faker\Generator $faker */
    protected $faker;

    /**
     * TopicFixtures constructor.
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
        /** @var Category[] $categories */
        $categories = FixturesService::filterReferences($this->referenceRepository->getReferences(), Category::class);
        for ($i = 0; $i < self::COUNT; ++$i) {
            $topic = Topic::create($this->faker->sentence(mt_rand(2,3)), $categories[array_rand($categories)]);
            $this->setReference("topic_$i", $topic);
            $manager->persist($topic);
        }

//        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class
        ];
    }
}
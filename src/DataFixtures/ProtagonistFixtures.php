<?php

namespace App\DataFixtures;

use App\Entity\Protagonist;
use App\Entity\User;
use App\Utils\FixturesService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ProtagonistFixtures
 * @package App\DataFixtures
 */
class ProtagonistFixtures extends Fixture implements DependentFixtureInterface
{
    protected const COUNT = 100;

    /** @var \Faker\Generator $faker */
    protected $faker;

    /**
     * ProtagonistFixtures constructor.
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
        for ($i = 0; $i < self::COUNT; ++$i) {
            $protagonist = Protagonist::create(
                $this->faker->lastName . '_' . mt_rand(1, 999999999),
                $this->faker->imageUrl(180, 180, 'abstract'),
                FixturesService::randEntity($references, User::class)
            );

            $this->setReference("protagonist_{$protagonist->getName()}", $protagonist);
            $manager->persist($protagonist);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
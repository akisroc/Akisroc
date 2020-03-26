<?php

declare(strict_types=1);

namespace App\DataFixtures\Dev;

use App\Entity\Protagonist;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ProtagonistFixtures
 * @package App\DataFixtures\Dev
 */
class ProtagonistFixtures extends Fixture implements DependentFixtureInterface
{
    public const COUNT = 100;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < self::COUNT; ++$i) {
            $protagonist = new Protagonist();

            /** @var User $user */
            $user = $this->getReference('user_' . rand(0, UserFixtures::COUNT - 1));
            $user->addProtagonist($protagonist);

            $name = $faker->firstName . ' ' . $faker->lastName . ' ' . $faker->randomNumber(6);
            $protagonist->setName($name);
            $protagonist->setAvatar($faker->imageUrl());
            $protagonist->setAnonymous($faker->boolean);

            $this->setReference("protagonist_$i", $protagonist);
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
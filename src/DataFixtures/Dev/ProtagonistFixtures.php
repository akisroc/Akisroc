<?php

namespace App\DataFixtures\Dev;

use App\Entity\Protagonist;
use App\Entity\User;
use App\Utils\FixturesService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ProtagonistFixtures
 * @package App\DataFixtures\Dev
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
            $user = FixturesService::randEntity($references, User::class);
            $name = $this->faker->lastName . ' ' . ucfirst($this->faker->colorName)  .  ' ' . $this->faker->firstName;
            $secretAuthor = $this->faker->randomElement([true, false]);
            $avatar = $this->faker->imageUrl(180, 180, 'abstract');

            $protagonist = (new Protagonist())
                ->setName($name)
                ->setSecretAuthor($secretAuthor)
                ->setAvatar($avatar)
                ->setUser($user)
            ;
            $user->addProtagonist($protagonist);

            $this->setReference("protagonist_{$protagonist->getName()}", $protagonist);
            if (!$manager->contains($protagonist)) {
                $manager->persist($protagonist);
            } else {
                $manager->merge($protagonist);
            }
        }

//        $manager->flush();
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
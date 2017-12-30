<?php

namespace App\DataFixtures;

use App\Entity\Board;
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
        /** @var Board[] $categories */
        $boards = FixturesService::filterReferences($this->referenceRepository->getReferences(), Board::class);
        $randBoard = function () use (&$boards): Board { return $boards[array_rand($boards)]; };
        for ($i = 0; $i < self::COUNT; ++$i) {
            $board = $randBoard();
            $title = $this->faker->sentence(mt_rand(2, 4));
            $topic = (new Topic())
                ->setTitle($title)
                ->setBoard($board)
            ;
            $this->setReference("topic_$i", $topic);

            if (!$manager->contains($topic)) {
                $manager->persist($topic);
            } else {
                $manager->merge($topic);
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
            BoardFixtures::class
        ];
    }
}
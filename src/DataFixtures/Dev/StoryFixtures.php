<?php

declare(strict_types=1);

namespace App\DataFixtures\Dev;

use App\Entity\Episode;
use App\Entity\Protagonist;
use App\Entity\Story;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class StoryFixtures
 * @package App\DataFixtures\Dev
 */
class StoryFixtures extends Fixture implements DependentFixtureInterface
{
    private const EPISODE_COUNT = 500;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $remainingEpisodes = self::EPISODE_COUNT;

        $i = 0;
        $date = \DateTime::createFromFormat('Ymd', '20200101');
        $interval = \DateInterval::createFromDateString('1 hour + 3 minutes');
        do {
            $story = new Story();

            $title = $faker->colorName . ' ' . $faker->numberBetween(1, 999) . ' ' . $faker->words(2, true);
            $story->setTitle($title);

            for ($j = 0, $n = rand(1, 33); $j < $n; ++$j) {
                $date = clone $date->add($interval);

                $episode = new Episode();
                /** @var Protagonist $protagonist */
                $protagonist = $this->getReference('protagonist_' . rand(0, ProtagonistFixtures::COUNT - 1));
                $protagonist->addEpisode($episode);
                $story->addEpisode($episode);

                $episode->setContent($faker->text(2000));
                $episode->setCreatedAt($date);
                $episode->setUpdatedAt($date);

                --$remainingEpisodes;
                $this->setReference("episode_$j", $episode);
                $manager->persist($episode);
            }
            ++$i;
            $this->setReference("story_$i", $story);
            $manager->persist($story);
        } while ($remainingEpisodes > 0);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            ProtagonistFixtures::class
        ];
    }
}

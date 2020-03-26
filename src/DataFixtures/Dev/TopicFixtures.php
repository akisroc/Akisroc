<?php

declare(strict_types=1);

namespace App\DataFixtures\Dev;

use App\DataFixtures\BoardFixtures;
use App\Entity\Board;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class TopicFixtures
 * @package App\DataFixtures\Dev
 */
class TopicFixtures extends Fixture implements DependentFixtureInterface
{
    private const POST_COUNT = 500;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $remainingPosts = self::POST_COUNT;

        $i = 0;
        $date = \DateTime::createFromFormat('Ymd', '20200101');
        $interval = \DateInterval::createFromDateString('1 hour + 3 minutes');
        $nbOfBoards = count(BoardFixtures::provideBoards());
        do {
            $topic = new Topic();
            /** @var Board $board */
            $board = $this->getReference(
                'board_' . rand(0, $nbOfBoards - 1)
            );
            $board->addTopic($topic);

            $title = $faker->colorName . ' ' . $faker->numberBetween(1, 999) . ' ' . $faker->words(2, true);
            $topic->setTitle($title);

            for ($j = 0, $n = rand(1, 33); $j < $n; ++$j) {
                $date = clone $date->add($interval);

                $post = new Post();
                /** @var User $user */
                $user = $this->getReference('user_' . rand(0, UserFixtures::COUNT));
                $user->addPost($post);
                $topic->addPost($post);

                $post->setContent($faker->text(2000));
                $post->setCreatedAt($date);
                $post->setUpdatedAt($date);

                --$remainingPosts;
                $this->setReference("post_$j", $post);
                $manager->persist($post);
            }
            ++$i;
            $this->setReference("thread_$i", $topic);
            $manager->persist($topic);
        } while ($remainingPosts > 0);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class, BoardFixtures::class
        ];
    }
}

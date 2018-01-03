<?php

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class BoardFixtures
 * @package App\DataFixtures
 */
class BoardFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var \Faker\Generator $faker */
    protected $faker;

    /**
     * CategoryFixtures constructor.
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
        foreach ($this->provideBoards() as $node) {
            $board = (new Board())
                ->setTitle($node['title'])
                ->setDescription($node['description'])
                ->setCategory($node['category'])
            ;
            $node['category']->addBoard($board);
            if (!$manager->contains($board)) {
                $manager->persist($board);
            } else {
                $manager->merge($board);
            }
            $this->setReference("board_{$board->getTitle()}", $board);
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

    /**
     * @return array
     */
    protected function provideBoards(): array
    {
        $fakeDescription = function (): string { return $this->faker->paragraph(mt_rand(1, 3)); };

        $c = function (string $title) { return $this->getReference("category_$title"); };

        return [
            [
                'title' => 'Place Frozell',
                'description' => $fakeDescription(),
                'category' => $c('Akisroc')
            ],
            [
                'title' => 'Quartier Merkin',
                'description' => $fakeDescription(),
                'category' => $c('Akisroc')
            ],
            [
                'title' => 'Ville basse',
                'description' => $fakeDescription(),
                'category' => $c('Akisroc')
            ],
            [
                'title' => 'Grande bibliothèque',
                'description' => $fakeDescription(),
                'category' => $c('Akisroc')
            ],
            [
                'title' => 'Vie en Dragostina',
                'description' => $fakeDescription(),
                'category' => $c('Dragostina')
            ],
            [
                'title' => 'En vrac',
                'description' => $fakeDescription(),
                'category' => $c('Discussions')
            ],
            [
                'title' => 'IRL',
                'description' => $fakeDescription(),
                'category' => $c('Discussions')
            ],
            [
                'title' => 'Bugs',
                'description' => $fakeDescription(),
                'category' => $c('Le jeu')
            ],
            [
                'title' => 'Idées',
                'description' => $fakeDescription(),
                'category' => $c('Le jeu')
            ],
            [
                'title' => 'Autour du RP',
                'description' => $fakeDescription(),
                'category' => $c('Organisation du RP')
            ],
            [
                'title' => 'Propositions de scénarii',
                'description' => $fakeDescription(),
                'category' => $c('Organisation du RP')
            ]
        ];
    }
}
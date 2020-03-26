<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class BoardFixtures
 * @package App\DataFixtures
 */
class BoardFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $boards = self::provideBoards();
        for ($i = 0, $c = count($boards); $i < $c; ++$i) {
            $board = new Board();
            $board->setTitle($boards[$i]['title']);
            $board->setDescription($boards[$i]['description']);
            $manager->persist($board);
            $this->setReference("board_$i", $board);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function provideBoards(): array
    {
        $faker = Factory::create('fr_FR');
        $fakeDescription = fn() => $faker->paragraph(mt_rand(1, 3));

        /** Todo: Translations */
        return [
            [
                'title' => 'En vrac',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'IRL',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Bugs',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Idées',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Autour du RP',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Propositions de scénarii',
                'description' => $fakeDescription()
            ]
        ];
    }
}
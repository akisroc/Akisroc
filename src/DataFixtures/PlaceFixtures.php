<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class PlaceFixtures
 * @package App\DataFixtures
 */
class PlaceFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $places = self::providePlaces();
        for ($i = 0, $c = count($places); $i < $c; ++$i) {
            $place = new Place();
            $place->setTitle($places[$i]['title']);
            $place->setDescription($places[$i]['description']);
            $manager->persist($place);
            $this->setReference("place_$i", $place);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function providePlaces(): array
    {
        $faker = Factory::create('fr_FR');
        $fakeDescription = fn() => $faker->paragraph(mt_rand(1, 3));

        /** Todo: Translations */
        return [
            [
                'title' => 'Place Frozell',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Quartier Merkin',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Ville basse',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Grande bibliothèque',
                'description' => $fakeDescription()
            ],
            [
                'title' => 'Vie en Dragostina',
                'description' => $fakeDescription()
            ]
        ];
    }
}
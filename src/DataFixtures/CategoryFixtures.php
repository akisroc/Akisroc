<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class CategoryFixtures
 * @package App\DataFixtures
 */
class CategoryFixtures extends Fixture
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
        foreach ($this->provideCategories() as $node) {
            $category = new Category();
            $category->setTitle($node['title'] ?: $this->faker->sentence(2, 3));
            $category->setDescription($node['description'] ?: $this->faker->paragraph(mt_rand(1, 3)));
            $category->setType($node['type'] ?: $this->faker->randomElement([Category::TYPE_RP, Category::TYPE_HRP]));

            if (!$manager->contains($category)) {
                $manager->persist($category);
            } else {
                $manager->merge($category);
            }

            $this->setReference("category_{$category->getTitle()}", $category);
        }

//        $manager->flush();
    }

    /**
     * @return array
     */
    protected function provideCategories(): array
    {
        return [
            [
                'title' => 'Akisroc',
                'type' => Category::TYPE_RP,
                'description' => null,
                'children' => []
            ],
            [
                'title' => 'Dragostina',
                'type' => Category::TYPE_RP,
                'description' => null,
                'children' => []
            ],
            [
                'title' => 'Discussions',
                'type' => Category::TYPE_HRP,
                'description' => null,
                'children' => []
            ],
            [
                'title' => 'Organisation du RP',
                'type' => Category::TYPE_HRP,
                'description' => null,
                'children' => []
            ],
            [
                'title' => 'Le jeu',
                'type' => Category::TYPE_HRP,
                'description' => null,
                'children' => []
            ]
        ];
    }
}
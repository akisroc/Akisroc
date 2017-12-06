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
        $persistRecursive = function (array $nodes, Category $parentCategory = null) use (&$manager, &$persistRecursive): void {
            foreach ($nodes as $node) {
                $category = Category::create(
                    $node['title'] ?: $this->faker->sentence(2, 3),
                    $node['description'] ?: $this->faker->paragraph(mt_rand(1, 3)),
                    $parentCategory
                );
                $manager->persist($category);
                $this->setReference("category_{$category->getTitle()}", $category);

                if (!empty($node['children'])) {
                    $persistRecursive($node['children'], $category);
                }
            }
        };

        $persistRecursive($this->provideCategories());

//        $manager->flush();
    }

    /**
     * @return array
     */
    protected function provideCategories(): array
    {
        return [
            [
                'title' => 'RP',
                'description' => null,
                'children' => [
                    [
                        'title' => 'Akisroc',
                        'description' => null,
                        'children' => []
                    ],
                    [
                        'title' => 'Dragostina',
                        'description' => null,
                        'children' => []
                    ]
                ]
            ],
            [
                'title' => 'HRP',
                'description' => null,
                'children' => [
                    [
                        'title' => 'Discussions',
                        'description' => null,
                        'children' => []
                    ],
                    [
                        'title' => 'Organisation du RP',
                        'description' => null,
                        'children' => []
                    ],
                    [
                        'title' => 'Le jeu',
                        'description' => null,
                        'children' => []
                    ]
                ]
            ]
        ];
    }
}
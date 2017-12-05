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
//                if ($parentCategory) {
//                    $parentCategory->addChild($category);
//                    $manager->persist($parentCategory);
//                } else {
//                    $manager->persist($category);
//                }
                $this->addReference("category_{$category->getTitle()}", $category);

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
        $fakeDescription = function (): string { return $this->faker->paragraph(mt_rand(1, 3)); };

        return [
            [
                'title' => 'RP',
                'description' => null,
                'children' => [
                    [
                        'title' => 'Akisroc',
                        'description' => null,
                        'children' => [
                            [
                                'title' => 'Place Frozell',
                                'description' => $fakeDescription(),
                                'children' => []
                            ],
                            [
                                'title' => 'Quartier Merkin',
                                'description' => $fakeDescription(),
                                'children' => []
                            ],
                            [
                                'title' => 'Ville basse',
                                'description' => $fakeDescription(),
                                'children' => []
                            ],
                            [
                                'title' => 'Grande bibliothèque',
                                'description' => $fakeDescription(),
                                'children' => []
                            ]
                        ]
                    ],
                    [
                        'title' => 'Dragostina',
                        'description' => null,
                        'children' => [
                            [
                                'title' => 'Vie en Dragostina',
                                'description' => $fakeDescription(),
                                'children' => []
                            ]
                        ]
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
                        'children' => [
                            [
                                'title' => 'En vrac',
                                'description' => $fakeDescription(),
                                'children' => []
                            ],
                            [
                                'title' => 'IRL',
                                'description' => $fakeDescription(),
                                'children' => []
                            ]
                        ]
                    ],
                    [
                        'title' => 'Organisation du RP',
                        'description' => null,
                        'children' => [
                            [
                                'title' => 'Propositions de scénarii',
                                'description' => $fakeDescription(),
                                'children' => []
                            ],
                            [
                                'title' => 'Autour du RP',
                                'description' => $fakeDescription(),
                                'children' => []
                            ]
                        ]
                    ],
                    [
                        'title' => 'Le jeu',
                        'description' => null,
                        'children' => [
                            [
                                'title' => 'Idées',
                                'description' => $fakeDescription(),
                                'children' => []
                            ],
                            [
                                'title' => 'Bugs',
                                'description' => $fakeDescription(),
                                'children' => []
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
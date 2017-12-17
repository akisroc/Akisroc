<?php

namespace App\DataFixtures;

use App\Entity\Board;
use App\Entity\Topic;
use App\Entity\User;
use App\Utils\FixturesService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    protected const COUNT = 30;

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
        $encoder = Argon2iPasswordEncoder::isSupported()
            ? new Argon2iPasswordEncoder()
            : new BCryptPasswordEncoder(4)
        ;
        //            $encoder = new BCryptPasswordEncoder(4);
        for ($i = 0; $i < self::COUNT; ++$i) {
            if ($i === 0) {
                $user = User::create(
                    'admin',
                    'admin@admin.dev',
                    $this->faker->imageUrl(180, 180, 'abstract')
                );
                $user->setPassword($encoder->encodePassword('admin', $this->faker->sha256));
            } else if ($i === 1) {
                $user = User::create(
                    'user',
                    'user@user.dev',
                    $this->faker->imageUrl(180, 180, 'abstract')
                );
                $user->setPassword($encoder->encodePassword('user', $this->faker->sha256));
            }  else {
                $user = User::create(
                    $this->faker->firstName . '_' . mt_rand(1, 999999999),
                    $this->faker->email,
                    $this->faker->imageUrl(180, 180, 'abstract')
                );
                $user->setPassword($encoder->encodePassword(
                    $this->faker->password(15, 70),
                    $this->faker->sha256
                ));
            }

            if (empty($manager->getRepository(User::class)->findBy(['username' => $user->getUsername()]))) {
                $this->setReference("user_{$user->getUsername()}", $user);
                $manager->persist($user);
            }
        }

//        $manager->flush();
    }
}
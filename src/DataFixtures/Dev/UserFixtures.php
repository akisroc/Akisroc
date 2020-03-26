<?php

declare(strict_types=1);

namespace App\DataFixtures\Dev;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;

/**
 * Class UserFixtures
 * @package App\DataFixtures\Dev
 */
class UserFixtures extends Fixture
{
    public const COUNT = 30;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $encoder = new SodiumPasswordEncoder();


        $users = [
            ['username' => 'admin', 'email' => 'admin@akisroc-jdr.fr', 'password' => 'admin', 'roles' => ['ROLE_USER', 'ROLE_ADMIN']],
            ['username' => 'user', 'email' => 'user@akisroc-jdr.fr', 'password' => 'user', 'roles' => ['ROLE_USER']],
        ];
        for ($i = 0; $i < self::COUNT; ++$i) {
            $users[] = [
                'username' => $faker->unique()->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => $faker->password(8, 100),
                'roles' => ['ROLE_USER']
            ];
        }

        for ($i = 0, $c = count($users); $i < $c; ++$i) {
            $user = new User();

            $user->setUsername($users[$i]['username']);
            $user->setEmail($users[$i]['email']);
            $user->setSalt($user->generateSalt());
            $user->setPassword(
                $encoder->encodePassword($users[$i]['password'], $user->getSalt())
            );
            $user->addRoles($users[$i]['roles']);
            $user->setEnabled($faker->boolean(92));
            $user->setAvatar($faker->imageUrl());

            $this->setReference("user_$i", $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
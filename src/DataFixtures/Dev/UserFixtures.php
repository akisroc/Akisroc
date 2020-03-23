<?php

namespace App\DataFixtures\Dev;

use App\DataFixtures\Prod\ProdUserFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;

/**
 * Class UserFixtures
 * @package App\DataFixtures\Dev
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
        $encoder = new SodiumPasswordEncoder();
        for ($i = 0; $i < self::COUNT; ++$i) {
            $avatar = $this->faker->imageUrl(180, 180, 'abstract');
            if ($i === 0) {
                $user = (new User())
                    ->setUsername('admin')
                    ->setEmail('admin@admin.dev')
                    ->setAvatar($avatar)
                    ->setSalt(User::generateSalt())
                ;
                $user->setPassword($encoder->encodePassword('admin', $user->getSalt()));
            } else if ($i === 1) {
                $user = (new User())
                    ->setUsername('user')
                    ->setEmail('user@user.dev')
                    ->setAvatar($avatar)
                    ->setSalt(User::generateSalt());
                ;
                $user->setPassword($encoder->encodePassword('user', $user->getSalt()));
            } else {
                $username = $this->faker->firstName() . ' ' . $this->faker->country . ' ' . $this->faker->city;
                $user = (new User())
                    ->setUsername($username)
                    ->setEmail($this->faker->email)
                    ->setAvatar($avatar)
                    ->setSalt(User::generateSalt())
                ;
                $user->setPassword($encoder->encodePassword(
                    $this->faker->password(255, 511),
                    $user->getSalt()
                ));
            }

            if (empty($manager->getRepository(User::class)->findBy(['username' => $user->getUsername()]))) {
                $this->setReference("user_{$user->getUsername()}", $user);
                if (!$manager->contains($user)) {
                    $manager->persist($user);
                } else {
                    $manager->merge($user);
                }
            }
        }

//        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            ProdUserFixtures::class
        ];
    }
}
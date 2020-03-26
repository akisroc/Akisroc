<?php

declare(strict_types=1);

namespace App\DataFixtures\Prod;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;

/**
 * Class ProdUserFixtures
 * @package App\DataFixtures
 */
class ProdUserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $cr = $this->getACredentials();

        $encoder = new SodiumPasswordEncoder();

        $user = new User();

        $user->setUsername($cr[0]);
        $user->setEmail($cr[1]);
        $user->setSalt($user->generateSalt());
        $user->setAvatar(filter_var($cr[3], FILTER_VALIDATE_URL) ? $cr[3] : null);
        $user->setPassword($encoder->encodePassword($cr[2], $user->getSalt()));

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @return array
     */
    private function getACredentials(): array
    {
        $apwd = realpath(__DIR__ . DIRECTORY_SEPARATOR . '.apwd');
        if (!file_exists($apwd)) {
            throw new \RuntimeException('Must define admin credentials.');
        }
        $credentials = explode(PHP_EOL, file_get_contents($apwd));

        if (count($credentials) < 4) {
            throw new \RuntimeException('Incorrect credentials format.');
        }

        return array_slice($credentials, 0, 4);
    }
}
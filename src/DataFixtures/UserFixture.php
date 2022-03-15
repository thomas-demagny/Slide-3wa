<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    const DATA = 16;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= self::DATA; ++$i) {
            $user = new User();
            $passHashed = $this->passwordHasher->hashPassword($user, 'password');

            $user
                ->setEmail($faker->email())
                ->setPassword($passHashed)
                ->setIsVerified(true)
                ->setCreatedAt($faker->dateTimebetween('-7 days'));

            $manager->persist($user);

            $fixtureName = 'user' . $i;
            $this->addReference($fixtureName, $user);
        }

        $manager->flush();
    }
}
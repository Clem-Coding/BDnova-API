<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}

    public function load(ObjectManager $manager): void
    {

        $usersData = [
            ['email' => 'user@bdnova-api.com', 'roles' => ['ROLE_USER']],
            ['email' => 'admin@bdnova-api.com', 'roles' => ['ROLE_ADMIN']],
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}

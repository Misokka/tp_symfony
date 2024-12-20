<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user1@example.com');
        $user->setFirstname('User');
        $user->setLastname('One');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'userpass'));
        $manager->persist($user);

        $banned = new User();
        $banned->setEmail('banned@example.com');
        $banned->setFirstname('Banned');
        $banned->setLastname('One');
        $banned->setRoles(['ROLE_BANNED']);
        $banned->setPassword($this->passwordHasher->hashPassword($banned, 'bannedpass'));
        $manager->persist($banned);

        $manager->flush();
    }
}

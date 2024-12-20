<?php

namespace App\DataFixtures;

use App\Entity\Location;
use App\Entity\Activity;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EventFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setFirstname('User');
        $user->setLastname('Example');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $locations = [];
        for ($i = 1; $i <= 5; $i++) {
            $location = new Location();
            $location->setName("Lieu $i")
                ->setAddress("Adresse $i, Rue de Exemple")
                ->setCity("Ville $i")
                ->setUser($user);
            $manager->persist($location);
            $locations[] = $location;
        }

        $activities = [];
        for ($i = 1; $i <= 5; $i++) {
            $activity = new Activity();
            $activity->setName("Activité $i")
                ->setDescription("Description de l'activité $i")
                ->setUser($user);
            $manager->persist($activity);
            $activities[] = $activity;
        }

        for ($i = 1; $i <= 5; $i++) {
            $event = new Event();
            $event->setName("Événement $i")
                ->setDescription("Description de l'événement $i")
                ->setDate(new \DateTime("+{$i} days"))
                ->setLocation($locations[array_rand($locations)])
                ->setUser($user);
            $manager->persist($event);
        }

        $manager->flush();
    }
}

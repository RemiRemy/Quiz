<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPseudo("Admin")
            ->setEmail("admin@quizzup.fr")
            ->setPassword($this->hasher->hashPassword($user, "admin"))
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);

        for($i = 1; $i < 10; $i++) {
            $user = new User();
            $user->setPseudo("User $i")
                ->setEmail("user.$i@quizzup.fr")
                ->setPassword($this->hasher->hashPassword($user, "user$i"));
            $manager->persist($user);
        }

        $manager->flush();
    }
}

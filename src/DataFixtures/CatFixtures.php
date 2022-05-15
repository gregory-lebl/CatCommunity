<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class CatFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $slugify = new Slugify();
        $user = new User();

        $user->setEmail("fixture@test.fr");
        $user->setFirstname("Pierre");
        $user->setLastname("Fixture");
        $password = $this->hasher->hashPassword($user,"azerty");
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        for ($i = 0; $i <= 100; $i++){
            $title = "Chat numéro " . $i;
            $image = new Image();
            $image->setUser($user);
            $image->setName("cat6280c6dc6bfdb.webp");
            $image->setSlug($slugify->slugify($title));
            $image->setTitle($title);

            $manager->persist($image);
        }

        $manager->flush();
    }

}

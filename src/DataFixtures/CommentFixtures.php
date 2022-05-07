<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    private ImageRepository $imageRepository;
    private UserRepository $userRepository;

    public function __construct(ImageRepository $imageRepository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->imageRepository = $imageRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy(['email' => "fixture@test.fr"]);
        $image = $this->imageRepository->findOneBy(['id' => 1]);

        for ($i = 0; $i <= 20; $i++){
            $content = "Commentaire n° " . $i;
            $comment = new Commentaire();
            $comment->setUser($user);
            $comment->setImage($image);
            $comment->setContent($content);

            $manager->persist($comment);
        }

        $manager->flush();
    }

}

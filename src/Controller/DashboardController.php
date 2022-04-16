<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
use App\Repository\ImageRepository;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(ImageRepository $imageRepository, CommentaireRepository $commentaireRepository): Response
    {
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();

        return $this->render('dashboard/index.html.twig',[
            'images' => $imageRepository->findBy(['user' => $userId])
        ]);
    }

    #[Route('/dashboard/add', name: 'dashboard_add')]
    public function add(): Response
    {
        return $this->render('dashboard/add.html.twig');
    }


    #[Route('/upload', name: 'upload', methods: "POST")]
    public function upload(UploadService $uploadService,ImageRepository $imageRepository): RedirectResponse
    {
        $user = $this->getUser();
        $image = $uploadService->uploadCatImage($user,$_FILES["image"],$_POST["title"]);
        $imageRepository->add($image);

        return $this->redirectToRoute('dashboard');
    }
}
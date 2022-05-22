<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\CommentaireRepository;
use App\Repository\ImageRepository;
use App\Service\UploadService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(ImageRepository $imageRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $allPictures = $imageRepository->findByMostRecent();

        $images = $paginator->paginate($allPictures,intval($request->get('page', 1)),12);

        return $this->render('dashboard/index.html.twig',[
            'images' => $images,
        ]);
    }

    #[Route('/dashboard/add', name: 'dashboard_add')]
    public function add(): Response
    {
        return $this->render('dashboard/add.html.twig');
    }

    #[Route('/dashboard/{slug}', name: 'dashboard_comments')]
    public function comments(Image $image, CommentaireRepository $commentaireRepository, Request $request, PaginatorInterface $paginator ): Response
    {
        $allComments = $commentaireRepository->findBy(['image' => $image->getId()]);

        $comments = $paginator->paginate($allComments,intval($request->get('page', 1)),12);

        return $this->render('dashboard/comments.html.twig',[
            'image' => $image,
            'comments' => $comments
        ]);
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

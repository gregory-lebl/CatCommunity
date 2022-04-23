<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    #[Route('/delete/picture/{id}', name: 'delete_picture')]
    public function deletePicture(Image $image, ImageRepository $imageRepository): Response
    {
        if ($image->getUser() == $this->getUser()){
            dd('user correct');
        }else{
            dd('user incorrect');
        }

        $imageRepository->remove($image);
        return $this->redirectToRoute('dashboard');
    }

    #[Route('/delete/user/{id}', name: 'delete_user')]
    public function deleteUser(Request $request, UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();

        if (intval($request->get('id')) !== $currentUser->getId()){
            $this->addFlash('error','Vous ne pouvez pas supprimer cet utilisateur. Petit malin.');
            return $this->redirectToRoute('user_profil');
        }else{
            $userRepository->remove($currentUser);
            $this->addFlash('success','Votre compte a bien été supprimé.');
            return $this->redirectToRoute('home');
        }
    }
}
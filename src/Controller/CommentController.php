<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/{slug}/add', name: 'comment_add')]
    public function add(Image $image, Request $request, EntityManagerInterface $manager): Response
    {
        $token = $request->request->get('token');
        if ($request->isMethod("POST") && $this->isCsrfTokenValid('comment-add', $token)){
            $content = $_POST["content"];
            $comment = new Commentaire();
            $comment->setUser($this->getUser());
            $comment->setImage($image);
            $comment->setContent($content);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');
            return $this->redirectToRoute('dashboard_comments', ["slug" => $image->getSlug()]);

        }
        return $this->render('comment/add.html.twig');
    }
}

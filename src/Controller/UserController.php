<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/register', name: 'user_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager, UserRepository $repository): Response
    {
        if ($request->isMethod("POST")){
            $token = $request->request->get('token');
            if ($this->isCsrfTokenValid('user-register', $token)){

                $firstName = $request->request->get('firstname');
                $lastName = $request->request->get('lastname');
                $email = $request->request->get('email');
                $plainPassword = $request->request->get('plainPassword');

                $newUser = new User();
                $newUser->setEmail($email);
                $newUser->setFirstname($firstName);
                $newUser->setLastname($lastName);
                $newUser->setPassword($passwordHasher->hashPassword($newUser, $plainPassword));

                if (!empty($repository->findBy(['email' => $email]))){
                    $this->addFlash('error', 'Adresse mail déjà utilisée.');
                    return $this->redirectToRoute('user_register');
                }else{
                    $manager->persist($newUser);
                    $manager->flush();
                    $this->addFlash('success', 'Votre compte a bien été créé, vous pouvez maintenant vous connecter.');
                    return $this->redirectToRoute('user_login');
                }
            }
        }
        return $this->render('user/register.html.twig');
    }

    #[Route('/login', name: 'user_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig',[
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'user_logout')]
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig',[
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'user_profil', methods: 'GET')]
    public function profil(){
        if (is_null($this->getUser())){
            $this->redirectToRoute('home');
        }

        dd($this->getUser());
    }
}

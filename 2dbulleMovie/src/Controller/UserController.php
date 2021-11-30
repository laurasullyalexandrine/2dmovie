<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'admin_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/user/new', name: 'admin_user_new', methods: ['GET'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
       $user = new User($userPasswordHasherInterface);
       $form = $this->createForm(UserType::class, $user);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $entityManager = $this->getDoctrine()->getManager();

            $rawPassword = $request->request->get('user')['password']['first'];

            if(!empty($rawPassword))
            {
                $user->setPassword($userPasswordHasherInterface->hashPassword($user, $user->getPassword($rawPassword)));
            }
          
           $entityManager->persist($user);
           $entityManager->flush();

           return $this->redirectToRoute('admin_user');
       }

       return $this->render('user/new.html.twig', [
           'user' => $user,
           'form' => $form->createView()
       ]);
    }


    #[Route('/admin/user/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasherInterface) : Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe en clair via la requete saisi par l'utilisateur (donc le formulaire complété)
            // Avec RepeatedType fait que le password est devenu un tableau alors il faut récupérer par l'info par la clé
            $rawPassword = $request->request->get('user')['password']['first'];

            if(!empty($rawPassword))
            {
                $user->setPassword($userPasswordHasherInterface->hashPassword($user, $user->getPassword($rawPassword)));
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user');
        }
         return $this->render('user/edit.html.twig', [
             'user' => $user,
             'form' => $form->createView()
         ]);
    }

    #[Route('/admin/user/delete/{id}', name: 'admin_user_delete', methods: ['GET'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user');
    }
}

<?php

namespace App\Controller\Back;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/admin/genre', name: 'admin_genre')]
    public function browse(GenreRepository $genreRepository): Response
    {
        $allGenre = $genreRepository->findBy([], ['name'=> 'ASC']);// Ici on range les genres pas ordre alpha
        //dd($allGenre);

        return $this->render('back/genre/browse.html.twig', [
            'genre_list' => $allGenre,
        ]);
    }

    #[Route('/admin/genre/new', name: 'admin_genre_add')]
    public function add(Request $request): Response
    {
        $genre = new Genre();
        // je crée un objet form type
        $form = $this->createForm(GenreType::class, $genre);
        // cette méthode va vérifier si un formulaire html a été soumis en post
        // et si ce formulaire concerne l'entité Genre
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // ici tout est ok, les champs sont valides et on peut continuer

            $genre = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            // on enregistre en bdd par exemple 
            $entityManager->persist($genre);
            $entityManager->flush();

            $this->addFlash('success', 'Genre `' . $genre->getName() . '` a bien été mis ajouté !');

            // puis on redirige
            return $this->redirectToRoute('admin_genre');
        }

        return $this->render('back/genre/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/genre/{id}', name: 'admin_genre_read', methods:['GET'])]
    public function read(Genre $genre): Response // Genre $genre est équivalent à $genre =new Genre:: class avec getDoc et getManger 
    {
       dump($genre);
        // ici on est sur de récup un objet car le param convert renvoit une 404 dans le cas contraire
        return $this->render('back/genre/read.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/admin/genre/edit/{id}', name: 'admin_genre_edit', methods: ['GET', 'POST'])]
    public function edit(Genre $genre, Request $request): Response
    {
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request); // il regarde dans la requête si il a des champs qui sont définit dans le form et vérifie également les contraintes

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager= $this->getDoctrine()->getManager();
            $genre->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();
            
            $this->addFlash('success', 'Genre `' . $genre->getName() . '` a bien été mis à jour  !');

            return $this->redirectToRoute('admin_genre');
        }

        return $this->render('back/genre/edit.html.twig', [
            'form' => $form->createView(),
            'genre' => $genre,
        ]);
    }

    #[Route('/admin/genre/delete/{id}', name: 'admin_genre_delete')]
    public function delete(Genre $genre, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($genre);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'Genre `' . $genre->getName() . '` a bien été supprimé !');

        return $this->redirectToRoute('admin_genre');
    }
}

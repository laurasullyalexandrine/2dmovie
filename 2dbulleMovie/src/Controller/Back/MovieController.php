<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/admin/movie', name: 'admin_movie')]
    public function browse(MovieRepository $movieRepository): Response
    {
        $allMovie = $movieRepository->findBy([], ['title' => 'ASC']);
        return $this->render('back/movie/browse.html.twig', [
            'movie_list' => $allMovie,
        ]);
    }

    #[Route('/admin/movie/new', name: 'admin_movie_add')]
    public function add(Request $request): Response
    {
        $movie = new Movie();
        // je crée un objet form type
        $form = $this->createForm(MovieType::class, $movie);
        // cette méthode va vérifier si un formulaire html a été soumis en post
        // et si ce formulaire concerne l'entité Movie
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // ici tout est ok, les champs sont valides et on peut continuer

            $movie = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            
            // on enregistre en bdd par exemple 
            $entityManager->persist($movie);
            $entityManager->flush();

            $this->addFlash('success', 'Movie `' . $movie->getTitle() . '` a bien été ajouté !');
            
            // puis on redirige
            return $this->redirectToRoute('admin_movie');
        }

        return $this->render('back/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/movie/{id}', name: 'admin_movie_read')]
    public function read(Movie $movie): Response
    {
        return $this->render('back/movie/read.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/admin/movie/edit/{id}', name: 'admin_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Movie $movie, Request $request): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $movie->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Movie `' . $movie->getTitle() . '` a bien été mis à jour !');

            return $this->redirectToRoute('admin_movie');
        }

        return $this->render('back/movie/edit.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }


    #[Route('/admin/movie/delete/{id}', name: 'admin_movie_delete', methods: ['GET'])]
    public function delete(Movie $movie, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($movie);

        $entityManagerInterface->flush();
        $this->addFlash('success', 'Movie`' . $movie->getTitle() . '` a bien été supprimé !');

        return $this->redirectToRoute('admin_movie');
    }
    
}

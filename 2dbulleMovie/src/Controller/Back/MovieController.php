<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Service\FileUploader;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/film', name:'admin_movie')]
    public function browse(MovieRepository $movieRepository): Response
    {
        $allMovie = $movieRepository->findBy([], ['title' => 'ASC']);
        return $this->render('back/movie/browse.html.twig', [
            'movie_list' => $allMovie,
        ]);
    }

    #[Route('/admin/film/nouveau', name:'admin_movie_add')]
    public function add(Request $request, FileUploader $fileUploader, Slugger $slugger): Response
    {
        $movie = new Movie();
        // je crée un objet form type et je le fait correspondre à son entité => Movie
        $form = $this->createForm(MovieType::class, $movie);

        /**
         * Dans les coulisses, cela utilise un objet NativeRequestHandler pour lire les 
         * données des superglobales PHP correctes (c'est $_POST-à- dire ou $_GET) en 
         * fonction de la méthode HTTP configurée sur le formulaire (POST est la valeur par défaut).
         */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($movie); // On demande à ajouter les nouvelles données Movie dans la BDD.
            $picture = $form->get('picture')->getData(); // On récupère les données soumises dans le champ picture.
            $fileUploader->moveMoviePicture($picture, $movie); // On les traite ensuite avec notre Service FileUploader.
            $slugger->SluggigyMovieName($movie);
            $this->entityManager->flush(); // On Pousse le tout dans la BDD.

            $this->addFlash('succes', 'Votre film a bien été ajouté au site !'); // Message si succès

            return $this->redirectToRoute('admin_movie'); // retour sur l'admininstration des films avec le nouveau film
        }
        else
        {
            $this->addFlash('danger', 'Votre film n\'a pas pu être ajouté !');
            return $this->render('back/movie/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    }

    #[Route('/admin/film/{slug}', name: 'admin_movie_slug', methods:['GET'])]
    public function read(Movie $movie, Slugger $slugger): Response
    {
        $movie = $slugger->SluggigyMovieName($movie);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_movie_slug', ['slug'=> $movie->getSlug()]);
        // récupérer une instance de movieRepository
        //$movie = $movieRepository->findOneWithGenre($id);
        //dd($movie);

        //return $this->render('back/movie/read.html.twig', [
            //'movie' => $movie,
        //]);
    }

    #[Route('/admin/film/{slug}', name:'admin_movie_slug', methods:['GET'])]
    public function showSlug(Movie $movie) : Response
    {
        return $this->render(
            'Back/movie/read.html.twig', 
            ['movie' => $movie
        ]);
    }

    #[Route('/admin/movie/edit/{id}', name:'admin_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Movie $movie, Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();// On va chercher Doctrine et dans Doctrine on va chercher l'entityManager

            $movie->setUpdatedAt(new \DateTimeImmutable());
            $picture = $form->get('picture')->getData();
            $fileUploader->moveMoviePicture($picture, $movie);
            $entityManager->flush();

            $this->addFlash('success', 'Le film `' . $movie->getTitle() . '` a bien été mis à jour !');

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
        $this->addFlash('success', 'Le film `' . $movie->getTitle() . '` a bien été supprimé !');

        return $this->redirectToRoute('admin_movie');
    }
}

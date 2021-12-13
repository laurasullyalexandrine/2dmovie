<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homepage(MovieRepository $movieRepository): Response
    {
        return $this->render('main/movie.html.twig', [
            'movie_list' => $movieRepository->findAllOrderedDQL(),
        ]);
    }

    #[Route('/movie/{slug}', name:'movie_show_slug', methods:['GET'])]
    public function show(Movie $movie, Slugger $slugger): Response
    {
        $movie = $slugger->SluggigyMovieName($movie);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('movie_show_slug', ['slug'=> $movie->getSlug()]);

        // récupérer une instance de movieRepository
        // $movie = $movieRepo->findOneWithGenre($id);
        // dump($movie);

        // return $this->render('main/show.html.twig', [
        //     'movie' => $movie,
        // ]);
    }

    #[Route('/movie/{slug}', name:'movie_show_slug', methods:['GET'])]
    public function showSlug(Movie $movie) : Response
    {
        return $this->render(
            'main/show.html.twig', 
            ['movie' => $movie
        ]);
    }

    #[Route('/mentions-legales', name: 'legal_mention')]
    public function legal():Response
    {
        return $this->render('main/legal_mention.html.twig');
    } 
}

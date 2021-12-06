<?php

namespace App\Controller\Front;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function list(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/movie.html.twig', [
            'movie_list' => $movieRepository->findAllOrderedDQL(),
        ]);
    }

    #[Route('/id', name:'movie_show', methods:['GET'])]
    public function show($id, MovieRepository $movieRepo): Response
    {
        // récupérer une instance de movieRepository
        $movie = $movieRepo->findOneWithGenre($id);
        dump($movie);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}

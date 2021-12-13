<?php   

namespace App\Service;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugger 
{
    private $em;
    private $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    /**
     * Fonction qui permet de slugger une chaine de caractère
     *
     * @param [type] $movieName
     * @return void
     */
    public function sluggify($string)
    {
        // Ici on sluggyfie et on met en minuscule une chaine de caractère
       return $this->slugger->slug(str_replace(" ", "_", $string));
    }

    /**
     * Fonction qui permet de sluggyfie le nom d'un film
     *
     * @param Movie $movie
     */
    public function SluggigyMovieName(Movie $movie)
    {
        // On commence par sluggyfié le titre de film
        $slugMovieName = $this->sluggify($movie->getTitle());

        // Pour gérer les homonymes, on décide de rajouter l'id à la fin du slug
        $slugMovieName = $slugMovieName . '-' . $movie->getId();

        // On demande à ce que le slug de base soit remplacé par le nouveau nom de film donc avec l'id du film en plus
        $movie->setSlug($slugMovieName);

        // On envoie le tout vers la BDD
        $this->em->flush();

        return $movie;
    }
}
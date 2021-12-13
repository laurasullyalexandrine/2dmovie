<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $movieDirectory;
    private $slugger;

    public function __construct($movieDirectory, SluggerInterface $slugger)
    {
        $this->movieDirectory = $movieDirectory;
        $this->slugger = $slugger;
    }


    public function manageMoviePicture(?UploadedFile $picture, string $targetDirectory): ?string
    {
        if($picture)
        {
            // Ici on récupère le chemin d'accès afin d'optenir le nom du fichier téléchargé
            $newPictureloader = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
            // Ici on transforme le nom du fichier téléchargé avec la méhtode slug() pour qu'il 
            // soit lisible par tous (humain robot navigateur) et on le sauvagarde.
            $safePictureName = $this->slugger->slug($newPictureloader);
            // Ici on crée le nom du fichier qui sera sauvegardé dans la BDD.
            $newPictureName = $safePictureName . '-' . uniqid() . '.' . $picture->guessExtension();
            // Pour finir on déplace le fichier téléchargé avec le nouveau nom dans le dossier 'uploads' du projet.
            // configurer dans le fichier service.yaml.
            $picture->move($targetDirectory, $newPictureName);
        }
        return $newPictureName;
    }

/**
 * Fonction qui gère le téléchargement du nouveau fichier dans le dossier 'uploads' du projet.
 *
 * @param UploadedFile|null $picture
 * @param Movie $movie
 * @return void
 */
    public function moveMoviePicture(?UploadedFile $picture, Movie $movie)
    {
        $pictureName = $this->manageMoviePicture($picture, $this->movieDirectory);
        if($pictureName !== null)
        {
            $movie->setPicture($pictureName);
        }
    }
}


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
            $newPictureloader = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
            $safePictureName = $this->slugger->slug($newPictureloader);
            $newPictureName = $safePictureName . '-' . uniqid() . '.' . $picture->guessExtension();

            $picture->move($targetDirectory, $newPictureName);
        }
        return $newPictureName;
    }

/**
 * Undocumented function
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


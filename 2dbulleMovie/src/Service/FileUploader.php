<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $movieDirectory;

    public function __construct($movieDirectory)
    {
        $this->movieDirectory = $movieDirectory;
    }

    /**
     * function to use dowload a picture for the movie
     *
     * @param UploadedFile|null $picture
     * @param string $targetDirectory
     * @param string $prefix
     * @return string|null
     */
    public function manageMoviePicture(?UploadedFile $picture, string $targetDirectory, $prefix = ''): ?string
    {
        $newFileloader = null;

        if($newFileloader !== null)
        {
            $newFileloader = $prefix . uniqid() . '.' . $picture->guessExtension();

            $picture->move($targetDirectory, $newFileloader);
        }
        return $newFileloader;
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
        $pictureName = $this->manageMoviePicture($picture, $this->movieDirectory, 'new-picture-');
        if($pictureName !== null)
        {
            $movie->setPicture($pictureName);
        }
    }
}


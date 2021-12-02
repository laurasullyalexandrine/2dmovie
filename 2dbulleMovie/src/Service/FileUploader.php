<?php

// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $pictureDirectory;
    private $slugger;

    public function __construct($pictureDirectory, SluggerInterface $slugger)
    {
        $this->pictureDirectory = $pictureDirectory;
        $this->slugger = $slugger;
    }

    /**
     * Function about to upload a file
     *
     * @param UploadedFile|null $picture on autorise le null si aucune image n'a été fournie
     * On ajoutera le chemin de notre dossier dans lequel les images seront stockées et 
     * un préfix pour reconnaître nos images
     * @return string|null
     */
    public function uploadPicture(UploadedFile $picture, string $pictureDirectory, $prefix = ''): ?string
    {
        // On initilise à null une variable qui prendra le nouveau nom du fichier qui sera stocké en BDD
        // $newPictureName = null;

        // On pause une contrainte de validité d'exécution à notre fonction
        if($picture !==null)
        {
            /** 
             * Alors on télécharge et le nouveau nom du fichier reçu avec
             * 1 - le préfix, 
             * 2 - le code propre à chaque image généré avec la méthod uniqid(),
             * 3 - et la méthod guessExtension pour laisser Symfony deviner 
             * la bonne extension en fonction du type MIME du fichier ; 
            */
            // $newPictureName = $prefix . uniqid() . '.' . $picture->guessExtension();

        $originalPictureName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
        $safePictureName = $this->slugger->slug($originalPictureName);
        $pictureName = $safePictureName.'-'.uniqid().'.'.$picture->guessExtension();

        try {
            $picture->move(
                $this->getPictureDirectory(), $pictureName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        }

        return $pictureName;
    }

    public function getPictureDirectory()
    {
        return $this->pictureDirectory;
    }


}


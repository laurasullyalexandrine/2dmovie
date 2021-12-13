<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null,
            [
                'label' => 'Titre du film : ',
                'constraints' => [
                    new NotBlank(['message' => 'Le champ "Movie" est vide'])
                ]
            ])

            ->add('genres', null, [
                'label' => 'Genres associés : ',
                'expanded' => true, // on passe à false si on ne veut pas afficher une liste de case à cocher
            ])

            ->add('castings', null, [
                'label' => 'Acteurs associés : ',
                'expanded' => true, // on passe à false si on ne veut pas afficher une liste de case à cocher
            ])

            ->add('picture', FileType::class,
            [
                'label' => 'Affiche du film : ',
                'mapped' => false,
                'required' => false,
                'constraints' => 
                [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypesMessage' => 'La taille maximum de l\'image ne doit pas dépasser 2 MO',
                    ])
                ]
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                'expanded' => false, // on passe à true si on veut afficher une liste de case à cocher
            ])

            ->add('image', null,
            [
                'label' => 'Affiche du film : '
            ])
                
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}

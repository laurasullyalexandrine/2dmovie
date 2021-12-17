<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personage', TextType::class,
            [
                'label' => 'Nom acteurs.trice : ' ,
                'constraints' => [
                    new NotBlank(['message' => 'Le champ "Casting" est vide'])
                ]
            ])
            ->add('creditOrder', IntegerType::class, 
            [
                'label' => 'Distribution : ',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 1]),
                ]
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('person')
            
            ->add('movie', null, [
                'label' => 'Titre du film : '
            ])

            //->add('send')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}

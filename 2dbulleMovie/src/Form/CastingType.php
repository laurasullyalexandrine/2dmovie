<?php

namespace App\Form;

use App\Entity\Casting;
use Symfony\Component\Form\AbstractType;
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
            ->add('role', TextType::class,
            [
                'label' => 'Nom du casting : ' ,
                'constraints' => [
                    new NotBlank(['message' => 'Le champ "Casting" est vide'])
                ]
            ])
            ->add('creditOrder', IntegerType::class, 
            [
                'label' => 'Crédit Order : ',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 1]),
                ]
            ])
            // ->add('createdAt')
            // ->add('updatedAt')

            ->add('movie', TextType::class,
            [
                'label' => 'Titre du film : '
            ])
            ->add('send', SubmitType::class,
            ['label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}

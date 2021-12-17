<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du personnage : ',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('castings', null, [
                'label' => 'Acteurs associés : ',
                'expanded' => true, // on passe à false si on ne veut pas afficher une liste de case à cocher
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}

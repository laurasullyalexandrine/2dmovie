<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
            [
                'label' => 'Email : ',
                'constraints' => [
                    new Email(),
                ]
            ])
            ->add('roles', ChoiceType::class,
            [
                'label' => 'Roles : ',
                'multiple' => true, // permet d'afficher plusiers choix
                'expanded' => true, // affiche des cases à cocher

                'choices' => [
                    'Adminstrateur' => 'ROLE_ADMIN', // clé => valeur
                    'Utilisateur' => 'ROLE_USER',
                ]
            ])
            ->add('password', RepeatedType::class, 
            [
                'label' => 'mot de passe : ',
                'type' => PasswordType::class,
                'invalid_message'=> 'Les mots de passes ne correspondent pas.',
                'required' => false,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Répétez le mot de passe : '],
                'mapped' => false, // composant Symfony ne va pas demander à renseigner le mot de passe lors de la modification d'un email
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

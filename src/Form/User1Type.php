<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('roles')
            ->add('reset_token')
            ->add('password')
            ->add('isVerified')
            ->add('city')
            ->add('gender')
            ->add('phoneNumber')
            ->add('birthdate')
            ->add('specialty')
            ->add('bloodType')
            ->add('height')
            ->add('weight')
            ->add('hospitalName')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

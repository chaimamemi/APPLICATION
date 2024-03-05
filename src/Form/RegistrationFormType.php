<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
            ])
            ->add('email', TextType::class, [
            ])

            // Inside your form builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Doctor' => 'ROLE_DOCTOR',
                    'Admin' => 'ROLE_ADMIN',
                    'family' => 'ROLE_FAMILY',
                    'Emergency' => 'ROLE_EMERGENCY_TEAM',
                    'Owner' => 'OWNER',


                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('city', TextType::class, [
            ])
            ->add('gender', TextType::class, [
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'html5' => true,
            ])
           
            ->add('register', SubmitType::class, [
                'label' => 'Create Account',
                'attr' => ['class' => 'btn btn-primary w-100'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

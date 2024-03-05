<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProfileType extends AbstractType
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
                'label' => 'Email',
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
            ])
            ->add('gender', TextType::class, [
                'label' => 'Gender',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('specialty', TextType::class, [
                'label' => 'Specialty',
            ])
            ->add('bloodType', TextType::class, [
                'label' => 'Blood Type',
            ])
            ->add('height', NumberType::class, [
                'label' => 'Height',
                'html5' => true,
                'attr' => ['step' => 'any'], // Allow float values
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Weight',
                'html5' => true,
                'attr' => ['step' => 'any'], // Allow float values
            ])
            ->add('hospitalName', TextType::class, [
                'label' => 'Hospital Name',
            ])
            ->add('update', SubmitType::class, [
                'label' => 'Update Account',
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

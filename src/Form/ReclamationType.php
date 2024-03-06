<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextareaType::class, [
                'label' => 'First Name',
                    'attr' => [ 
                                'placeholder' => 'add your First Name...',
                    ],] )
            ->add('prenom', TextareaType::class, [
                'label' => 'Last Name',
                    'attr' => [ 
                                'placeholder' => 'add your Last Name...',
                    ],] )
            ->add('email', TextareaType::class, [
                'label' => 'Email',
                    'attr' => [ 
                                'placeholder' => 'add your Email...',
                    ],] )
            ->add('tel', TextareaType::class, [
                'label' => 'PhoneNumber',
                    'attr' => [ 
                                'placeholder' => 'add your Phone Number...',
                    ],] )
            ->add('description', TextareaType::class, [
                'label' => 'description',
                    'attr' => [ 'rows' => 5, // nombre de lignes visibles
                                'placeholder' => 'add your description...',
                    ],] )
            ->add('date_reclamation')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}

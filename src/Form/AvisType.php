<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating' ,TextareaType::class, [
                'label' => 'rating',
                    'attr' => [ 
                                'placeholder' => 'add your rate from 0 to 5...',
                    ],])
            ->add('commentaire' ,  TextareaType::class, [
                'label' => 'commentaire',
                    'attr' => [ 
                                'placeholder' => 'add your comment...',
                    ],])
            ->add('titre' ,  TextareaType::class, [
                'label' => 'Titre',
                    'attr' => [ 
                                'placeholder' => 'add your title ...',
                    ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}

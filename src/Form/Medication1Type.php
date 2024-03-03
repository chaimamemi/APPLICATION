<?php

namespace App\Form;

use App\Entity\Medication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\BiologicalData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Medication1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Enter description',
                ],
            ])
            ->add('nameMedication', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Enter medication name',
                ],
            ])
            ->add('medicalNote', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Enter medical note',
                ],
            ])
            ->add('dosage', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Enter dosage',
                ],
            ])
            ->add('biologicalData', EntityType::class, [
                'class' => BiologicalData::class,
                'choice_label' => function (BiologicalData $biologicalData) {
                    return $biologicalData->getPatientName() . ' ' . $biologicalData->getPatientLastName();
                },
                'attr' => [
                    'class' => 'select2', // Ajoutez des classes CSS personnalisées si nécessaire
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medication::class,
        ]);
    }
}
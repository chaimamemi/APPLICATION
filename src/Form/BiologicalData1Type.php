<?php

namespace App\Form;

use App\Entity\BiologicalData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiologicalData1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timestamp')
            ->add('measurementType')
            ->add('value')
            ->add('patientName')
            ->add('PatientLastName')
            ->add('patientAge')
            ->add('patient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BiologicalData::class,
        ]);
    }
}

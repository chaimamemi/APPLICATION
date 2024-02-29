<?php

namespace App\Form;

use App\Entity\Bracelet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BraceletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('temperature', null, [
                'disabled' => true, // Le champ temperature est en lecture seule
            ])
            ->add('bloodPressure', null, [
                'disabled' => true, // Le champ bloodPressure est en lecture seule
            ])
            ->add('heartRate', null, [
                'disabled' => true, // Le champ heartRate est en lecture seule
            ])
            ->add('movement', null, [
                'disabled' => true, // Le champ movement est en lecture seule
            ])
            ->add('gps', null, [
                'disabled' => true, // Le champ gps est en lecture seule
            ])
            ->add('latitude', null, [
                'disabled' => true, // Le champ latitude est en lecture seule
            ])
            ->add('longitude', null, [
                'disabled' => true, // Le champ longitude est en lecture seule
            ])
            ->add('biologicalData') // Le champ biologicalData est modifiable
        ;
   
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bracelet::class,
        ]);
    }
}

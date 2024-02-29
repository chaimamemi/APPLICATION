<?php

namespace App\Form;

use App\Entity\HealthProfessional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class HealthProfessionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('healthRole', ChoiceType::class, [
                'choices' => [
                    'Doctors' => 'Doctors',
                    'Emergency Team' => 'EmergencyTeam',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'required' => true,
            ])
    
            ->add('specialty', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('dashboardType')
            ->add('hospital');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HealthProfessional::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Hospital;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\HealthProfessional;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\BiologicalData;

class HospitalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The name cannot be blank.']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'The name cannot be longer than {{ limit }} characters.']),
                ],
                'attr' => [
                    'placeholder' => 'Enter hospital name',
                    'required' => false,
                ],
            ])
            ->add('address', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The address cannot be blank.']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'The address cannot be longer than {{ limit }} characters.']),
                ],
                'attr' => [
                    'placeholder' => 'Enter hospital address',
                    
                ],
                'required' => false,
            ])
            ->add('latitude', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The latitude cannot be blank.']),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The latitude must be a numeric value.']),
                ],
                'attr' => [
                    'placeholder' => 'Enter latitude',
                ],
                'required' => false,
            ])
            ->add('longitude', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The longitude cannot be blank.']),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The longitude must be a numeric value.']),
                ],
                'attr' => [
                    'placeholder' => 'Enter longitude',
                ],
                'required' => false,
            ])
            ;
        }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hospital::class,
        ]);
    }
}

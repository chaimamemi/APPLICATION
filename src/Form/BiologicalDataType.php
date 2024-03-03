<?php

namespace App\Form;

use App\Entity\BiologicalData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert; // Importer la classe Assert 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Bracelet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BiologicalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder

   ->add('timestamp', DateTimeType::class, [
        'date_widget' => 'single_text'
    ])
        ->add('measurementType', null, [
            'attr' => [
                'placeholder' => 'e.g. Measurement Type',
            ],
        ])
        ->add('value', null, [
            'attr' => [
                'placeholder' => 'e.g. Value',
            ],
        ])
        ->add('patientName', TextType::class, [
            'label' => 'Patient Name',
            'required' => true,
            'attr' => [
                'placeholder' => 'e.g. John',
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255]),
            ],
        ])
        ->add('patientLastName', TextType::class, [
            'label' => 'Patient Last Name',
            'required' => true,
            'attr' => [
                'placeholder' => 'e.g. Doe',
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255]),
            ],
        ])
        ->add('patientAge', IntegerType::class, [
            'label' => 'Patient Age',
            'required' => true,
            'attr' => [
                'placeholder' => 'e.g. 30',
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\PositiveOrZero(),
            ],
        ])
       
        ->add('disease', TextareaType::class, [
            'label' => 'Disease',
            'required' => false,
            'attr' => [
                'placeholder' => 'e.g. Disease',
            ],
        ])

        ->add('bracelet', EntityType::class, [
            'class' => Bracelet::class,
            'placeholder' => 'Select a bracelet',
            'required' => false, // Facultatif selon votre logique métier
            // Autres options si nécessaire...
        ])


        ->add('otherInformation', TextareaType::class, [
            'label' => 'Other Information',
            'required' => false,
            'attr' => [
                'placeholder' => 'e.g. Additional information',
            ],
        ])


        ->add('hospital');
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BiologicalData::class,
            'bracelets' => [], // Définir une valeur par défaut pour l'option 'bracelets'
        ]);
    }
}

        


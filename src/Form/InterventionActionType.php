<?php

namespace App\Form;

use App\Entity\InterventionAction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;


class InterventionActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.role = :role')
                        ->setParameter('role', 'ROLE_OWNER');
                },
                'placeholder' => 'example@example.com',
                'invalid_message' => '', // Supprimer complètement le message de validation par défaut
                'required' => false,
                
            ])
            ->add('emergencyTeam', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.role = :role')
                        ->setParameter('role', 'ROLE_EMERGENCY_TEAM');
                },
                'placeholder' => 'example@example.com',
                'invalid_message' => 'Please select an emergency team member.', // Message de validation personnalisé
                'required' => false,
            ])
            ->add('action', ChoiceType::class, [
                'choices' => [
                    'Temperature control' => 'Temperature control',
                    'Blood pressure monitoring' => 'Blood pressure monitoring',
                    'Heart rate check' => 'Heart rate check',
                    'Respiratory rate analysis' => 'Respiratory rate analysis',
                    'Oxygen saturation check' => 'Oxygen saturation check',
                    'Neurological assessment' => 'Neurological assessment',
                    'Cardiopulmonary resuscitation (CPR)' => 'Cardiopulmonary resuscitation (CPR)',
                    'Referral to emergency services' => 'Referral to emergency services',
                    'Electrocardiogram (ECG) testing' => 'Electrocardiogram (ECG) testing',
                    'Other' => 'Other',
                ],
                'required' => false,
                'placeholder' => 'Click to choose',
                'invalid_message' => 'Please select an action.', // Message de validation personnalisé
                'required' => false,
            ])
            ->add('otherAction', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Specify other action',
                    'example' => 'Please provide additional details if you selected "Other".',
                ],
                'invalid_message' => 'Please provide a valid action description.', // Message de validation personnalisé
                'required' => false,
            ])
            ->add('dateTime', DateTimeType::class, [
                
                'date_widget' => 'single_text',
                'invalid_message' => 'Please provide a date', 
            ]);
    }
}
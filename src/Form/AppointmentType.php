<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateTime', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description (Please provide  age, sex, disease, problem, etc.)',
                'required' => true,
            ]);

        // Ajouter le champ 'patient' si la clé 'patients' est définie dans les options
        if (isset($options['patients'])) {
            $builder->add('patient', EntityType::class, [
                'class' => User::class,
                'choices' => $options['patients'],
                'choice_label' => 'username',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :roles')
                        ->setParameter('roles', '%"ROLE_PATIENT"%');
                },
            ]);
        }

        $builder->add('doctor', EntityType::class, [
            'class' => User::class,
            'choices' => $options['doctors'],
            'choice_label' => 'username',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.roles LIKE :roles')
                    ->setParameter('roles', '%"ROLE_DOCTOR"%');
            },
        ]);

        // Ajouter le champ 'status' si l'utilisateur connecté est un médecin
        if (in_array('ROLE_DOCTOR', $options['user_roles'], true)) {
            $builder->add('status', ChoiceType::class, [
                'choices' => [
                    'Accepté' => 'accepted',
                    'Rejeté' => 'rejected',
                ],
                'label' => 'Statut du rendez-vous',
                'expanded' => true,
                'multiple' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configuration par défaut des options
            'data_class' => Appointment::class,
            'doctors' => [], // Liste des médecins
            'patients' => [], // Liste des patients
            'user_roles' => [], // Rôles de l'utilisateur connecté
            'is_doctor' => false, // Défaut à false pour éviter les erreurs
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateTime')
            ->add('description')
            ->add('patientUserId')
            ->add('patientUserId', EntityType::class, [ // Utilisez EntityType pour représenter la relation ManyToOne avec User
                'class' => User::class, // Spécifiez l'entité cible
                'choice_label' => 'username', // Spécifiez le champ de l'entité à afficher dans le formulaire
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'pending',
                    'Accepted' => 'accepted',
                    'Rejected' => 'rejected',
                ],
            ]);
            

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}

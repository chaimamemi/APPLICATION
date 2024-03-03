<?php
// src/Controller/HealthProfessionalController.php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthProfessionalController extends AbstractController
{
    #[Route('/healthprofessional/doctor-dashboard', name: 'app_health_professional_doctor_dashboard')]
    public function doctorDashboard(Request $request, AppointmentRepository $appointmentRepository): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté et a le rôle "ROLE_DOCTOR"
        if (!$user || !in_array('ROLE_DOCTOR', $user->getRoles(), true)) {
            throw $this->createAccessDeniedException('You are not authorized to access this page.');
        }

        // Récupérer les rendez-vous associés à ce médecin
        $doctorAppointments = $appointmentRepository->findBy(['doctor' => $user]);

        return $this->render('health_professional/doctor_dashboard.html.twig', [
            'appointments' => $doctorAppointments,
        ]);
    }

    #[Route('/confirm-appointment/{id}', name: 'confirm_appointment')]
    public function confirmAppointment(Request $request, Appointment $appointment): Response
    {
        // Vérifier si l'utilisateur est autorisé à confirmer le rendez-vous
        // Ici, vous pouvez implémenter votre logique de vérification
        // Par exemple, vérifier si l'utilisateur connecté est le même que le médecin associé à l'appointment
        
        // Marquer le rendez-vous comme confirmé
        $appointment->setStatus('accepted');
        $this->getDoctrine()->getManager()->flush();

        // Rediriger vers le tableau de bord du médecin
        return $this->redirectToRoute('app_health_professional_doctor_dashboard');
    }

    #[Route('/reject-appointment/{id}', name: 'reject_appointment')]
    public function rejectAppointment(Request $request, Appointment $appointment): Response
    {
        // Vérifier si l'utilisateur est autorisé à rejeter le rendez-vous
        // Implémentez votre logique de vérification ici
        
        // Marquer le rendez-vous comme rejeté
        $appointment->setStatus('rejected');
        $this->getDoctrine()->getManager()->flush();

        // Rediriger vers le tableau de bord du médecin
        return $this->redirectToRoute('app_health_professional_doctor_dashboard');
    }





















    #[Route('/healthprofessional/emergency-dashboard', name: 'app_health_professional_emergency_dashboard')]
    public function emergencyDashboard(): Response
    {
        // Logique pour le tableau de bord de l'équipe d'urgence
        return $this->render('health_professional/emergency_dashboard.html.twig');
    }
}



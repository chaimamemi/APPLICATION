<?php
// src/Controller/HealthProfessionalController.php
// src/Controller/HealthProfessionalController.php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MedicationRepository;
use App\Repository\BiologicalDataRepository;
use App\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;
use App\Entity\Alert;
use App\Entity\Bracelet;
use App\Entity\InterventionAction;
use App\Form\InterventionActionType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Repository\UserRepository;
use App\Repository\BraceletRepository;
use App\Repository\AlertRepository;
use Doctrine\ORM\EntityManagerInterface;

class HealthProfessionalController extends AbstractController
{
    #[Route('/healthprofessional/doctor-dashboard', name: 'app_health_professional_doctor_dashboard')]
    public function doctorDashboard(Request $request, AppointmentRepository $appointmentRepository, MedicationRepository $medicationRepository, BiologicalDataRepository $biologicalDataRepository): Response
    {
        $user = $this->getUser();
    
        // Vérifier si l'utilisateur est connecté et a le rôle "ROLE_DOCTOR"
        if (!$user || !in_array('ROLE_DOCTOR', $user->getRoles(), true)) {
            throw $this->createAccessDeniedException('You are not authorized to access this page.');
        }
    
        // Récupérer les rendez-vous associés à ce médecin
        $doctorAppointments = $appointmentRepository->findBy(['doctor' => $user]);
    
        // Récupérer la liste des médicaments
        $medications = $medicationRepository->findAll();
        
        // Récupérer les données biologiques associées à ce médecin
        $biologicalDatas = $biologicalDataRepository->findAll();


        return $this->render('health_professional/doctor_dashboard.html.twig', [
            'appointments' => $doctorAppointments,
            'medications' => $medications, // Passer la liste des médicaments au modèle Twig
            'biological_datas' => $biologicalDatas, // Passer la liste des données biologiques au modèle Twig
        ]);
    }

    


    #[Route('/confirm-appointment/{id}', name: 'confirm_appointment')]
    public function confirmAppointment(Request $request, Appointment $appointment): Response
    {
        // Marquer le rendez-vous comme confirmé
        $appointment->setStatus('accepted');
        $this->getDoctrine()->getManager()->flush();

        // Rediriger vers la vue du calendrier pour afficher la date du rendez-vous
        return $this->redirectToRoute('app_doctor_calendar', ['id' => $appointment->getId()]);
    }

    #[Route('/reject-appointment/{id}', name: 'reject_appointment')]
    public function rejectAppointment(Request $request, Appointment $appointment): Response
    {
        // Marquer le rendez-vous comme rejeté
        $appointment->setStatus('rejected');
        $this->getDoctrine()->getManager()->flush();

        // Rediriger vers le tableau de bord du médecin
        return $this->redirectToRoute('app_health_professional_doctor_dashboard');
    }

    

    #[Route('/delete-appointment/{id}', name: 'delete_appointment', methods: ['POST'])]
    public function deleteAppointment(Request $request, Appointment $appointment): RedirectResponse
    {
        // Supprimer la demande de rendez-vous de la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($appointment);
        $entityManager->flush();

        // Rediriger vers le tableau de bord du médecin
        return $this->redirectToRoute('app_health_professional_doctor_dashboard');
    }




    #[Route('/doctor/calendar', name: 'app_doctor_calendar')]
    public function doctorCalendar(CalendarRepository $calendarRepository): Response
    {
        // Logique pour récupérer les calendriers des médecins
        $calendars = $calendarRepository->findAll();
        $rdvs = [];
    
        foreach ($calendars as $calendar) {
            $rdvs[] = [
                'id' => $calendar->getId(),
                'start' => $calendar->getStart()->format('Y-m-d H:i:s'),
                'end' => $calendar->getEnd()->format('Y-m-d H:i:s'),
                'title' => $calendar->getTitle(),
                'description' => $calendar->getDescription(),
                'backgroundColor' => $calendar->getBackgroundColor(),
                'borderColor' => $calendar->getBorderColor(),
                'textColor' => $calendar->getTextColor(),
                'allDay' => $calendar->getAllDay(),
            ];
        }
    
        $data = json_encode($rdvs);
    
        return $this->render('health_professional/doctor_calendar.html.twig', [
            'data' => $data,
        ]);
    }






    #[Route('/doctor/statistical', name: 'app_doctor_statistical')]
    public function statistical(BiologicalDataRepository $biologicalDataRepository): Response
    {
        // Récupérer les données biologiques pertinentes
        $biologicalData = $biologicalDataRepository->findAll();
        
        // Initialiser les tableaux pour stocker les données pour le graphique
        $withBracelet = 0;
        $withoutBracelet = 0;
    
        // Analyser les données biologiques pour compter les patients avec et sans bracelet
        foreach ($biologicalData as $data) {
            if ($data->getBracelet() !== null) {
                $withBracelet++;
            } else {
                $withoutBracelet++;
            }
        }
    
        // Convertir les données en format JSON pour l'affichage dans le graphique
        $data = [
            'labels' => ['Patients with Bracelet', 'Patients without Bracelet'],
            'datasets' => [
                [
                    'label' => 'Number of Patients',
                    'backgroundColor' => ['#36a2eb', '#ff6384'],
                    'data' => [$withBracelet, $withoutBracelet]
                ]
            ]
        ];
    
        return $this->render('health_professional/stats.html.twig', [
            'data' => json_encode($data),
        ]);
    }
    



    #[Route('/patient-with-bracelet', name: 'app_patient_with_bracelet')]
    public function patientsWithBracelet(BiologicalDataRepository $biologicalDataRepository): Response
    {
        // Récupérer les données biologiques des patients avec bracelet
        $patientsWithBracelet = $biologicalDataRepository->findBy(['bracelet' => true]);
    
        return $this->render('health_professional/patients_with_bracelet.html.twig', [
            'patients' => $patientsWithBracelet,
        ]);
    }
    
    #[Route('/patient-without-bracelet', name: 'app_patient_without_bracelet')]
    public function patientsWithoutBracelet(BiologicalDataRepository $biologicalDataRepository): Response
    {
        // Récupérer les données biologiques de tous les patients sans bracelet
        $patientsWithoutBracelet = $biologicalDataRepository->findBy(['bracelet' => null]);
    
        return $this->render('health_professional/patients_without_bracelet.html.twig', [
            'patients' => $patientsWithoutBracelet,
        ]);
    }
    




    #[Route('/healthprofessional/emergency-dashboard', name: 'app_health_professional_emergency_dashboard')]
    public function emergencyDashboard(Request $request, UserRepository $userRepository, BraceletRepository $braceletRepository, AlertRepository $alertRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array('ROLE_EMERGENCY_TEAM', $user->getRoles())) {
            throw $this->createAccessDeniedException('You are not authorized to access this page.');
        }

        // Fetch necessary information for the dashboard
        $owners = $userRepository->findBy(['role' => 'ROLE_OWNER']);
        $bracelets = $braceletRepository->findAll();
        $alerts = $alertRepository->findAll();

        // Here, you could add your logic to detect new alerts if available
        $newAlerts = $alertRepository->findNewAlerts();

        // Display a notification for each new alert
        if ($newAlerts) {
            foreach ($newAlerts as $alert) {
                $this->addFlash('info', 'New alert detected: ' . $alert->getDescription());
                // Optionally update the alert as being seen or handled if necessary
            }
            $entityManager->flush(); // Save all changes in the database
        }

        // Handle intervention (example, adapt as per your needs)
        $interventionAction = new InterventionAction();
        $form = $this->createForm(InterventionActionType::class, $interventionAction);

        return $this->render('health_professional/emergency_dashboard.html.twig', [
            'owners' => $owners,
            'bracelets' => $bracelets,
            'alerts' => $alerts,
            'form' => $form->createView(),
        ]);
    }
}
<?php

// src/Controller/OwnerController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Bracelet;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\UserRepository; 
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\NotifierInterface;
use App\Repository\AppointmentRepository;
use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Entity\BiologicalData;

use App\Repository\CalendarRepository;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OwnerController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    #[Route('/owner', name: 'app_owner_dashboard')]
    public function index(Request $request, Security $security, AppointmentRepository $appointmentRepository): Response
    {
        // Récupérer l'utilisateur connecté
        /** @var User $user */
        $user = $security->getUser();
    
        // Vérifier si l'utilisateur est connecté et a le rôle "owner"
        if (!$user || !in_array('ROLE_OWNER', $user->getRoles(), true)) {
            throw $this->createAccessDeniedException('You are not authorized to access this page.');
        }
    
        // Récupérer les informations de l'utilisateur
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $email = $user->getEmail();
    
        // Récupérer les données du bracelet depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $braceletRepository = $entityManager->getRepository(Bracelet::class);
        $braceletData = $braceletRepository->findAll(); // Ou utilisez une méthode spécifique pour récupérer les données
    
        // Récupérer les rendez-vous de l'utilisateur
        $appointments = $appointmentRepository->findBy(['patient' => $user]);
    
        // Génération du code aléatoire
        $code = uniqid();
        $this->session->set('owner_access_code', $code); // Utilisation de $this->session ici
    
        return $this->render('owner/app_owner_dashboard.html.twig', [
            'braceletData' => $braceletData,
            'code' => $code,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'appointments' => $appointments,
        ]);
    }
    


    #[Route('/owner/calendar', name: 'app_owner_calendar')]
    public function ownerCalendar(CalendarRepository $calendarRepository): Response
    {
        // Logique pour récupérer les calendriers des propriétaires
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
    
        return $this->render('owner/owner_calendar.html.twig', [
            'data' => $data,
        ]);
    }
    




    #[Route('/create-appointment', name: 'create_appointment')]
    public function createAppointment(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user || !in_array('ROLE_OWNER', $user->getRoles(), true)) {
            throw $this->createAccessDeniedException('You are not authorized to access this page.');
        }

        $appointment = new Appointment();

        $doctors = $userRepository->findByRole('ROLE_DOCTOR');

        $form = $this->createForm(AppointmentType::class, $appointment, [
            'doctors' => $doctors,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le champ "Patient" est présent dans la requête
            if ($form->has('patient')) {
                // Si le champ "Patient" est présent, définissez l'utilisateur connecté comme le patient du rendez-vous
                $appointment->setPatient($user);
            }

            // Persistez normalement
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_owner_dashboard');
        }

        return $this->render('owner/create_appointment.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit-appointment/{id}', name: 'edit_appointment')]
    public function editAppointment(Request $request, Appointment $appointment, UserRepository $userRepository): Response
    {
        // Vérifier si l'utilisateur est le propriétaire de l'appointment
        if ($appointment->getPatient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not authorized to edit this appointment.');
        }
    
        $doctors = $userRepository->findByRole('ROLE_DOCTOR');
    
        $form = $this->createForm(AppointmentType::class, $appointment, [
            'doctors' => $doctors,
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez normalement
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('app_owner_dashboard');
        }
    
        return $this->render('owner/edit_appointment.html.twig', [
            'form' => $form->createView(),
            'appointment' => $appointment, // Assurez-vous que cette ligne passe l'objet $appointment au modèle
        ]);
    }

    #[Route('/delete-appointment/{id}', name: 'delete_appointment')]
    public function deleteAppointment(Request $request, Appointment $appointment): Response
    {
        // Vérifier si l'utilisateur est le propriétaire de l'appointment
        if ($appointment->getPatient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not authorized to delete this appointment.');
        }

        // Supprimer l'appointment
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($appointment);
        $entityManager->flush();

        $this->addFlash('success', 'Appointment deleted successfully.');

        return $this->redirectToRoute('app_owner_dashboard');
    }
 

 
 #[Route('/check-appointments', name: 'check_appointments')]
    public function checkAppointments(AppointmentRepository $appointmentRepository, SessionInterface $session): Response
    {
        $appointments = $appointmentRepository->findAll();
    
        foreach ($appointments as $appointment) {
            $doctorFullName = $appointment->getDoctor()->getFirstName() . ' ' . $appointment->getDoctor()->getLastName();
            if ($appointment->getStatus() === 'accepted') {
                $this->addFlash('success', "Your appointment with doctor $doctorFullName is accepted :ID: {$appointment->getId()}");
            } elseif ($appointment->getStatus() === 'rejected') {
                $this->addFlash('error', "Your appointment with doctor $doctorFullName is rejected. Try another date. ID: {$appointment->getId()}");
            }
        }
    
        return $this->redirectToRoute('app_owner_dashboard');
    }

    
    #[Route('/delete-notification/{id}', name: 'delete_notification')]
public function deleteNotification(Appointment $appointment): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($appointment);
    $entityManager->flush();

    $this->addFlash('success', 'Notification deleted successfully.');

    return $this->redirectToRoute('app_owner_dashboard');
}



public function uploadOwnerImage(Request $request): Response
{
    // Récupérer le chemin temporaire de l'image téléchargée
    $imageFile = $request->files->get('owner_image');
    $imagePath = $imageFile->getRealPath();

    return $this->render('owner/app_owner_dashboard.html.twig', [
        'imagePath' => $imagePath,
    ]);
}


}
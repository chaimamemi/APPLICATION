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


class OwnerController extends AbstractController
{
    #[Route('/owner', name: 'app_owner_dashboard')]
    public function index(Request $request, Security $security): Response
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

        // Génération du code d'accès
        $code = $request->getSession()->get('access_code');
        if (!$code) {
            $code = $this->generateNewCode();
            $request->getSession()->set('access_code', $code);
        }

        return $this->render('owner/app_owner_dashboard.html.twig', [
            'braceletData' => $braceletData,
            'code' => $code,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ]);
    }

    private function generateNewCode(): string
    {
        return uniqid();
    }

    #[Route('/generate-code', name: 'app_generate_code')]
    public function generateCode(Request $request): JsonResponse
    {
        $newCode = $this->generateNewCode();
        $request->getSession()->set('access_code', $newCode);

        return $this->json($newCode);
    }

    #[Route('/send-code', name: 'app_send_code')]
    public function sendCode(Request $request, MailerInterface $mailer): Response
    {
        // Récupérer l'adresse e-mail saisie par l'utilisateur depuis la requête
        $emailRecipient = $request->request->get('emailRecipient');
    
        // Vérifier si l'adresse e-mail est vide ou non valide
        if (empty($emailRecipient) || !filter_var($emailRecipient, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'Invalid email address.');
            return $this->redirectToRoute('app_owner_dashboard');
        }
    
        // Générer le code d'accès
        $code = $this->generateNewCode();
    
        // Envoyer le code par e-mail
        $email = (new Email())
            ->from('chaima.mami@esprit.tn') // Utilisez votre adresse e-mail ici
            ->to($emailRecipient)
            ->subject('Your access code')
            ->text('Your access code: ' . $code);
    
        $mailer->send($email);
    
        $this->addFlash('success', 'Email sent successfully.');
    
        return $this->redirectToRoute('app_owner_dashboard');
    }

    public function createAppointment(Request $request): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Initialisez le statut à "pending"
            $appointment->setStatus('pending');
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();
    
            // Rediriger ou notifier l'utilisateur Owner
        }
    
        return $this->render('owner/create_appointment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

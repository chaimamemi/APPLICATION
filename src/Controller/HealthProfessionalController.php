<?php
// HealthProfessionalController.php

namespace App\Controller;

use App\Form\DashboardTypeFormType; // Importer la classe DashboardTypeFormType
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthProfessionalController extends AbstractController
{
  

    #[Route('/healthprofessional/doctor-dashboard', name: 'app_health_professional_doctor_dashboard')]
    public function doctorDashboard(): Response
    {
        // Logique pour le tableau de bord du médecin
        return $this->render('health_professional/doctor_dashboard.html.twig');
    }


    
    #[Route('/healthprofessional/emergency-dashboard', name: 'app_health_professional_emergency_dashboard')]
    public function emergencyDashboard(): Response
    {
        // Logique pour le tableau de bord de l'équipe d'urgence
        return $this->render('health_professional/emergency_dashboard.html.twig');
    }



   
}

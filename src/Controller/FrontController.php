<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;




use App\Repository\TypeRepository;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('front/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
   
  
}

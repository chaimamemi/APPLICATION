<?php
// src/Controller/BraceletController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bracelet;
use App\Form\BraceletType;

class BraceletController extends AbstractController
{
    #[Route('/bracelet', name: 'app_bracelet')]
    public function index(Request $request): Response
    {
        // Récupérer tous les bracelets depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $bracelets = $entityManager->getRepository(Bracelet::class)->findAll();
    
        // Création d'un tableau associatif pour stocker les formulaires de chaque bracelet
        $braceletForms = [];
        foreach ($bracelets as $bracelet) {
            // Création du formulaire et liaison avec l'objet Bracelet
            $form = $this->createForm(BraceletType::class, $bracelet);
    
            // Stocker le formulaire dans le tableau associatif avec l'ID du bracelet comme clé
            $braceletForms[$bracelet->getId()] = $form->createView();
        }
    
        // Vérifier si le bouton "Show Information" a été cliqué
        if ($request->request->has('showInformation')) {
            // Récupérer l'ID du bracelet sélectionné dans le formulaire
            $selectedBraceletId = $request->request->get('braceletId');
            // Rediriger vers la page d'informations du bracelet avec l'ID sélectionné
            return $this->redirectToRoute('bracelet_information', ['id' => $selectedBraceletId]);
        }
    
        // Affichage des bracelets dans le template
        return $this->render('bracelet/information.html.twig', [
            'bracelets' => $bracelets, // Passer la variable "bracelets" au template Twig
        ]);
    }

}

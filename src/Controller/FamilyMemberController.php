<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bracelet;

class FamilyMemberController extends AbstractController
{
    #[Route('/familymember', name: 'app_family_member_dashboard')]
    public function index(Request $request): Response
    {
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer le code d'accès saisi par le family member
            $accessCode = $request->request->get('access_code');

            // Récupérer le code d'accès généré dans le contrôleur OwnerController
            $ownerAccessCode = $this->get('session')->get('access_code');

            // Vérifier si les codes d'accès correspondent
            if ($accessCode === $ownerAccessCode) {
                // Les codes d'accès correspondent, afficher les données du dashboard de l'owner
                // Récupérer les données du bracelet de l'owner depuis la base de données ou toute autre source
                $entityManager = $this->getDoctrine()->getManager();
                $braceletRepository = $entityManager->getRepository(Bracelet::class);
                $braceletData = $braceletRepository->findAll(); // Ou utilisez une méthode spécifique pour récupérer les données

                // Afficher les données du dashboard de l'owner dans le dashboard du family member
                return $this->render('family_member/app_family_member_dashboard.html.twig', [
                    'braceletData' => $braceletData,
                    'ownerAccessCode' => $ownerAccessCode, // Vous pouvez également transmettre d'autres données si nécessaire
                ]);
            } else {
                // Les codes d'accès ne correspondent pas, afficher un message d'erreur
                return $this->render('family_member/access_denied.html.twig', [
                    'error_message' => 'Invalid access code. Please try again.',
                ]);
            }
        }

        // Affichage du formulaire de saisie du code pour le family member
        return $this->render('family_member/app_family_member_dashboard.html.twig');
    }
}
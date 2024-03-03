<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Log\LoggerInterface;

class FamilyMemberController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/familymember', name: 'app_family_member_dashboard')]
    public function index(Request $request, SessionInterface $session): Response
    {
        // Récupérer le code d'accès stocké dans la session
        $ownerAccessCode = $session->get('access_code');

        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer le code d'accès saisi par le membre de la famille
            $accessCode = $request->request->get('access_code');

            // Log des valeurs des codes pour débogage
            $this->logger->info('Access Code from form: ' . $accessCode);
            $this->logger->info('Owner Access Code from session: ' . $ownerAccessCode);

            // Vérifier si les codes d'accès correspondent
            if ($accessCode === $ownerAccessCode) {
                // Les codes d'accès correspondent, afficher le dashboard du membre de la famille
                return $this->redirectToRoute('family_member_dashboard');
            } else {
                // Les codes d'accès ne correspondent pas, afficher un message d'erreur
                return $this->render('family_member/access_denied.html.twig', [
                    'error_message' => 'Invalid access code. Please try again.',
                ]);
            }
        }

        // Affichage du formulaire de saisie du code pour le membre de la famille
        return $this->render('family_member/access_code_form.html.twig', [
            'accessCodeFromSession' => $ownerAccessCode,
        ]);
    }
}


<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Bracelet;
use Symfony\Component\Security\Core\Security;

class FamilyMemberController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    #[Route('/familymember', name: 'app_family_member_dashboard')]
    public function index(Request $request, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        /** @var User $user */
        $user = $security->getUser();

        // Vérifier si l'utilisateur est connecté et a le rôle "family member"
        if (!$user || !in_array('ROLE_FAMILY_MEMBER', $user->getRoles(), true)) {
            throw $this->createAccessDeniedException('You are not authorized to access this page.');
        }

        // Récupérer les informations de l'utilisateur
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();

        // Récupérer les données du bracelet depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $braceletRepository = $entityManager->getRepository(Bracelet::class);
        $braceletData = $braceletRepository->findAll(); // Ou utilisez une méthode spécifique pour récupérer les données

        // Message personnalisé pour le membre de la famille
        $dashboardContent = "Hello dear family member $firstName $lastName, here is the bracelet data for your beloved one. Take care.";

        return $this->render('family_member/app_family_member_dashboard.html.twig', [
            'braceletData' => $braceletData,
            'dashboardContent' => $dashboardContent,
        ]);
    }
}

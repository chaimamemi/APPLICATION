<?php
// AdminUserController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AdminUserController extends AbstractController
{
    #[Route('/adminusers', name: 'admin_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        
        // Lire tous les utilisateurs de la base de données
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/adminusers/{id}/delete', name: 'admin_user_delete')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Redirection vers la liste des utilisateurs après suppression
        return $this->redirectToRoute('admin_users');
    }
}

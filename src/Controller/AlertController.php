<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Form\AlertType;
use App\Repository\AlertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/alert')]
class AlertController extends AbstractController
{
    #[Route('/', name: 'app_alert_index', methods: ['GET'])]
    public function index(AlertRepository $alertRepository): Response
    {
        return $this->render('alert/index.html.twig', [
            'alerts' => $alertRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_alert_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $alert = new Alert();
        $form = $this->createForm(AlertType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($alert);
            $entityManager->flush();

            return $this->redirectToRoute('app_alert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alert/new.html.twig', [
            'alert' => $alert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_alert_show', methods: ['GET'])]
    public function show(Alert $alert): Response
    {
        return $this->render('alert/show.html.twig', [
            'alert' => $alert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_alert_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alert $alert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlertType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_alert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alert/edit.html.twig', [
            'alert' => $alert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_alert_delete', methods: ['POST'])]
    public function delete(Request $request, Alert $alert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($alert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_alert_index', [], Response::HTTP_SEE_OTHER);
    }
}

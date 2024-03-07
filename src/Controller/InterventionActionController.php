<?php

namespace App\Controller;

use App\Entity\InterventionAction;
use App\Form\InterventionActionType;
use App\Repository\InterventionActionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/interventionaction')]
class InterventionActionController extends AbstractController
{
    #[Route('/', name: 'app_intervention_action_index', methods: ['GET'])]
    public function index(InterventionActionRepository $interventionActionRepository): Response
    {
        return $this->render('intervention_action/index.html.twig', [
            'intervention_actions' => $interventionActionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_intervention_action_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $interventionAction = new InterventionAction();
        $form = $this->createForm(InterventionActionType::class, $interventionAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interventionAction);
            $entityManager->flush();

            return $this->redirectToRoute('app_intervention_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervention_action/new.html.twig', [
            'intervention_action' => $interventionAction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intervention_action_show', methods: ['GET'])]
    public function show(InterventionAction $interventionAction): Response
    {
        return $this->render('intervention_action/show.html.twig', [
            'intervention_action' => $interventionAction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_intervention_action_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InterventionAction $interventionAction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterventionActionType::class, $interventionAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_intervention_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervention_action/edit.html.twig', [
            'intervention_action' => $interventionAction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intervention_action_delete', methods: ['POST'])]
    public function delete(Request $request, InterventionAction $interventionAction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interventionAction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($interventionAction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_intervention_action_index', [], Response::HTTP_SEE_OTHER);
    }
}

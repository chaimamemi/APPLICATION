<?php

namespace App\Controller;

use App\Entity\Medication;
use App\Form\Medication1Type;
use App\Repository\MedicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/medication')]
class MedicationController extends AbstractController
{
    #[Route('/', name: 'app_medication_index', methods: ['GET'])]
    public function index(MedicationRepository $medicationRepository): Response
    {
        return $this->render('medication/index.html.twig', [
            'medications' => $medicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_medication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medication = new Medication();
        $form = $this->createForm(Medication1Type::class, $medication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($medication);
            $entityManager->flush();

            return $this->redirectToRoute('app_medication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medication/new.html.twig', [
            'medication' => $medication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medication_show', methods: ['GET'])]
    public function show(Medication $medication): Response
    {
        return $this->render('medication/show.html.twig', [
            'medication' => $medication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medication $medication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Medication1Type::class, $medication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medication/edit.html.twig', [
            'medication' => $medication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medication_delete', methods: ['POST'])]
    public function delete(Request $request, Medication $medication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($medication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medication_index', [], Response::HTTP_SEE_OTHER);
    }
}

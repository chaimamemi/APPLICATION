<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Form\HospitalType;
use App\Repository\HospitalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hospital')]
class HospitalController extends AbstractController
{
    #[Route('/', name: 'app_hospital_index', methods: ['GET'])]
    public function index(HospitalRepository $hospitalRepository): Response
    {
        return $this->render('hospital/index.html.twig', [
            'hospitals' => $hospitalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hospital_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $hospital = new Hospital();
    // Ne pas transmettre les données initiales pour les champs healthProfessionals et biologicalData
    $form = $this->createForm(HospitalType::class, $hospital);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($hospital);
        $entityManager->flush();

        return $this->redirectToRoute('app_hospital_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('hospital/new.html.twig', [
        'hospital' => $hospital,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_hospital_show', methods: ['GET'])]
    public function show(Hospital $hospital): Response
    {
        return $this->render('hospital/show.html.twig', [
            'hospital' => $hospital,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hospital_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hospital $hospital, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HospitalType::class, $hospital);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_hospital_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hospital/edit.html.twig', [
            'hospital' => $hospital,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hospital_delete', methods: ['POST'])]
    public function delete(Request $request, Hospital $hospital, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hospital->getId(), $request->request->get('_token'))) {
            $entityManager->remove($hospital);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hospital_index', [], Response::HTTP_SEE_OTHER);
    }
}

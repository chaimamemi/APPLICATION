<?php

namespace App\Controller;

use App\Entity\BiologicalData;
use App\Entity\Bracelet;
use App\Form\BiologicalDataType;
use App\Repository\BiologicalDataRepository;
use App\Repository\BraceletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

#[Route('/biologicaldata')]
class BiologicalDataController extends AbstractController
{
    #[Route('/', name: 'app_biological_data_index', methods: ['GET'])]
    public function index(BiologicalDataRepository $biologicalDataRepository): Response
    {
        $biologicalDatas = $biologicalDataRepository->findAll();

        return $this->render('biological_data/index.html.twig', [
            'biological_datas' => $biologicalDatas,
        ]);
    }

    #[Route('/new', name: 'app_biological_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, BraceletRepository $braceletRepository): Response
    {
        $biologicalDatum = new BiologicalData();
        $bracelets = $braceletRepository->findAll();
        $form = $this->createForm(BiologicalDataType::class, $biologicalDatum, [
            'bracelets' => $bracelets,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($biologicalDatum);
            foreach ($biologicalDatum->getMedication() as $medication) {
                $medication->setBiologicalData($biologicalDatum);
                $entityManager->persist($medication);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_biological_data_show', ['id' => $biologicalDatum->getId()]);
        }

        return $this->render('biological_data/new.html.twig', [
            'biological_datum' => $biologicalDatum,
            'form' => $form->createView(),
            'bracelets' => $bracelets,
        ]);
    }

    #[Route('/{id}', name: 'app_biological_data_show', methods: ['GET'])]
    public function show(BiologicalData $biologicalDatum): Response
    {
        $bracelet = $biologicalDatum->getBracelet();

        return $this->render('biological_data/show.html.twig', [
            'biological_datum' => $biologicalDatum,
            'bracelet' => $bracelet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_biological_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BiologicalData $biologicalDatum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BiologicalDataType::class, $biologicalDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_biological_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('biological_data/edit.html.twig', [
            'biological_datum' => $biologicalDatum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_biological_data_delete', methods: ['POST'])]
    public function delete(Request $request, BiologicalData $biologicalDatum, EntityManagerInterface $entityManager, BraceletRepository $braceletRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $biologicalDatum->getId(), $request->request->get('_token'))) {
            $bracelets = $braceletRepository->findBy(['biologicalData' => $biologicalDatum]);
            foreach ($bracelets as $bracelet) {
                $entityManager->remove($bracelet);
            }
            $entityManager->remove($biologicalDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_biological_data_index', [], Response::HTTP_SEE_OTHER);
    }
}

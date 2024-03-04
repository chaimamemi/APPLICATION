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
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/medication')]
class MedicationController extends AbstractController
{
    
    #[Route('/', name: 'app_medication_index', methods: ['GET'])]
    public function index(MedicationRepository $medicationRepository): Response
    {
        // Récupérer les données nécessaires depuis la base de données
        $medications = $medicationRepository->findAll();

        // Créer un tableau pour stocker les données spécifiques de BiologicalData
        $biologicalDataDetails = [];

        // Parcourir chaque médicament pour récupérer les détails de BiologicalData
        foreach ($medications as $medication) {
            // Vérifier si la liaison avec BiologicalData est définie
            $biologicalData = $medication->getBiologicalData();
            if ($biologicalData) {
                // Ajouter les détails requis à notre tableau
                $biologicalDataDetails[] = [
                    'firstName' => $biologicalData->getpatientName(),
                    'lastName' => $biologicalData->getpatientLastName(),
                    'disease' => $biologicalData->getDisease(),
                    'otherInformation' => $biologicalData->getOtherInformation(),
                ];
            }
        }

        // Passer les données à la vue Twig
        return $this->render('medication/index.html.twig', [
            'medications' => $medications,
            'biologicalDataDetails' => $biologicalDataDetails,

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



    #[Route('/download/{id}', name: 'app_medication_download_pdf', methods: ['GET'])]
    public function downloadPdf(Medication $medicationDatum, MedicationRepository $medicationRepository): Response
{
    // Configure Dompdf according to your needs
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    
    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);
    
    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('medication/index_pdf.html.twig', [
        'biological_datum' => $medicationDatum,
        'medications' => $medicationRepository->findAll(),

    ]);
    
    // Load HTML to Dompdf
    $dompdf->loadHtml($html);
    
    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser (force download)
    return new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment;filename="medicationData.pdf"'
    ]);
}


}

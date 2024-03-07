<?php

// src/Controller/AlertController.php

namespace App\Controller;

use App\Entity\Alert;
use App\Repository\BraceletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\SearchType;
use App\Model\SearchData;

#[Route('/alert')]
class AlertController extends AbstractController
{
    #[Route('/', name: 'app_alert_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);

        $alerts = $entityManager->getRepository(Alert::class)->findSearch($searchData->q);

        return $this->render('alert/alert.html.twig', [
            'alerts' => $alerts,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/', name: 'app_check_bracelets')]
    public function checkBracelets(BraceletRepository $braceletRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données des bracelets depuis la base de données
        $braceletsData = $braceletRepository->getBraceletsData();

        // Traiter les données des bracelets pour détecter les alertes
        foreach ($braceletsData as $braceletData) {
            $this->processBraceletData($braceletData, $entityManager);
        }

        // Récupérer à nouveau les alertes après le traitement
        $alerts = $entityManager->getRepository(Alert::class)->findAll();

        return $this->render('alert/alert.html.twig', [
            'alerts' => $alerts,
            'braceletsData' => $braceletsData,

        ]);
    }

    private function processBraceletData(array $braceletData, EntityManagerInterface $entityManager): void
    {
        // Analyser les données du bracelet
        $temperature = (float) preg_replace('/[^0-9.]/', '', $braceletData['temperature']);
        $bloodPressure = $braceletData['bloodPressure'];
        $movement = $braceletData['movement'];
        $latitude = $braceletData['latitude'];
        $longitude = $braceletData['longitude'];
        $heartRate = (int) preg_replace('/[^0-9]/', '', $braceletData['heartRate']);

        // Vérifier les conditions pour détecter les alertes
        if ($this->determineTemperatureSeverity($temperature) !== 'Normal') {
            $this->createAlert($entityManager, $braceletData['id'], 'Temperature', 'Temperature is abnormal.');
        }
        if ($this->determineBloodPressureSeverity($bloodPressure) !== 'Normal') {
            $this->createAlert($entityManager, $braceletData['id'], 'Blood Pressure', 'Blood pressure is abnormal.');
        }
        if ($this->determineMovementSeverity($movement) !== 'Normal') {
            $this->createAlert($entityManager, $braceletData['id'], 'Movement', 'Movement is abnormal.');
        }
        if ($this->determineLatitudeSeverity($latitude) !== 'Normal') {
            $this->createAlert($entityManager, $braceletData['id'], 'Latitude', 'Latitude data missing.');
        }
        if ($this->determineLongitudeSeverity($longitude) !== 'Normal') {
            $this->createAlert($entityManager, $braceletData['id'], 'Longitude', 'Longitude data missing.');
        }
        if ($this->determineHeartRateSeverity($heartRate) !== 'Normal') {
            $this->createAlert($entityManager, $braceletData['id'], 'Heart Rate', 'Heart rate is abnormal.');
        }
    }

    private function determineTemperatureSeverity(float $temperature): string
    {
        if ($temperature > 37.5) {
            return 'High';
        } elseif ($temperature < 35.5) {
            return 'Low';
        } else {
            return 'Normal';
        }
    }

    private function determineBloodPressureSeverity(string $bloodPressure): string
    {
        // Logique factice pour déterminer la gravité en fonction de la pression artérielle
        if ($bloodPressure > '140/90') {
            return 'High';
        } elseif ($bloodPressure < '90/60') {
            return 'Low';
        } else {
            return 'Normal';
        }
    }

    private function determineMovementSeverity(string $movement): string
    {
        // Logique factice pour déterminer la gravité en fonction du mouvement
        if ($movement === 'None' || $movement === 'High') {
            return 'High';
        } else {
            return 'Normal';
        }
    }

    private function determineLatitudeSeverity(string|float $latitude): string
    {
        // Logique factice pour déterminer la gravité en fonction de la latitude
        if (empty($latitude)) {
            return 'High';
        } else {
            return 'Normal';
        }
    }

    private function determineLongitudeSeverity(string|float $longitude): string
    {
        // Logique factice pour déterminer la gravité en fonction de la longitude
        if (empty($longitude)) {
            return 'High';
        } else {
            return 'Normal';
        }
    }

    private function determineHeartRateSeverity(int $heartRate): string
    {
        // Logique factice pour déterminer la gravité en fonction du rythme cardiaque
        if ($heartRate > 100) {
            return 'High';
        } elseif ($heartRate < 60) {
            return 'Low';
        } else {
            return 'Normal';
        }
    }

    private function createAlert(EntityManagerInterface $entityManager, int $braceletId, string $alertType, string $description): void
    {
        $alert = new Alert();
        $alert->getBraceletId();
        $alert->setTimestamp(new \DateTime());
        $alert->setAlertType($alertType);
        $alert->setSeverity($this->determineSeverity($alertType));
        $alert->setDescription($description);
        $alert->setHandled(false);

        // Enregistrement de l'alerte dans la base de données
        $entityManager->persist($alert);
        $entityManager->flush();
    }

    private function determineSeverity(string $alertType): string
    {
        // Logique factice pour déterminer la gravité en fonction du type d'alerte
        // Vous pouvez personnaliser cette logique en fonction de vos besoins
        if ($alertType === 'Temperature' || $alertType === 'Blood Pressure' || $alertType === 'Heart Rate') {
            return 'High';
        } else {
            return 'Normal';
        }
    }


    #[Route('/alert/delete/{id}', name: 'app_alert_delete', methods: ['POST'])]
public function deleteAlert(int $id, EntityManagerInterface $entityManager): Response
{
    $alert = $entityManager->getRepository(Alert::class)->find($id);
    
    if (!$alert) {
        throw $this->createNotFoundException('Alert not found');
    }

    
    $entityManager->remove($alert);
    $entityManager->flush();
    
    $this->addFlash('success', 'Alert deleted successfully.');
    
    return $this->redirectToRoute('app_alert_index');
}


#[Route('/alert/handle/{id}', name: 'app_alert_handle')]
public function handleAlert(int $id, EntityManagerInterface $entityManager): Response
{
    $alert = $entityManager->getRepository(Alert::class)->find($id);

    if (!$alert) {
        throw $this->createNotFoundException('Alert not found');
    }

    // Marquer l'alerte comme traitée
    $alert->setHandled('Yes'); // Assurez-vous que cela correspond à la manière dont vous avez modélisé le champ "handled"

    $entityManager->persist($alert);
    $entityManager->flush();

    $this->addFlash('success', 'Alert handled successfully.');

    return $this->redirectToRoute('app_alert_index');
}

}
<?php

// src/Repository/AlertRepository.php

namespace App\Repository;

use App\Entity\Alert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alert::class);
    }

    public function findSearch(string $searchTerm): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.alertType LIKE :searchTerm')
            ->orWhere('a.severity LIKE :searchTerm')
            ->orWhere('a.description LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    public function findNewAlerts(): array
    {
        // Exemple: récupérer toutes les alertes où le champ 'handled' est égal à 'No'
        // Adaptez cette requête selon les champs et la logique de votre base de données
        return $this->createQueryBuilder('a')
            ->where('a.handled = :handled')
            ->setParameter('handled', 'No')
            ->orderBy('a.timestamp', 'DESC') // Les plus récentes d'abord
            ->getQuery()
            ->getResult();
    }
}


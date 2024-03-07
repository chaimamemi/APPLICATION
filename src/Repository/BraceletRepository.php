<?php

namespace App\Repository;

use App\Entity\Bracelet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BraceletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bracelet::class);
    }

    public function findTemperature()
    {
        return $this->createQueryBuilder('b')
            ->select('b.temperature')
            ->setMaxResults(1) // Limiter les résultats à un seul
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findBloodPressure()
    {
        return $this->createQueryBuilder('b')
            ->select('b.bloodPressure')
            ->setMaxResults(1) // Limiter les résultats à un seul
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findHeartRate()
    {
        return $this->createQueryBuilder('b')
            ->select('b.heartRate')
            ->setMaxResults(1) // Limiter les résultats à un seul
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMovement()
    {
        return $this->createQueryBuilder('b')
            ->select('b.movement')
            ->setMaxResults(1) // Limiter les résultats à un seul
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findGps()
    {
        return $this->createQueryBuilder('b')
            ->select('b.gps')
            ->setMaxResults(1) // Limiter les résultats à un seul
            ->getQuery()
            ->getSingleScalarResult();
    }




    public function getBraceletsData(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.id', 'b.temperature', 'b.bloodPressure', 'b.heartRate', 'b.movement', 'b.latitude', 'b.longitude')
            ->getQuery()
            ->getResult();
    }
}

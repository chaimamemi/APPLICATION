<?php

namespace App\Repository;

use App\Entity\BiologicalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BiologicalData>
 *
 * @method BiologicalData|null find($id, $lockMode = null, $lockVersion = null)
 * @method BiologicalData|null findOneBy(array $criteria, array $orderBy = null)
 * @method BiologicalData[]    findAll()
 * @method BiologicalData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiologicalDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BiologicalData::class);
    }

//    /**
//     * @return BiologicalData[] Returns an array of BiologicalData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BiologicalData
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

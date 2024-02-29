<?php

namespace App\Repository;

use App\Entity\ProfessionalAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfessionalAccess>
 *
 * @method ProfessionalAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfessionalAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfessionalAccess[]    findAll()
 * @method ProfessionalAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalAccess::class);
    }

//    /**
//     * @return ProfessionalAccess[] Returns an array of ProfessionalAccess objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProfessionalAccess
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

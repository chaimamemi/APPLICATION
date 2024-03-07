<?php

namespace App\Repository;

use App\Entity\InterventionAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InterventionAction>
 *
 * @method InterventionAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionAction[]    findAll()
 * @method InterventionAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterventionAction::class);
    }

//    /**
//     * @return InterventionAction[] Returns an array of InterventionAction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InterventionAction
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

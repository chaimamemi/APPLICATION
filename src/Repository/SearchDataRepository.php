<?php

namespace App\Repository;

use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchData>
 *
 * @method SearchData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchData[]    findAll()
 * @method SearchData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchData::class);
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

//    /**
//     * @return SearchData[] Returns an array of SearchData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SearchData
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

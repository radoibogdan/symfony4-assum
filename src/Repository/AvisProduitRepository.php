<?php

namespace App\Repository;

use App\Entity\AvisProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisProduit[]    findAll()
 * @method AvisProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisProduit::class);
    }

     /**
      * @return AvisProduit[] Returns an array of AvisProduit objects
      */

    public function findAllDesc()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.creation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return AvisProduit[] Returns an array of AvisProduit objects
//     */

//    public function findAllDesc($value)
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//            ;
//    }

    /*
    public function findOneBySomeField($value): ?AvisProduit
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

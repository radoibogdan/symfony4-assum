<?php

namespace App\Repository;

use App\Entity\CategorieUC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieUC|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieUC|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieUC[]    findAll()
 * @method CategorieUC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieUCRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieUC::class);
    }

    // /**
    //  * @return CategorieUC[] Returns an array of CategorieUC objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieUC
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

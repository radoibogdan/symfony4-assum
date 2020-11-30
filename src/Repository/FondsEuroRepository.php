<?php

namespace App\Repository;

use App\Entity\FondsEuro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FondsEuro|null find($id, $lockMode = null, $lockVersion = null)
 * @method FondsEuro|null findOneBy(array $criteria, array $orderBy = null)
 * @method FondsEuro[]    findAll()
 * @method FondsEuro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FondsEuroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FondsEuro::class);
    }


    /**
     * @param $annee
     * @return FondsEuro[] Returns an array of FondsEuro objects
     */
    public function meilleurFondsDeLanneeX($annee) : array
    {
        # Création d'un QueryBuilder (constructeur de requête)
        return $this->createQueryBuilder('a')
            ->where('a.annee = :this_year')
            ->setParameter('this_year', $annee)
            ->orderBy('a.taux_pb','DESC')
            ->setMaxResults(1)
            ->getQuery()        # obtenir la requête
            ->getResult()       # obtenir un tableau d'entités
        ;
    }

    // /**
    //  * @return FondsEuro[] Returns an array of FondsEuro objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FondsEuro
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

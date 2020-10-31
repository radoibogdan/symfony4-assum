<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findNewProduits () {
        # Création d'un QueryBuilder (constructeur de requête)
        return $this->createQueryBuilder('a')
            ->where('a.creation >= :last_month')
            ->setParameter('last_month', new \DateTime('-1 month'))
            ->orderBy('a.creation','DESC')
            ->getQuery()        # obtenir la requête
            ->getResult()       # obtenir un tableau d'entités
        ;
    }

    /**
     * @return Query
     * Pour que la pagination fonctionne on a besoin de renvoyer une query et non le résultat
     */
    public function findAllQuery () : Query
    {
        # Création d'un QueryBuilder (constructeur de requête)
        return $this->createQueryBuilder('a')
            ->orderBy('a.creation','DESC')
            ->getQuery()        # obtenir la requête
            ;
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

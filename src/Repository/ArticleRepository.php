<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Query
     * Pour que la pagination fonctionne on a besoin de renvoyer une query et non le résultat
     */
    public function findAllQuery() : Query
    {
        # Création d'un QueryBuilder (constructeur de requête)
        return $this->createQueryBuilder('a')
            ->orderBy('a.creation','DESC')
            ->getQuery()        # obtenir la requête
        ;
    }

    public function findLastArticlePublished()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.creation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Based on user input searches in Article titles and Article contents
     * Must return a Query for the pagination to work
     * Section Articles
     * @param $mots
     * @return Query
     */
    public function search($mots): Query
    {
        $query = $this->createQueryBuilder('a');
        if ($mots != null) {
            // MATCH AGAINST = From Doctrine Site, found in Extension/Doctrine folder
            $query
                ->where('MATCH_AGAINST(a.titre, a.contenu) AGAINST(:mots boolean)>0')
                ->setParameter('mots', $mots)
            ;
        }
        return $query->getQuery();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
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

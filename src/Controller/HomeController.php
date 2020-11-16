<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\FondsEuroRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ProduitRepository $produitRepository
     * @param FondsEuroRepository $fondsEuroRepository
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository, FondsEuroRepository $fondsEuroRepository, ArticleRepository $articleRepository)
    {
        $annee_en_cours = date('Y');
        $list_produits = $produitRepository->findNewProduits();
        $meilleur_taux = $fondsEuroRepository->meilleurTauxDeCetteAnnee($annee_en_cours);

        return $this->render('home/index.html.twig', [
            'list_produits' => $list_produits,
            'meilleur_taux' => $meilleur_taux,
            'annee_en_cours' => $annee_en_cours,
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/qui-sommes-nous",name="qui_sommes_nous")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function whoAreWe(ArticleRepository $articleRepository)
    {
        return $this->render('home/qui_sommes_nous.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/mentions_legales",name="mentions_legales")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function legal(ArticleRepository $articleRepository)
    {
        return $this->render('home/mentions_legales.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/donnees_personnelles",name="donnees_personnelles")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function personal_data(ArticleRepository $articleRepository)
    {
        return $this->render('home/donnees_personnelles.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/nous_contacter",name="nous_contacter")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function contact(ArticleRepository $articleRepository)
    {
        return $this->render('home/nous_contacter.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }
}

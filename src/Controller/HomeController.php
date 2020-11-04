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
}

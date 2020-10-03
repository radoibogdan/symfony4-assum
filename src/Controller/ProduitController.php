<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\FondsEuroRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     * @param ProduitRepository $produitRepository
     * @param FondsEuroRepository $fondsEuroRepository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository, FondsEuroRepository $fondsEuroRepository)
    {
        $annee_en_cours = date('Y');
        $list_produits = $produitRepository->findAll();
        $meilleur_taux = $fondsEuroRepository->meilleurTauxDeCetteAnnee($annee_en_cours);
        return $this->render('produit/liste.html.twig', [
            'list_produits' => $list_produits,
            'meilleur_taux' => $meilleur_taux,
            'annee_en_cours'=> $annee_en_cours
        ]);
    }

    /**
     * @Route ("/annonce/{id}", name="affichage_produit")
     * @param Produit $produit
     * @return Response
     */
    public function show(Produit $produit) : Response
    {
        $annee_en_cours = date('Y');
        return $this->render('produit/affichage.html.twig',[
            'produit' => $produit,
            'annee_en_cours'=> $annee_en_cours
        ]);
    }
}

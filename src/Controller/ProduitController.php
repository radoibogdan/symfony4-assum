<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository)
    {
        $list_produits = $produitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'list_produits' => $list_produits
        ]);
    }

    /**
     * @Route ("/annonce/{id}", name="affichage_produit")
     * @param Produit $produit
     * @return Response
     */
    public function show(Produit $produit) : Response
    {
        return $this->render('produit/affichage.html.twig',[
            'produit' => $produit
        ]);
    }
}

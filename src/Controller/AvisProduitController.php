<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AvisProduitController extends AbstractController
{
    /**
     * @Route("/avis/produit", name="avis_produit")
     */
    public function index()
    {
        return $this->render('avis_produit/index.html.twig', [
            'controller_name' => 'AvisProduitController',
        ]);
    }
}

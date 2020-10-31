<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\AvisProduitFormType;
use App\Repository\FondsEuroRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param ProduitRepository $produitRepository
     * @param FondsEuroRepository $fondsEuroRepository
     * @return Response
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        ProduitRepository $produitRepository,
        FondsEuroRepository $fondsEuroRepository)
    {
        $annee_en_cours = date('Y');
        $list_produits = $paginator->paginate(
          $produitRepository->findAllQuery(),
          $request->query->getInt('page',1),
          5
        );
        $meilleur_taux = $fondsEuroRepository->meilleurTauxDeCetteAnnee($annee_en_cours);
        return $this->render('produit/liste.html.twig', [
            'list_produits' => $list_produits,
            'meilleur_taux' => $meilleur_taux,
            'annee_en_cours'=> $annee_en_cours
        ]);
    }

    /**
     * @Route ("/annonce/{id}", name="affichage_produit")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Produit $produit
     * @return Response
     */
    public function show(Request $request, EntityManagerInterface $entityManager, Produit $produit) : Response
    {
        /*
            $avis = $produit->getAvisProduits();
            $cumul_notes = 0;
            $nombre_notes = 0;
            foreach ($avis as $avis_individuel) {
                $cumul_notes += $avis_individuel->getNote();
                $nombre_notes++;
            }
            $moyenne= $cumul_notes === 0 ? 0 : $cumul_notes/$nombre_notes;
        */
        $moyenne= $produit->getMoyenneProduit();

        $form = $this->createForm(AvisProduitFormType::class);
        $form->handleRequest($request);

        // Si le user s'est connecté et il y a validé le commentaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Rajouter une entité dans la bdd 1. Récupérer l'entité avec getData() 2. persist()
            // getData retourne une AvisProduit
            $avisproduit = $form->getData();
            $avisproduit->setAuteur($this->getUser());
            $avisproduit->setProduit($produit);
            $entityManager->persist($avisproduit);
            $entityManager->flush();
            $this->addFlash('success','Votre commentaire est enregistré et soumis pour validation.');
            return $this->redirect($request->getUri());
        }
        $annee_en_cours = date('Y');
        return $this->render('produit/affichage.html.twig',[
            'produit'           => $produit,
            'annee_en_cours'    => $annee_en_cours,
            'formAvisProduit'   => $form->createView(),
            'touslesavis'       => $produit->getAvisProduits(),
            'moyenne'           => $moyenne
        ]);
    }
}

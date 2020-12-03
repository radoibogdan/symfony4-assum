<?php

namespace App\Controller;

use App\Entity\AvisProduit;
use App\Entity\Produit;
use App\Form\AvisProduitFormType;
use App\Repository\ArticleRepository;
use App\Repository\AvisProduitRepository;
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
     * @return Response
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        ProduitRepository $produitRepository)
    {
        $annee_en_cours = date('Y') -1;
        $list_produits = $paginator->paginate(
          $produitRepository->findAllQuery(),
          $request->query->getInt('page',1),
          6
        );
        return $this->render('produit/liste.html.twig', [
            // 'list_produits' => $list_produits->getItems(),
            'annee_en_cours'=> $annee_en_cours,
            'list_produits' => $list_produits
        ]);
    }

    /**
     * @Route ("/produit/{id}", name="affichage_produit")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Produit $produit
     * @param AvisProduitRepository $avisProduitRepository
     * @return Response
     */
    public function show
    (
        Request $request,
        EntityManagerInterface $entityManager,
        Produit $produit,
        AvisProduitRepository $avisProduitRepository
    ) : Response
    {
        // Obtenir la moyenne de ce produit
        $moyenne= $produit->getMoyenneProduit();

        // Vérifie si le user a déjà donné son avis sur ce produit
        $avis_deja_donne = $this->avisDejaDonne($produit);

        $form = $this->createForm(AvisProduitFormType::class);
        $form->handleRequest($request);

        // Si le user s'est connecté et s'il a validé le commentaire
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
        dump($avisProduitRepository->findLastXAvis(2, $produit->getId()));
        dump($produit->getId());
        $annee_en_cours = date('Y');
        return $this->render('produit/affichage.html.twig',[
            'produit'           => $produit,
            'annee_en_cours'    => $annee_en_cours,
            'formAvisProduit'   => $form->createView(),
            //'touslesavis'       => $produit->getAvisProduits(),
            'derniersAvis'       => $avisProduitRepository->findLastXAvis(2, $produit->getId()),
            'moyenne'           => $moyenne,
            'avis_deja_donne'   => $avis_deja_donne
        ]);
    }

    /**
     * PRODUIT DU MOIS
     * @Route ("/produit_du_mois", name="show_best_produit")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function show_best(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository) : Response
    {
        $produits_mois_en_cours = $produitRepository->findNewProduits();
        $moyenne_du_mois = 0;
        $produit_du_mois = 0;
        /** @var Produit $produit */
        foreach ($produits_mois_en_cours as $produit) {
            // Obtenir la moyenne d'un produit
            if ($produit->getMoyenneProduit() > $moyenne_du_mois) {
                $produit_du_mois = $produit;
                $moyenne_du_mois = $produit->getMoyenneProduit();
            };
        }
        $this->addFlash(
            'info',
            'Ceci est un produit récemment créé (moins d\'un mois) et le mieux côté d\'après les avis de nos utilisateurs enregistrés.'
        );
        return $this->redirectToRoute('affichage_produit',[
            'id' => $produit_du_mois->getId()
        ]);
    }

    /**
     * @param Produit $produit
     * @return bool
     * Vérifie si le user a déjà donné son avis sur le produit
     */
    private function avisDejaDonne(Produit $produit) : bool
    {
        $avis_deja_donne = false;
        // Si le user n'est pas connecté on renvoie false
        if (!$this->getUser()) {
            return $avis_deja_donne;
        }

        // récupère tous les avis de l'utilisateur et vérifie s'il a déjà donné son avis sur le produit passé en argument
        $array_avis = $this->getUser()->getAvisProduits();
        /** @var AvisProduit $avis */
        foreach ($array_avis as $avis) {
            if ($avis->getProduit()->getId() === $produit->getId()) {
                $avis_deja_donne = true;
            }
        }
        return $avis_deja_donne;
    }
}

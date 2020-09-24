<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ConfirmDeletionFormType;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs et moderateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/produit", name="admin_produit_")
 */
class AdminProduitController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository)
    {
        $list_produits = $produitRepository->findAll();
        return $this->render('admin_produit/liste.html.twig', [
            'list_produits' => $list_produits
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Produit $produit
     * @param Request $request
     * @Route ("{id}/edit", name="edit")
     * @return Response
     */
    public function edit(EntityManagerInterface $entityManager, Produit $produit, Request $request) {
        // Le fait de mettre Produit comme argument va récupérer le bon Produit de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData(). Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées au produit ont été enregistrées!');
        }
        return $this->render('admin_produit/edit.html.twig', [
            'produit' => $produit, // pour rajouter des informations en plus du formulaire
            'produitForm' => $form->createView()
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Produit $produit
     * @param Request $request
     * @Route ("{id}/delete", name="delete")
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager, Produit $produit, Request $request) {
        // Le fait de mettre Produit comme argument va récupérer le bon Produit de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData(). Les modifications sont faites automatiquement
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success','Le produit a été supprimé.');
            return $this->redirectToRoute('admin_produit_liste');
        }
        return $this->render('admin_produit/delete.html.twig', [
            'produit' => $produit, // pour rajouter des informations en plus du formulaire
            'deletionForm' => $form->createView()
        ]);
    }
}

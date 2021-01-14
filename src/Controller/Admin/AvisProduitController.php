<?php

namespace App\Controller\Admin;

use App\Entity\AvisProduit;
use App\Form\ConfirmDeletionFormType;
use App\Repository\AvisProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AvisProduitController
 * @package App\Controller\Admin
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/avis_produit", name="admin_avisproduit_")
 */
class AvisProduitController extends AbstractController
{
    /**
     * Lists all Product reviews
     * @Route("s", name="liste")
     * @param AvisProduitRepository $avisProduitRepository
     * @return Response
     */
    public function index(AvisProduitRepository $avisProduitRepository)
    {
        // order by date
        $avisproduit_list = $avisProduitRepository->findAllDesc();
        return $this->render('admin_avis_produit/liste.html.twig', [
            'avisproduit_list' => $avisproduit_list
        ]);
    }

    /**
     * Delete confirmation form for product review
     * @param EntityManagerInterface $entityManager
     * @param AvisProduit $avisProduit
     * @param Request $request
     * @return Response
     * @Route ("{id}/delete", name="delete")
     */
    public function delete(EntityManagerInterface $entityManager, AvisProduit $avisProduit, Request $request) {
        // Le fait de mettre Produit comme argument va récupérer le bon Produit de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData(). Les modifications sont faites automatiquement
            $entityManager->remove($avisProduit);
            $entityManager->flush();
            $this->addFlash('success','L\'avis a été supprimé.');
            return $this->redirectToRoute('admin_avisproduit_liste');
        }
        return $this->render('admin_avis_produit/delete.html.twig', [
            'avisProduit' => $avisProduit, // pour rajouter des informations en plus du formulaire
            'deleteForm' => $form->createView()
        ]);
    }

    /**
     * Toggle validation for a product review
     * @param EntityManagerInterface $entityManager
     * @param AvisProduit $avisProduit
     * @param Request $request
     * @return Response
     * @Route ("{id}/approuver", name="approuver")
     */
    public function validate(EntityManagerInterface $entityManager, AvisProduit $avisProduit, Request $request) {
        // Le fait de mettre Produit comme argument va récupérer le bon Produit de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        // Toggle validation
        if ($avisProduit->getApprouve() == 0) {
            $avisProduit->setApprouve(1);
            $this->addFlash('success','L\'avis a été approuvé.');
        } else {
            $avisProduit->setApprouve(0);
            $this->addFlash('danger','L\'avis a été invalidé.');
        }
        $entityManager->flush();
        return $this->redirectToRoute('admin_avisproduit_liste');
    }
}

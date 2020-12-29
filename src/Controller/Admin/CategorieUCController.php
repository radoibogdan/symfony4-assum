<?php

namespace App\Controller\Admin;

use App\Entity\CategorieUC;
use App\Form\CategorieUCFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\CategorieUCRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/categorie_uc", name="admin_categorie_uc_")
 */
class CategorieUCController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param CategorieUCRepository $categorie_ucRepository
     * @return Response
     */
    public function index(CategorieUCRepository $categorie_ucRepository)
    {
        $list_categories_uc = $categorie_ucRepository->findAll();
        return $this->render('admin_categorie_uc/liste.html.twig', [
            'list_categories_uc' => $list_categories_uc
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(CategorieUCFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // pour rajouter une entité dans la bdd on a besoin de récupérer l'entité (getData) et de la méthode persist()
            // getData retourne une CategorieUC
            $categorie_uc = $form->getData();
            $entityManager->persist($categorie_uc);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie a été ajoutée.');
            return $this->redirectToRoute('admin_categorie_uc_liste');
        }
        return $this->render('admin_categorie_uc/add.html.twig', [
            'categorie_ucForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="edit")
     * @param CategorieUC $categorie_uc
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(CategorieUC $categorie_uc, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre CategorieUC comme argument va récupérer la bonne Catégorie de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(CategorieUCFormType::class, $categorie_uc);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData et persist(). Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées à la catégorie ont été enregistrées!');
            return $this->redirectToRoute('admin_categorie_uc_liste');
        }
        return $this->render('/admin_categorie_uc/edit.html.twig', [
            'categorie_uc' => $categorie_uc, // pour rajouter des informations en plus du formulaire
            'categorie_ucForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/suppression", name="delete")
     * @param CategorieUC $categorie_uc
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(CategorieUC $categorie_uc, Request $request, EntityManagerInterface $entityManager)
    {
        // ConfirmDeletionFormType n'est pas lié à une entité
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityManager->remove($categorie_uc);
            $entityManager->flush();

            $this->addFlash('danger', 'La catégorie a été supprimée');
            return $this->redirectToRoute('admin_categorie_uc_liste');
        }
        return $this->render('/admin_categorie_uc/delete.html.twig', [
            'categorie_uc' => $categorie_uc,
            'deleteForm' => $form->createView()
        ]);
    }

}

<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/categorie", name="admin_categorie_")
 */
class CategorieController extends AbstractController
{
    /**
     * List all the Categories
     * @Route("s", name="liste")
     * @param CategorieRepository $categorieRepository
     * @return Response
     */
    public function index(CategorieRepository $categorieRepository)
    {
        $list_categories = $categorieRepository->findAll();
        return $this->render('admin_categorie/liste.html.twig', [
            'list_categories' => $list_categories
        ]);
    }

    /**
     * Add new category
     * @Route("/ajouter", name="add")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(CategorieFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // pour rajouter une entité dans la bdd on a besoin de récupérer l'entité (getData) et de la méthode persist()
            // getData retourne une Categorie
            $categorie = $form->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie a été ajoutée.');
            return $this->redirectToRoute('admin_categorie_liste');
        }
        return $this->render('admin_categorie/add.html.twig', [
            'categorieForm' => $form->createView()
        ]);
    }

    /**
     * Edit category
     * @Route("/{id}/modifier", name="edit")
     * @param Categorie $categorie
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre Categorie comme argument va récupérer la bonne Catégorie de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData et persist(). Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées à la catégorie ont été enregistrées!');
            return $this->redirectToRoute('admin_categorie_liste');
        }
        return $this->render('/admin_categorie/edit.html.twig', [
            'categorie' => $categorie, // pour rajouter des informations en plus du formulaire
            'categorieForm' => $form->createView()
        ]);
    }

    /**
     * Delete confirmation form for Category
     * @Route("/{id}/suppression", name="delete")
     * @param Categorie $categorie
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Categorie $categorie, Request $request, EntityManagerInterface $entityManager)
    {
        // ConfirmDeletionFormType n'est pas lié à une entité
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityManager->remove($categorie);
            $entityManager->flush();

            $this->addFlash('danger', 'La catégorie a été supprimée');
            return $this->redirectToRoute('admin_categorie_liste');
        }
        return $this->render('/admin_categorie/delete.html.twig', [
            'categorie' => $categorie,
            'deleteForm' => $form->createView()
        ]);
    }

}

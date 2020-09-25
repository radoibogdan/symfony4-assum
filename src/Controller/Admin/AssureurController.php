<?php

namespace App\Controller\Admin;

use App\Entity\Assureur;
use App\Form\AssureurFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\AssureurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/assureur", name="admin_assureur_")
 */
class AssureurController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param AssureurRepository $assureurRepository
     * @return Response
     */
    public function index(AssureurRepository $assureurRepository)
    {
        $list_assureurs = $assureurRepository->findAll();
        return $this->render('admin_assureur/liste.html.twig', [
            'list_assureurs' => $list_assureurs
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
        $form = $this->createForm(AssureurFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // pour rajouter une entité dans la bdd on a besoin de récupérer l'entité (getData) et de la méthode persist()
            // getData retourne une Assureur
            $assureur = $form->getData();
            $entityManager->persist($assureur);
            $entityManager->flush();
            $this->addFlash('success', 'L\'assureur a été ajouté.');
            return $this->redirectToRoute('admin_assureur_liste');
        }
        return $this->render('admin_assureur/add.html.twig', [
            'assureurForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="edit")
     * @param Assureur $assureur
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Assureur $assureur, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre Assureur comme argument va récupérer le bon Assureur de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(AssureurFormType::class, $assureur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData et persist(). Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées à l\'assureur ont été enregistrées!');
        }
        return $this->render('/admin_assureur/edit.html.twig', [
            'assureur' => $assureur, // pour rajouter des informations en plus du formulaire
            'assureurForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/suppression", name="delete")
     * @param Assureur $assureur
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Assureur $assureur, Request $request, EntityManagerInterface $entityManager)
    {
        // ConfirmDeletionFormType n'est pas lié à une entité
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityManager->remove($assureur);
            $entityManager->flush();

            $this->addFlash('success', 'L\'assureur a été supprimé.');
            return $this->redirectToRoute('admin_assureur_liste');
        }
        return $this->render('/admin_assureur/delete.html.twig', [
            'assureur' => $assureur,
            'deleteForm' => $form->createView()
        ]);
    }

}

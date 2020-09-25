<?php

namespace App\Controller\Admin;

use App\Entity\Gestion;
use App\Form\ConfirmDeletionFormType;
use App\Form\GestionFormType;
use App\Repository\GestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/gestion", name="admin_gestion_")
 */
class GestionController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param GestionRepository $gestionRepository
     * @return Response
     */
    public function index(GestionRepository $gestionRepository)
    {
        $list_gestions = $gestionRepository->findAll();
        return $this->render('admin_gestion/liste.html.twig', [
            'list_gestions' => $list_gestions
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
        $form = $this->createForm(GestionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // pour rajouter une entité dans la bdd on a besoin de récupérer l'entité (getData) et de la méthode persist()
            // getData retourne une Gestion
            $gestion = $form->getData();
            $entityManager->persist($gestion);
            $entityManager->flush();
            $this->addFlash('success', 'La type de gestion a été ajouté.');
            return $this->redirectToRoute('admin_gestion_liste');
        }
        return $this->render('admin_gestion/add.html.twig', [
            'gestionForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="edit")
     * @param Gestion $gestion
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Gestion $gestion, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre Gestion comme argument va récupérer la bonne Gestion de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(GestionFormType::class, $gestion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData et persist(). Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées à la gestion ont été enregistrées!');
        }
        return $this->render('/admin_gestion/edit.html.twig', [
            'gestion' => $gestion, // pour rajouter des informations en plus du formulaire
            'gestionForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/suppression", name="delete")
     * @param Gestion $gestion
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Gestion $gestion, Request $request, EntityManagerInterface $entityManager)
    {
        // ConfirmDeletionFormType n'est pas lié à une entité
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityManager->remove($gestion);
            $entityManager->flush();

            $this->addFlash('success', 'Le type de gestion a été supprimé');
            return $this->redirectToRoute('admin_gestion_liste');
        }
        return $this->render('/admin_gestion/delete.html.twig', [
            'gestion' => $gestion,
            'deleteForm' => $form->createView()
        ]);
    }

}

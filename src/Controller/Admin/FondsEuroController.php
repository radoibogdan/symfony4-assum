<?php

namespace App\Controller\Admin;

use App\Entity\FondsEuro;
use App\Form\ConfirmDeletionFormType;
use App\Form\FondsEuroFormType;
use App\Repository\FondsEuroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FondsEuroController
 * @package App\Controller\Admin
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/fonds_euro", name="admin_fonds_euro_")
 */
class FondsEuroController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param FondsEuroRepository $fondsEuroRepository
     * @return Response
     */
    public function index(FondsEuroRepository $fondsEuroRepository)
    {
        $list_fonds_euros = $fondsEuroRepository->findAll();
        return $this->render('admin_fonds_euro/liste.html.twig', [
            'list_fonds_euros' => $list_fonds_euros
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
        $form = $this->createForm(FondsEuroFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // pour rajouter une entité dans la bdd on a besoin de récupérer l'entité (getData) et de la méthode persist()
            // getData retourne une FondsEuro
            $fonds_euro = $form->getData();
            $entityManager->persist($fonds_euro);
            $entityManager->flush();
            $this->addFlash('success', 'Le fonds euro a été ajouté.');
            return $this->redirectToRoute('admin_fonds_euro_liste');
        }
        return $this->render('admin_fonds_euro/add.html.twig', [
            'fonds_euroForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="edit")
     * @param FondsEuro $fonds_euro
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(FondsEuro $fonds_euro, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre FondsEuro comme argument va récupérer la bonne FondsEuro de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(FondsEuroFormType::class, $fonds_euro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData et persist(). Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées au fonds euro ont été enregistrées!');
        }
        return $this->render('/admin_fonds_euro/edit.html.twig', [
            'fonds_euro' => $fonds_euro, // pour rajouter des informations en plus du formulaire
            'fonds_euroForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/suppression", name="delete")
     * @param FondsEuro $fonds_euro
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(FondsEuro $fonds_euro, Request $request, EntityManagerInterface $entityManager)
    {
        // ConfirmDeletionFormType n'est pas lié à une entité
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityManager->remove($fonds_euro);
            $entityManager->flush();

            $this->addFlash('success', 'Le fonds euro a été supprimé');
            return $this->redirectToRoute('admin_fonds_euro_liste');
        }
        return $this->render('/admin_fonds_euro/delete.html.twig', [
            'fonds_euro' => $fonds_euro,
            'deleteForm' => $form->createView()
        ]);
    }
}

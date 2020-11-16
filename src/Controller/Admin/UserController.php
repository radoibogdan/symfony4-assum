<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\ConfirmDeletionFormType;
use App\Form\UserProfileFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs et moderateurs sur toutes les routes de ce controlleur
 * @Route("/admin/user", name="admin_user_")
 * @IsGranted ("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        $user_list = $userRepository->findAll();
        return $this->render('admin_user/liste.html.twig', [
            'user_list' => $user_list
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(EntityManagerInterface $entityManager, User $user, Request $request)
    {
        // Récupérer l'utilisateur actuel $this->getUser() ou via le autowiring (User dans les arguments)
        // Associer le formulaire à l'utilisateur actuel: $this->createForm(Form::class, $this->getUser() or $user)
        $form = $this->createForm(UserProfileFormType::class, $user);
        // handleRequest permet de garder les champs renseignés en cas d'erreur
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success','Le profil a été modifié.');
        }
        return $this->render('admin_user/edit.html.twig',[
            'userForm' => $form->createView(),
            'user'     => $user
        ]);
    }

    /**
     * @Route ("/{id}/delete", name="delete")
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager, User $user, Request $request)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success','L\'utilisateur a été supprimé');
            return $this->redirectToRoute('admin_user_liste');
        }

        return $this->render('admin_user/delete.html.twig',[
           'deleteForm' => $form->createView(),
            'user'      => $user
        ]);
    }
}

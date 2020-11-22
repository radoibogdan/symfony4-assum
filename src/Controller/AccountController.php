<?php

namespace App\Controller;

use App\Form\ConfirmDeletionFormType;
use App\Form\UserProfileFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/compte/profil", name="profil")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserProfileFormType::class,$this->getUser());
        // Récupérer l'utilisateur actuel $this->getUser()
        // Associer le formulaire à l'utilisateur actuel: $this->createForm(Form::class, $this->getUser())
        $form->handleRequest($request);
        // handleRequest permet de garder les champs renseignés en cas d'erreur
        // pour avoir le formulaire prérempli
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success','Votre profil a été mis à jour.');
        }

        return $this->render('compte/profil.html.twig', [
            'profilForm' => $form->createView()
        ]);
    }

    /**
     * @Route ("/compte/suppression", name="user_suppression_compte")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success','Votre compte a été supprimé.');
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();

            return $this->redirectToRoute('home');
        }

        return $this->render('compte/delete.html.twig',[
            'deleteForm' => $form->createView(),
            'user'      => $user
        ]);
    }
}

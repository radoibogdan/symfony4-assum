<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\ArticleRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, ArticleRepository $articleRepository): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // dump($request->headers->get('referer')); die();
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'back_to_produit' => $request->headers->get('referer'),
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Route déconnexion : On rédirige vers "/" et on rajoute un message flash
     * @Route("/logout-message", name="app_logout_message")
     */
    public function logoutMessage()
    {
        // Ajout d'un message flash
        $this->addFlash('info','Vous avez été déconnecté(e).');
        // Redirection vers la page de connexion
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/inscription", name="inscription")
     * copié depuis le RegistrationController
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, ArticleRepository $articleRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // after validating the user and saving them to the database
            // authenticate the user and use onAuthenticationSuccess on the authenticator
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,            // the User object you just created
                $request,
                $authenticator,   // authenticator whose onAuthenticationSuccess you want to use
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route("/reset", name="reset_pass")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function reset(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface  $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            ));
            $entityManager->flush();
            $this->addFlash('success','Le mot de pass a été modifié.');
            return $this->redirectToRoute('profil');
        }

        return $this->render('security/reset.html.twig',[
            'resetForm' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // dump($request->headers->get('referer')); die();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if last URL is comming from the FORGOT PASS, RESET URL TOKEN PROCESS
        $last_url_accessed = $request->headers->get('referer');
        if (strpos($last_url_accessed,'reset-pass-token')) {
            $last_url_accessed = $this->generateUrl('app_login',[],UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'back_to_produit' => $last_url_accessed
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
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        MailerInterface $mailer
    ): Response
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

            // GENERATE ACTIVATION TOKEN
            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // SEND ACTIVATION EMAIL with TOKEN
            $email_activation = (new TemplatedEmail())
                ->from(new Address('radoi.office@gmail.com'))
                ->to($user->getEmail())
                ->subject('Activation de votre compte')
                ->htmlTemplate('contact/activation.html.twig')
                ->context([
                    'token' => $user->getActivationToken()
                ])
            ;
            $mailer->send($email_activation);

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
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * WHEN THE USER CREATES HIS ACCOUNT, an ACTIVATION TOKEN is sent to the email he provided, thus activating his account
     * This is the route he finds in the email
     * @Route("/activation/{token}", name="activation")
     * @param $token
     * @param UserRepository $userRepository
     * @return RedirectResponse
     */
    public function activate_account($token, UserRepository $userRepository)
    {
        // Check if any user has an account with the token provided in the url
        $user = $userRepository->findOneBy([
            'activation_token' => $token
        ]);

        // If no user has this token generate error
        if (!$user) {
            // Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas.');
        }

        // If user exists delete token in the database
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Generate flash message
        $this->addFlash('info', 'Vous avez bien activé votre compte.');

        // Redirect to homepage
        return $this->redirectToRoute('home');
    }

    /**
     * RESET PASSWORD IF USER IS ALREADY CONNECTED
     * @Route("/reset", name="reset_pass")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function reset(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
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

    /**
     * STEP 1 : FORGOT PASSWORD -> SEND EMAIL WITH RESET TOKEN TO USER
     * The forgotten password form is handled, if email is valid an email with the url for reseting the password is sent to the email
     * @Route("/mot-de-pass-oublie",name="app_forgotten_password")
     * @param MailerInterface $mailer
     * @param Request $request
     * @param UserRepository $userRepository
     * @param TokenGeneratorInterface $tokenGenerator
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function forgottenPassword(
        MailerInterface $mailer,
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        AuthenticationUtils $authenticationUtils
    )
    {
        // Create form, handle and validate data
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get data from form
            $data = $form->getData();
            // Check if user exists with the e-mail from the form
            $user = $userRepository->findOneBy(['email' => $data['email']]);
            // Create Lure, if user doesn't exist don't confirm it
            if (!$user) {
                $this->addFlash('success', 'Un e-mail de réinitialisation de votre mot de passe a été envoyé à l\'adresse renseignée.');
                return $this->redirectToRoute('app_login');
            }

            // If user exists generate token
            $token = $tokenGenerator->generateToken();
            // If error on database access/writing, generate exception
            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger','Une erreur est survenue :' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // Create RESET URL to be sent by e-mail
            $url = $this->generateUrl('app_forgotten_password_reset',['token' => $token],UrlGeneratorInterface::ABSOLUTE_URL);

            // Create and send e-mail with RESET URL
            $email_reset = (new TemplatedEmail())
                ->from(new Address('radoi.office@gmail.com','Bogdan'))
                ->to($user->getEmail())
                ->subject('Réinitialisation du mot de passe')
                ->htmlTemplate('contact/reset_password.html.twig')
                ->context(['url' => $url])
            ;
            $mailer->send($email_reset);

            // Add flash message for user and redirect to login page
            $this->addFlash('success','Un e-mail de réinitialisation de votre mot de passe a été envoyé à l\'adresse renseignée.');
            return $this->redirectToRoute('app_login');
        }

        // INITIAL REDIRECT : where user inputs email
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/forgotten_password.html.twig',[
            'emailForm' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * STEP 2 : FORGOT PASSWORD -> After the email link is clicked by the user he is redirected to this route
     * @Route("reset-pass-token/{token}", name="app_forgotten_password_reset")
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param $token
     * @return RedirectResponse|Response
     */
    public function forgottenPasswordResetToken (
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request,
        $token
    )
    {
        // Find user using the token provided in the url
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        // If the user doesn't exist redirect to Login page
        if (!$user) {
            $this->addFlash('info','Le token n\'est pas valide.');
            return  $this->redirectToRoute('app_login');
        }

        // Step 1 : If user submits new password and it is valid => save to databasse
        // Step 2 : Delete reset_token
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            ));
            $entityManager->flush();
            // Delete RESET TOKEN in the database
            $user->setResetToken(null);
            // Add flash message
            $this->addFlash('success','Le mot de pass a été modifié.');
            // Redirect to Login Page
            return $this->redirectToRoute('app_login');
        }

        // Show reset password form + pass the token from the url
        return $this->render('security/reset.html.twig',[
            'resetForm' => $form->createView(),
            'token' => $token
        ]);
    }
}

<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository)
    {
        $list_produits = $produitRepository->findNewProduits();
        return $this->render('home/index.html.twig', [
            'list_produits' => $list_produits
        ]);
    }

    /**
     * @Route ("/qui-sommes-nous",name="qui_sommes_nous")
     * @return Response
     */
    public function whoAreWe()
    {
        return $this->render('home/qui_sommes_nous.html.twig');
    }

    /**
     * @Route ("/mentions_legales",name="mentions_legales")
     * @return Response
     */
    public function legal()
    {
        return $this->render('home/mentions_legales.html.twig');
    }

    /**
     * @Route ("/donnees_personnelles",name="donnees_personnelles")
     * @return Response
     */
    public function personal_data()
    {
        return $this->render('home/donnees_personnelles.html.twig');
    }

    /**
     * @Route("/nous_contacter", name="nous_contacter")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = (new TemplatedEmail())
                ->from(new Address('radoi.office@gmail.com'))
                ->to(new Address('radoi.office@gmail.com', 'Bogdan RADOI'))
                ->subject('Notification Assum')
                ->htmlTemplate('contact/notification.html.twig')
                ->embed(fopen('uploads/images_site/logo_assum.png', 'r'), 'logo')
                ->context([
                    'message'   => $contactForm['message']->getData(),
                    'nom'       => $contactForm['nom']->getData(),
                    'prenom'    => $contactForm['prenom']->getData(),
                    'telephone' => $contactForm['telephone']->getData(),
                    'email_contact'     => $contactForm['email']->getData()
                ])
            ;
            $mailer->send($contact);

            $this->addFlash('success', 'Message envoyé !');
        }

        // Honey Pot (referer) for l'antispam
        // If bot accesses page directly, it will redirect to homepage.
        if ($request->headers->get('referer') === null) {
            return $this->redirectToRoute('home');
        }

        return $this->render('home/nous_contacter.html.twig', [
            'contact_form' => $contactForm->createView()
        ]);
    }

}

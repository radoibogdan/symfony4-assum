<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\FondsEuroRepository;
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
     * @param FondsEuroRepository $fondsEuroRepository
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ProduitRepository $produitRepository, FondsEuroRepository $fondsEuroRepository, ArticleRepository $articleRepository)
    {
        $annee_en_cours = date('Y');
        $list_produits = $produitRepository->findNewProduits();
        $meilleur_taux = $fondsEuroRepository->meilleurTauxDeCetteAnnee($annee_en_cours);

        return $this->render('home/index.html.twig', [
            'list_produits' => $list_produits,
            'meilleur_taux' => $meilleur_taux,
            'annee_en_cours' => $annee_en_cours,
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/qui-sommes-nous",name="qui_sommes_nous")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function whoAreWe(ArticleRepository $articleRepository)
    {
        return $this->render('home/qui_sommes_nous.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/mentions_legales",name="mentions_legales")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function legal(ArticleRepository $articleRepository)
    {
        return $this->render('home/mentions_legales.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/donnees_personnelles",name="donnees_personnelles")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function personal_data(ArticleRepository $articleRepository)
    {
        return $this->render('home/donnees_personnelles.html.twig',[
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route("/nous_contacter", name="nous_contacter")
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function contact(ArticleRepository $articleRepository, Request $request, MailerInterface $mailer)
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = (new TemplatedEmail())
                ->from(new Address('radoi.office@gmail.com'))
                ->to(new Address('radoi.office@gmail.com', 'Bogdan RADOI'))
                ->subject('Envoyé avec Symfony Mailer')
                ->htmlTemplate('contact/notification.html.twig')
                ->context([
                    'message' => $contactForm['message']->getData(),
                ]);
                $mailer->send($contact);

            $this->addFlash('success', 'Message envoyé !');
        }

        return $this->render('home/nous_contacter.html.twig', [
            'contact_form' => $contactForm->createView(),
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

}

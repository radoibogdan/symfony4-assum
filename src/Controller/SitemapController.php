<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format" = "xml"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // On récupère le nom d'hôte depuis l'url, le debut d'url getSchemeAndHttpHost()
        $hostname = $request->getSchemeAndHttpHost();

        // On initialise un tableau pour lister les urls
        $urls = [];

        // On ajoute les urls statiques
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('app_logout')];
        $urls[] = ['loc' => $this->generateUrl('app_logout_message')];
        $urls[] = ['loc' => $this->generateUrl('inscription')];
        $urls[] = ['loc' => $this->generateUrl('profil')];
        $urls[] = ['loc' => $this->generateUrl('reset_pass')];
        $urls[] = ['loc' => $this->generateUrl('user_suppression_compte')];
        $urls[] = ['loc' => $this->generateUrl('qui_sommes_nous')];
        $urls[] = ['loc' => $this->generateUrl('mentions_legales')];
        $urls[] = ['loc' => $this->generateUrl('nous_contacter')];
        $urls[] = ['loc' => $this->generateUrl('donnees_personnelles')];

        // On ajoute les urls dynamiques pour les Produits
        foreach ($this->getDoctrine()->getRepository(Produit::class)->findAll() as $produit) {
            $images = [
                'loc' => '/uploads/images/' . $produit->getImageFilename(),
                'title' => $produit->getTitre()
            ];

            $urls[] = [
                'loc' => $this->generateUrl('affichage_produit',[
                    //'slug' => $produit->getSlug(),
                    'id'   => $produit->getId()
                ]),
                'image' => $images,
                'lastmod' => $produit->getUpdatedAt()->format('Y-m-d')
            ];
        }

        // On ajoute les urls dynamiques pour les Articles
        foreach ($this->getDoctrine()->getRepository(Article::class)->findAll() as $article) {
//            $images = [
//                'loc' => '/uploads/images/' . $article->getImageFilename(),
//                'title' => $produit->getTitre()
//            ];

            $urls[] = [
                'loc' => $this->generateUrl('affichage_produit',[
                    //'slug' => $article->getSlug(),
                    'id'   => $article->getId()
                ]),
                //'image' => $images,
                'lastmod' => $article->getUpdatedAt()->format('Y-m-d')
            ];
        }

        // Créer la réponse
        $response = new Response(
        // RENDER VIEW (renders a template and returns its content, create Response after)
        // et non RENDER (renders a template, returns content, creates response)
            $this->renderView('sitemap/index.html.twig', compact('urls', 'hostname')),
            200
        );

        // Ajout des headers
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;
    }
}

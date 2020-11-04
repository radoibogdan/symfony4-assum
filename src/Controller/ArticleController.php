<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        ArticleRepository $articleRepository)
    {
        $annee_en_cours = date('Y');
        $list_articles = $paginator->paginate(
            $articleRepository->findAllQuery(),
            $request->query->getInt('page',1),
            6
        );
        return $this->render('article/liste.html.twig', [
            'list_articles' => $list_articles,
            'annee_en_cours'=> $annee_en_cours,
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }

    /**
     * @Route ("/article/{id}", name="affichage_article")
     * @param Article $article
     * @param ArticleRepository $articleRepository
     * @return Response;
     */
    public function show(Article $article, ArticleRepository $articleRepository)
    {
        return $this->render('article/affichage.html.twig',[
           'article' => $article,
            'dernier_article' => $articleRepository->findLastArticlePublished()[0]
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SearchArticleFormType;
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
    public function index(PaginatorInterface $paginator, Request $request, ArticleRepository $articleRepository)
    {
        $form = $this->createForm(SearchArticleFormType::class);
        $search = $form->handleRequest($request);

        $list_articles = $paginator->paginate(
            $articleRepository->findAllQuery(),
            $request->query->getInt('page',1),
            6
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $list_articles = $paginator->paginate(
                $articleRepository->search($search->get('mots')->getData()),
                $request->query->getInt('page',1),
                6
            );
        }

        return $this->render('article/liste.html.twig', [
            'list_articles' => $list_articles,
            'formSearchArticle' => $form->createView()
        ]);
    }

    /**
     * @Route ("/article/{id}", name="affichage_article")
     * @param Article $article
     * @return Response;
     */
    public function show(Article $article)
    {
        return $this->render('article/affichage.html.twig',[
           'article' => $article
        ]);
    }
}

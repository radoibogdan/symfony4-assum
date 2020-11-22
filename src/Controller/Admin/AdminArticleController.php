<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/article", name="admin_article_")
 */
class AdminArticleController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository)
    {
        $list_articles = $articleRepository->findAll();
        return $this->render('admin_article/liste.html.twig', [
            'list_articles' => $list_articles
        ]);
    }

    /**
     * @Route ("/ajouter", name="add")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param TagAwareAdapterInterface $cache
     * @return Response
     */
    public function add(EntityManagerInterface $entityManager, Request $request, TagAwareAdapterInterface $cache)
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->setAuteur($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success','L\'article a été ajouté dans la base de données.');
            // suppréssion du cache dans le footer
            $cache->invalidateTags(['footer_article']);
            return $this->redirectToRoute('admin_article_liste');
        }
        return $this->render('admin_article/add.html.twig',[
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Article $article
     * @param Request $request
     * @return Response
     * @Route ("{id}/edit", name="edit")
     */
    public function edit(EntityManagerInterface $entityManager, Article $article, Request $request)
    {
        // Le fait de mettre Article comme argument va récupérer le bon Article de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Les modifications apportées à l\'article ont été enregistrées!');
            return $this->redirectToRoute('admin_article_liste');
        }
        return $this->render('admin_article/edit.html.twig', [
            'article' => $article, // pour rajouter des informations en plus du formulaire
            'articleForm' => $form->createView()
        ]);
    }
    /**
     * @param EntityManagerInterface $entityManager
     * @param Article $article
     * @param Request $request
     * @Route ("{id}/delete", name="delete")
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager, Article $article, Request $request) {
        // Le fait de mettre Article comme argument va récupérer le bon Article de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData(). Les modifications sont faites automatiquement
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('danger','L\'article a été supprimé.');
            return $this->redirectToRoute('admin_article_liste');
        }
        return $this->render('admin_article/delete.html.twig', [
            'article' => $article, // pour rajouter des informations en plus du formulaire
            'deleteForm' => $form->createView()
        ]);
    }
}

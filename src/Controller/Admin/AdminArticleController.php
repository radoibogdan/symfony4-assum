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
     * Shows all the news articles
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
     * Create a news article
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

        // If form is submitted and valid => Insert news article in the database
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->setAuteur($this->getUser()); // get current user and register as author
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success','L\'article a été ajouté dans la base de données.');
            // Delete footer cache
            $cache->invalidateTags(['footer_article']);
            return $this->redirectToRoute('admin_article_liste');
        }
        return $this->render('admin_article/add.html.twig',[
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * Edit a news article
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
        // If form is submitted and valid => Edit the news article in the database
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
     * Delete confirmation form for article
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
        // if checkbox ix clicked then delete article
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

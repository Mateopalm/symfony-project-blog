<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Form\ModificationFormType;
use App\Form\RecetteFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    // Page d'accueil
    #[Route('/', name: 'home')]
    public function Article(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Page détail de l'article
    #[Route('/article/{id}', name: 'article_detail')]
    public function articleDetail($id, Request $request, EntityManagerInterface $entityManager, ArticleRepository $repo): Response
    {
        // Récupération des détails de l'article
        $article = $repo->find($id);

        // Création d'un commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireFormType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupère l'utilisateur
            $commentaire->setUser($this->getUser());
            $commentaire->setArticle($article);
            $commentaire->setCreationDate(new DateTime());
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('article/detail.html.twig', [
            'article' => $article,
            'commentaireForm' => $form->createView(),
        ]);
    }

    // Page des recettes
    #[Route('/article', name: 'recettes')]
    public function articles(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Gestion de mes articles et commentaires
    #[Route('/mapage', name: 'ma_page')]
    public function mesArticles(ArticleRepository $repo): Response
    {
        $articles = $repo->findBy(array('user' => $this->getUser()),array('creation_date' => 'DESC'));

        return $this->render('article/user.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Suppression de mes articles
    #[Route('/mapage/{id}', name: 'ma_page_delete')]
    public function mesArticleDelete(ArticleRepository $repo, EntityManagerInterface $entityManager, Article $article): Response
    {
        // Supprime l'article
        $repo->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('ma_page');
    }

    // Suppression des commentaires
    #[Route('/mapagecom/{id}', name: 'ma_page_delete_com')]
    public function mesArticleDeleteCommentaire(CommentaireRepository $repo, EntityManagerInterface $entityManager, Commentaire $commentaire): Response
    {
        // Supprime le commentaire
        $repo->remove($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('ma_page');
    }

    // Modification article
    #[Route('/ma_page_modif/{id}', name: 'ma_page_modif')]
    public function mesArticlesModif($id, Request $request, ArticleRepository $repo, EntityManagerInterface $entityManager, Article $article): Response
    {
        $article = $repo->find($id);

        $form = $this->createForm(ModificationFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // L'article garde le même user et la même date de création
            $article->setUser($this->getUser());
            $article->setCreationDate(new DateTime());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('ma_page');
        }
    
        return $this->render('article/modification.html.twig', [
            'article' => $article,
            'modificationForm' => $form->createView(),
        ]);
    }



    // Page de création des articles
    #[Route('/create_article', name: 'create_recette')]
    public function createArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(RecetteFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère l'user actuel
            $article->setUser($this->getUser());
            $article->setCreationDate(new DateTime());
            $entityManager->persist($article);
            $entityManager->flush();
            // // do anything else you need here, like send an email

            return $this->redirectToRoute('recettes');
        }

        return $this->render('article/creation.html.twig', [
            'recetteForm' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Form\RecetteFormType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function Article(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

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
            // do anything else you need here, like send an email

            return $this->redirect($request->getUri());
        }

        return $this->render('article/detail.html.twig', [
            'article' => $article,
            'commentaireForm' => $form->createView(),
        ]);
    }

    #[Route('/article', name: 'recettes')]
    public function articles(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Gestion de mes articles et commentaires
    #[Route('/article', name: 'ma_page')]
    public function mesArticles(ArticleRepository $repo): Response
    {
        $article = $repo->findBy();

        return $this->render('article/user.html.twig', [
            'article' => $article,
        ]);
    }

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

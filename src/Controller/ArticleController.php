<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function articleDetail($id, ArticleRepository $repo): Response
    {
        $article = $repo->find($id);

        return $this->render('article/detail.html.twig', ['article' => $article]);
    }

    #[Route('/article', name: 'recettes')]
    public function articles(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article', name: 'mes_recettes')]
    public function mesArticles(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/create_article', name: 'create_recette')]
    public function createArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(RecetteFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ??
            $article->setUser($this->getUser());
            $article->setCreationDate(new DateTime());
            $entityManager->persist($article);
            $entityManager->flush();
            // // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('article/creation.html.twig', [
            'recetteForm' => $form->createView(),
        ]);
    }
}

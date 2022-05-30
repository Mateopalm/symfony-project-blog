<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(ArticleRepository $repo, Request $request, EntityManagerInterface $entityManager): Response
    {
        // On affiche les articles
        $articles = $repo->findAll();

        return $this->render('admin/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Suppression article
    #[Route('/admin_delete/{id}', name: 'admin_delete')]
    public function adminDelete(ArticleRepository $repo, EntityManagerInterface $entityManager, Article $article): Response
    {
        // Supprime l'article
        $repo->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    // Modification article
    #[Route('/admin_modif/{id}', name: 'admin_modif')]
    public function adminModif(ArticleRepository $repo, EntityManagerInterface $entityManager, Article $article): Response
    {
        // Supprime l'article
        $repo->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    #[Route('/admin_contact', name: 'admin_contact')]
    public function adminContact(): Response
    {
        return $this->render('admin/contact.html.twig');
    }
}
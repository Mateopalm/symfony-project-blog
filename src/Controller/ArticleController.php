<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

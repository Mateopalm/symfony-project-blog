<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Form\ModificationFormType;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    // Page principale
    #[Route('/admin', name: 'admin')]
    public function admin(ArticleRepository $repo): Response
    {
        // On affiche les articles
        $articles = $repo->findAll();

        return $this->render('admin/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Suppression d'un article
    #[Route('/admin_delete/{id}', name: 'admin_delete')]
    public function adminDelete(ArticleRepository $repo, EntityManagerInterface $entityManager, Article $article): Response
    {
        // Supprime l'article
        $repo->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    // Modification article
    #[Route('/admin_modif/{id}', name: 'admin_modif')]
    public function adminModif($id, Request $request, ArticleRepository $repo, EntityManagerInterface $entityManager, Article $article): Response
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

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/modification.html.twig', [
            'article' => $article,
            'modificationForm' => $form->createView(),
        ]);
    }

    // Page des messages
    #[Route('/admin_contact', name: 'admin_contact')]
    public function adminContact(ContactRepository $repo): Response
    {
        // On affiche les articles
        $contact = $repo->findAll();

        return $this->render('admin/contact.html.twig', [
            'contact' => $contact,
        ]);
    }

    // Suppression du message
    #[Route('/admin_contact_delete/{id}', name: 'admin_contact_delete')]
    public function adminContactDelete(ContactRepository $repo, EntityManagerInterface $entityManager, Contact $contact): Response
    {
        // Supprime le message
        $repo->remove($contact);
        $entityManager->flush();

        return $this->redirectToRoute('admin_contact');
    }
}
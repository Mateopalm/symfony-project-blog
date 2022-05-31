<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    // Pour hash le mot de passe
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }


    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user
            ->setUsername('user')
            ->setPassword($this->hasher->hashPassword($user, 'Motdepasse'))
            ->setEmail('user@ex.com');
        $manager->persist($user);
        $manager->flush();

        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, 'Motdepasse'))
            ->setEmail('admin@ex.com');
        $manager->persist($admin);
        $manager->flush();

        $creationDate = new DateTime();
        // $articles = [];

        $image = 'images/logo.png';
        $title = 'monTitre';
        $description = 'Une description sans importance';

        for ($j=0; $j < 5; $j++) { 

            $category = new Category();
            if ($j == 0) {
                $category
                ->setName('Plat');
                $image = 'images/burger.jpg';
                $title = 'Mon plat principal';
                $description = 'Voici un burger, comme les 4 autres qui vont suivre';
            } elseif ($j == 1) {
                $category
                ->setName('Entrée');
                $image = 'images/bruschetta.jpg';
                $title = 'Une entrée légère';
                $description = 'Cette entrée est inconnue au bataillon';
            } elseif ($j == 2) {
                $category
                ->setName('Dessert');
                $image = 'images/cookies.jpg';
                $title = 'Un bon dessert';
                $description = 'Personne ne mange sérieusement de cookies au dessert, c\'est plus proche de la pâtisserie que du véritable dessert en réalité';
            } elseif ($j == 3) {
                $category
                ->setName('Apéritif');
                $image = 'images/aperitif-1.jpg';
                $title = 'apéro un jour apéro TOUJOURS';
                $description = 'Apéritifs communs pour soirée';
            } else {
                $category
                ->setName('Pâtisserie');
                $image = 'images/cake.jpg';
                $title = 'De la gourmandise';
                $description = 'Rien de spécial, mais ce texte sera plus long que les autres, afin que l\'on puisse gérer les descriptions abusées pour des plats quelconques, après il s\'agira quand même de recettes donc c\'est assez normal de s\'occuper de ce genre de cas';
            }

            $manager->persist($category);
            $manager->flush();

            for ($i = 0; $i < 5; $i++) {
                $article = new Article();
                $article
                    ->setTitle($title . ' ' . $i)
                    ->setDescription($description)
                    ->setImage($image)
                    ->setCreationDate($creationDate)
                    ->setCategory($category)
                    ->setUser($user);
    
                $manager->persist($article);
                $manager->flush();
                // array_push($articles, $article);
            }
        }

        //$manager->flush();
    }
}

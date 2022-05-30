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
            ->setUsername('test')
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

        for ($j=0; $j < 5; $j++) { 

            $category = new Category();
            if ($j == 0) {
                $category
                ->setName('Plat');
            } elseif ($j == 1) {
                $category
                ->setName('Entrée');
            } elseif ($j == 2) {
                $category
                ->setName('Dessert');
            } elseif ($j == 3) {
                $category
                ->setName('Apéritif');
            } else {
                $category
                ->setName('Pâtisserie');
            }

            $manager->persist($category);
            $manager->flush();

            for ($i = 0; $i < 5; $i++) {
                $article = new Article();
                $article
                    ->setTitle('title' . $i)
                    ->setDescription('description' . $i)
                    ->setImage('images/plat.jpg')
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

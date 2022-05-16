<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $articles = [];

        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article
                ->setTitle('title' . $i)
                ->setDescription('description' . $i);

            $manager->persist($article);
            $manager->flush();
            array_push($articles, $article);
        }

        //$manager->flush();
    }
}

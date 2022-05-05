<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $state = ['brouillon', 'publier'];

        for ($i = 1; $i <= 10; $i++){
            $article = new Article();
            $article->setTitre("Article n° " .$i);
            $article->setContenu("Ceci est le contenu de l'article");
            $article->setState($state[array_rand($state)]);

            $date = new \DateTime();
            $date->modify('-'.$i.' days');

            $article->setDateCreation($date);

            $this->addReference('article-' . $i, $article);

            $manager->persist($article);
        }

        $manager->flush();
    }
}

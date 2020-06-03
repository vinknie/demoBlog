<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // ON utilise la bibliothèque FAKER qui permet d'nvoyer des fausses données aléatoire dans la BDD
        // On a demandé à composer d'installer cette librairie sur notre application

        // Création de 3 catégories
        for($i = 1; $i <= 3; $i++)
        {
            // Nous avons besoin d'un objet $category vide afin de renseigner de nouvelles catégories
            $category = new Category;

            // On appel les setteurs de l'entité Category
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category); // on garde en mémoire les objets $category

            // Création de 4 à 6 articles
            for($j = 1; $j <= mt_rand(4,6); $j++)
            {
                // Nous avons besoin d'un objet $article vide afin de créer et d'insérer de nouveaux articles en BDD
                $article = new Article;

                // On demande à FAKER de créer 5 paragraphes aléatoire pour nos nouveaux articles
                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                // On renseigne tout les setteurs de la classe Article grace aux méthodes de la libraries FAKER (phrase aléatoire (sentence), image aléatoire (imageUrl()) etc...)
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months')) // cr&ation de date d'article, d'il y a 6 mois à aujourd'hui
                        ->setCategory($category); // on rensigne la clé étrangère qui permet de relier les articles aux catégories

                $manager->persist($article);

                // Création de 4 à 10 commentaires
                for($k = 1; $k <= mt_rand(4,10); $k++)
                {
                    $comment = new Comment;

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    $now = new \DateTime(); // objet dateTime avec l'heure et la date du jour
                    $interval = $now->diff($article->getCreatedAt()); // représente entre maintenant et la date de création de l'article (timestamp)
                    $days = $interval->days; // nombre de jour entre maintenant et la date de création de l'article
                    $minimum = '-' . $days . ' days'; /* - 100 days entre la date de création de l'article et maintenant */

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article); // on relie (clé étrangère) nos commenataires aux articles

                    $manager->persist($comment);
                }

            }

        }

        $manager->flush();

    }
}

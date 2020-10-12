<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\ORM\Mapping\PostRemove;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CommentFixtures extends Fixture
{

    /**
     * création d'objets Post et Comment en base de données
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        //instanciation d'un objet issu du bundle Faker pour créer automatiquement des données
        $faker = Factory::create('fr_FR');
        
        //instanciation de 5 objets Post et Comment avec les données de la librairie Faker
        for($i=1; $i<6; $i++)
        {
            $post = new Post();
            $post
                ->setTitle($faker->words(3, true))
                ->setContent($faker->sentences(5, true));

            $comment = new Comment();
            $comment->setUsername($faker->firstName)
                ->setContent($faker->sentences(3, true))
                ->setPost($post);

            $manager->persist($post);
            $manager->persist($comment);

        }
        $manager->flush();
    }
}

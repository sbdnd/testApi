<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiPostController extends AbstractController
{
    /**
     * Récupère les posts et les retourne au format Json
     * 
     * @Route("/api/post", name="api_post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository, SerializerInterface $serializer)
    {
        return $this->json($postRepository->findAll(), 200, [], ['groups'=>'show_post']);
    }

    /**
     * Lit une requête http cotenant un post en Json
     * Désérialize en objet Post et enregistre en BDD
     * Retourne une réponse http avec le post au format Json (201 OK)
     * 
     * @Route("/api/post", name="api_post_send", methods={"POST"})
     *
     */
    public function send(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        try
        {
            $jsonSend = $request->getContent();
            $post = $serializer->deserialize($jsonSend, Post::class, 'json');
            $errors = $validator->validate($post);
            
            //S'il y a des erreurs, retourne tableau des erreurs en json pour traitement par le client
            if(count($errors)>0)
            {
                return $this->json($errors, 400);
            }

            $em->persist($post);
            $em->flush();
            return $this->json($post, 201, [], ['groups'=>'show_post']);
        }
        catch(NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }
}

<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
}

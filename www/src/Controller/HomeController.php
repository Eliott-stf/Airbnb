<?php

namespace App\Controller;

use JulienLinard\Core\Controller\Controller;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Router\Response;
use JulienLinard\Doctrine\EntityManager;
use App\Entity\Post;
use App\Repository\PostRepository;

class HomeController extends Controller
{
    // 1. Injection de l'EntityManager
    public function __construct(
        private EntityManager $em
    ) {}

    #[Route("/", name:"app_index")]
    public function index(): Response
    {
        // 2. Création du Repository pour l'entité Post
        /** @var PostRepository $postRepo */
        $postRepo = $this->em->createRepository(PostRepository::class, Post::class);
        
        // 3. Récupération de tous les posts
        // Si vous avez une méthode findAll() dans PostRepository, utilisez-la.
        // Sinon, $postRepo->findBy([]) devrait fonctionner.
        $posts = $postRepo->findAll(); 

        $data = [
            "title" => "Accueil - Logements",
            "posts" => $posts // On passe la liste des posts à la vue
        ];

        return $this->view("home/index", $data);
    }
}
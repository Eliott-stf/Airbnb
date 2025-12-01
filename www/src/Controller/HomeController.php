<?php

namespace App\Controller;

use JulienLinard\Core\Controller\Controller;
use JulienLinard\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/", name:"app_index")]
    public function index()
    {
        $data = [
            "title" => "Bienvenue",
            "nom" => "Doe",
            "prenom" => "John"
        ];

        return $this->view("home/index", $data);
    }
}
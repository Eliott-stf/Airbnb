<?php

/**
 * ============================================
 * AUTH CONTROLLER
 * ============================================
 * 
 * Contrôleur pour l'authentification (login, register, logout)
 * Utilise AuthManager pour gérer l'authentification
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\UserRepository;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Form\Validator;
use JulienLinard\Core\Session\Session;
use JulienLinard\Core\View\ViewHelper;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;
use JulienLinard\Auth\Middleware\GuestMiddleware;

class AuthController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private EntityManager $em,
        private Validator $validator
    ) {}
    
    /**
     * Affiche le formulaire de connexion
     * TODO: Middleware
     * CONCEPT : Route protégée par GuestMiddleware (seulement pour les utilisateurs non authentifiés)
     */
    #[Route(path: '/login', methods: ['GET'], name: 'login')]
    public function loginForm(): Response
    {
        return $this->view('auth/login', [
            "csrf_token" => ViewHelper::csrfToken()
        ]);
    }
    /**
     * Affiche le formulaire de d'inscription
     */
     #[Route(path: '/register', methods: ['GET'], name: 'register')]
    public function registerForm(): Response
    {
        return $this->view('auth/register', [
            "csrf_token" => ViewHelper::csrfToken()
        ]);
    }
}
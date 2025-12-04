<?php

/**
 * ============================================
 * POST REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité User
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Doctrine\Repository\EntityRepository;

class PostRepository extends EntityRepository
{
    public function __construct(EntityManager $em, string $entityClass = Post::class)
    {
         parent::__construct(
            $em->getConnection(),
            $em->getMetadataReader(),
            $entityClass
        );
    }

    /**
     * méthode qui récupère tout les todo d'un utilisateur grace a son id 
     * @param int $userId
     * @return array Liste des todos 
     */
    public function findbyUserId(int $userId): array
    {
        return $this->findBy(
            ["user_id" => $userId]
        );
    }
}
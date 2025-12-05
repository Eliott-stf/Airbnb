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
 * Méthode qui récupère tous les posts d'un utilisateur grâce à son id
 * @param int $userId
 * @param array|null $orderBy Ordre de tri
 * @return array Liste des posts
 */
public function findByUserId(int $userId, ?array $orderBy = null): array
{
    return $this->findBy(
        ["user_id" => $userId],
        $orderBy ?? ["created_at" => "DESC"]
    );
}

/**
 * Méthode qui récupère un post grâce à son id et qui appartient à un utilisateur spécifique
 * @param int $id ID du post
 * @param int $userId ID de l'utilisateur
 * @return Post|null Retourne l'objet Post ou null si non trouvé ou pas accès
 */
public function findByIdAndUserId(int $id, int $userId): ?Post
{
    $post = $this->find($id);

    if (!$post || $post->user_id !== $userId) {
        return null;
    }

    return $post;
}

}
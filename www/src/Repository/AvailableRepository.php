<?php

/**
 * ============================================
 * EQUIPMENT REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité available
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Available;
use JulienLinard\Doctrine\Repository\EntityRepository;

class AvailableRepository extends EntityRepository
{
 /**
 * Méthode qui récupère un post grâce à son id et qui appartient à un utilisateur spécifique
 * @param int $id ID du post
 * @param int $userId ID de l'utilisateur
 * @return Post|null Retourne l'objet Post ou null si non trouvé ou pas accès
 */
public function findByIdAndUserId(int $id, int $userId): ?Available
{
    $post = $this->find($id);

    if (!$post || $post->user_id !== $userId) {
        return null;
    }

    return $post;
}
}

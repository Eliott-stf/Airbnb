<?php

/**
 * ============================================
 * TYPE REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité type
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Doctrine\Repository\EntityRepository;

class TypeRepository extends EntityRepository
{
    public function __construct(EntityManager $em, string $entityClass = Post::class)
    {
        parent::__construct(
            $em->getConnection(),
            $em->getMetadataReader(),
            $entityClass
        );
    }
}

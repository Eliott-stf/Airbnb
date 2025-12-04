<?php

/**
 * ============================================
 * CATEGORY REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité category
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */


declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Doctrine\Repository\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function __construct(EntityManager $em, string $entityClass = Category::class)
    {
        parent::__construct(
            $em->getConnection(),
            $em->getMetadataReader(),
            $entityClass
        );
    }
}

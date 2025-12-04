<?php

/**
 * ============================================
 * EQUIPMENT REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité equipment
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Equipment;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Doctrine\Repository\EntityRepository;

class EquipmentRepository extends EntityRepository
{
    public function __construct(EntityManager $em, string $entityClass = Equipment::class)
    {
         parent::__construct(
            $em->getConnection(),
            $em->getMetadataReader(),
            $entityClass
        );
    }

   public function findAllByCategory(): array
{
    
    $equipments = $this->findBy([], ['category_id' => 'ASC', 'label' => 'ASC']);

    $grouped = [];

    foreach ($equipments as $equipment) {
        $grouped[$equipment->category_id][] = $equipment;
    }

    return $grouped;
}
    
}
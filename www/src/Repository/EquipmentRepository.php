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
    private EntityManager $em;

    public function __construct(EntityManager $em, string $entityClass = Equipment::class)
    {
        $this->em = $em; // Stocker l'Entity Manager

        parent::__construct(
            $em->getConnection(),
            $em->getMetadataReader(),
            $entityClass
        );
    }

    public function findByPostId(int $postId): array
    {
        $sql = "SELECT e.*
                FROM equipment e
                JOIN equipment_post pe ON e.id = pe.equipment_id
                WHERE pe.post_id = ?";


        $rows = $this->connection->fetchAll($sql, [$postId]);
        return array_map([$this, 'hydrate'], $rows);
    }
}

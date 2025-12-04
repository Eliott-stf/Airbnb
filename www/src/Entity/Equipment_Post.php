<?php

/**
 * ============================================
 * ENTITÉ Equipment_Post
 * ============================================
 * 
 * Entité de mapping entre Equipment et Post 
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\ManyToOne;

#[Entity(table: 'equipment_post')]
class EquipmentPost
{
    #[Id]
    #[Column(type:"integer")]
    public int $post_id;
    
    #[Id]
    #[Column(type:"integer")]
    public int $equipment_id;
    
}
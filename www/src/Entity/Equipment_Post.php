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
    // ID 1 : Clé Étrangère de Post, faisant partie de la Clé Primaire
    #[Id]
    #[Column(type:"integer")]
    public int $post_id;
    
    // ID 2 : Clé Étrangère de Equipment, faisant partie de la Clé Primaire
    #[Id]
    #[Column(type:"integer")]
    public int $equipment_id;
    
    // Vous pouvez ajouter ici les propriétés d'objet si votre ORM le gère
    // #[ManyToOne(targetEntity: Post::class)]
    // public Post $post;
    
    // #[ManyToOne(targetEntity: Equipment::class)]
    // public Equipment $equipment;
    
    // Si vous aviez besoin d'une colonne supplémentaire, par exemple:
    // #[Column(type: "integer", nullable: true)]
    // public ?int $quantity = null;
}
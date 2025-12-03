<?php

/**
 * ============================================
 * ENTITÉ EQUIPMENT
 * ============================================
 * 
 * Entité pour lister les equipements dont dispose un logement 
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
#[Entity(table:'type')]
class Category 
{
    #[Id]
    #[Column(type:"integer", autoIncrement: true)]
    public ?int $id = null;

    #[Column(type:"string", length:70)]
    public string $label; 

}
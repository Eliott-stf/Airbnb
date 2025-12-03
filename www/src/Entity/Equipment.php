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

use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\ManyToMany;
use JulienLinard\Doctrine\Mapping\OneToMany;

#[Entity(table:'equipment')]
class Equipment 
{
    #[Id]
    #[Column(type:"integer", autoIncrement: true)]
    public ?int $id = null;

    #[Column(type:"string", length:50)]
    public string $label; 

    #[Column(type:"integer", nullable:true)]
    public int $category_id = null;

    #[OneToMany(targetEntity:Category::class, inversedBy: 'equipment')]
    public ?Category $category = null; 
}
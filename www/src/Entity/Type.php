<?php

/**
 * ============================================
 * ENTITÉ TYPE
 * ============================================
 * 
 * Entité pour répertoriant le type de logement : Maison, appartement...
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;

#[Entity(table:'type')]
class Type 
{
    #[Id]
    #[Column(type:"integer", autoIncrement: true)]
    public ?int $id = null;

    #[Column(type:"string")]
    public string $label; 

}

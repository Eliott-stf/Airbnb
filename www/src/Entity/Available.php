<?php

/**
 * ============================================
 * ENTITÉ AVAILABLE
 * ============================================
 * 
 * Entité available pour stocker le debut/fin d'une location d'un bien 
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\OneToMany;

#[Entity(table:'available')]
class Available
{
    #[Id]
    #[Column(type:"integer", autoIncrement:true)]
    public int $id;

    #[Column(type:"datetime")]
    public DateTime $date_in;

    #[Column(type:"datetime")]
    public DateTime $date_out;

    #[Column(type:"integer")]
    public int $post_id;

    #[OneToMany(targetEntity:Post::class, invertedBy: 'available')]
    public ?Post $post=null;
}
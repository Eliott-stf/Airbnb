<?php

/**
 * ============================================
 * ENTITÉ IMAGE
 * ============================================
 * 
 * Entité image pour stocker les photos des posts
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\ManyToOne;

#[Entity(table:'image')]
class Image 
{
    #[Id]
    #[Column(type:"integer", autoIncrement: true)]
    public int $id;

    #[Column(type:"string", length:255)]
    public string $name;

    #[Column(type:"text")]
    public string $media_path;

    #[Column(type:"integer", nullable:true)]
    public ?int $post_id = null;

    #[ManyToOne(targetEntity:Post::class, inversedBy:'image')]
    public ?Post $post = null; 
}
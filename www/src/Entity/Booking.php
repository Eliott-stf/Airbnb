<?php

/**
 * ============================================
 * ENTITÉ BOOKING
 * ============================================
 * 
 * Entité pour y inserer ses dates de réservation
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\ManyToOne;

#[Entity(table:'booking')]
class Booking
{
    #[Id]
    #[Column(type:"integer", autoIncrement:true, nullable:true)]
    public ?int $id= null;

     #[Column(type:"datetime")]
    public DateTime $date_in;

     #[Column(type:"datetime")]
    public DateTime $date_out;

    #[Column(type:"integer", default: 1)]
    public int $guest_count = 1;

    #[Column(type:"integer")]
    public int $post_id;

    #[Column(type:"integer")]
    public int $user_id; 

    #[ManyToOne(targetEntity:User::class, inversedBy:'booking')]
    public ?User $user = null;

    #[ManyToOne(targetEntity:Post::class, inversedBy:'booking')]
    public ?Post $post = null;
}
<?php

/**
 * ============================================
 * ENTITÉ AVAILABLE
 * ============================================
 * * Entité available pour stocker le debut/fin d'une location d'un bien 
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\ManyToOne; // Correction de OneToMany à ManyToOne

#[Entity(table:'available')]
class Available
{
    #[Id]
    // CORRECTION PHP TYPAGE: Utilise ?int pour autoriser la valeur null par défaut (avant persistance)
    #[Column(type:"integer", nullable: true, autoIncrement:true)]
    public ?int $id = null;

    // Dates du formulaire : doivent être converties en objets DateTime avant l'affectation
    #[Column(type:"datetime")]
    public DateTime $date_in;

    #[Column(type:"datetime")]
    public DateTime $date_out;

    // Clé étrangère simple (si vous souhaitez gérer la relation par ID manuellement)
    #[Column(type:"integer")]
    public int $post_id;

    // RELATION CORRIGÉE: ManyToOne (Une disponibilité appartient à UN seul Post)
    // L'attribut 'inversedBy' (correct) pointe vers le nom de la propriété dans l'entité Post.
    #[ManyToOne(targetEntity: Post::class, inversedBy: 'available')]
    public ?Post $post = null;
}
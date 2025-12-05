<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\ManyToOne;
use JulienLinard\Doctrine\Mapping\ManyToMany;

#[Entity(table: 'post')]
class Post
{
    #[Id]
    #[Column(type: "integer", autoIncrement: true)]
    public ?int $id = null;

    #[Column(type: "string", length: 255)]
    public string $title;

    #[Column(type: "text")]
    public string $description;

    #[Column(type: "integer")]
    public int $price_day;

    #[Column(type: "string", length: 100)]
    public string $country;

    #[Column(type: "string", length: 255)]
    public string $address;

    #[Column(type: "integer")]
    public int $postal_code;

    #[Column(type: "string", length: 150)]
    public string $city;

    #[Column(type: "datetime", nullable: true)]
    public ?DateTime $created_at = null;

    #[Column(type: "datetime", nullable: true)]
    public ?DateTime $updated_at = null;

    #[Column(type: "string", length: 255, nullable: true)]
    public ?string $media_path = null;

    #[Column(type: "integer")]
    public int $bed_count;

    #[Column(type: "integer")]
    public int $max_capacity;

    #[Column(type: "integer")]
    public int $user_id;

    #[Column(type: "integer")]
    public int $type_id;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'post')]
    public ?User $user = null;

    #[ManyToOne(targetEntity: Type::class, inversedBy: 'post')]
    public ?Type $type = null;

    #[ManyToMany(targetEntity: Equipment::class, joinTable: "post_equipment")]
    public array $equipments = [];
}

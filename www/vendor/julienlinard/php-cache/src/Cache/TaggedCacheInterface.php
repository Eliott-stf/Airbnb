<?php

namespace JulienLinard\Cache;

/**
 * Interface pour le cache avec support des tags
 */
interface TaggedCacheInterface extends CacheInterface
{
    /**
     * Ajoute des tags à une clé de cache
     *
     * @param string|array $tags Tag(s) à ajouter
     * @return TaggedCacheInterface Instance pour le chaînage
     */
    public function tags(string|array $tags): TaggedCacheInterface;

    /**
     * Invalide toutes les clés avec un tag donné
     *
     * @param string|array $tags Tag(s) à invalider
     * @return bool True si succès
     */
    public function invalidateTags(string|array $tags): bool;

    /**
     * Récupère toutes les clés associées à un tag
     *
     * @param string $tag Tag à rechercher
     * @return array Tableau de clés
     */
    public function getKeysByTag(string $tag): array;
}


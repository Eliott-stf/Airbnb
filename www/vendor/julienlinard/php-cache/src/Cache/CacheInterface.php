<?php

namespace JulienLinard\Cache;

/**
 * Interface pour tous les drivers de cache
 */
interface CacheInterface
{
    /**
     * Récupère une valeur du cache
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut si la clé n'existe pas
     * @return mixed Valeur en cache ou valeur par défaut
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Stocke une valeur dans le cache
     *
     * @param string $key Clé du cache
     * @param mixed $value Valeur à stocker
     * @param int|null $ttl Time to live en secondes (null = pas d'expiration)
     * @return bool True si succès, false sinon
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool;

    /**
     * Supprime une valeur du cache
     *
     * @param string $key Clé du cache
     * @return bool True si succès, false sinon
     */
    public function delete(string $key): bool;

    /**
     * Vérifie si une clé existe dans le cache
     *
     * @param string $key Clé du cache
     * @return bool True si la clé existe
     */
    public function has(string $key): bool;

    /**
     * Vide tout le cache
     *
     * @return bool True si succès
     */
    public function clear(): bool;

    /**
     * Supprime plusieurs clés en une fois
     *
     * @param array $keys Tableau de clés à supprimer
     * @return int Nombre de clés supprimées
     */
    public function deleteMultiple(array $keys): int;

    /**
     * Récupère plusieurs valeurs en une fois
     *
     * @param array $keys Tableau de clés à récupérer
     * @param mixed $default Valeur par défaut pour les clés manquantes
     * @return array Tableau associatif [key => value]
     */
    public function getMultiple(array $keys, mixed $default = null): array;

    /**
     * Stocke plusieurs valeurs en une fois
     *
     * @param array $values Tableau associatif [key => value]
     * @param int|null $ttl Time to live en secondes
     * @return bool True si succès
     */
    public function setMultiple(array $values, ?int $ttl = null): bool;

    /**
     * Incrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur d'incrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function increment(string $key, int $value = 1): int|false;

    /**
     * Décrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur de décrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function decrement(string $key, int $value = 1): int|false;

    /**
     * Récupère une valeur et la supprime ensuite
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut
     * @return mixed Valeur récupérée ou valeur par défaut
     */
    public function pull(string $key, mixed $default = null): mixed;
}


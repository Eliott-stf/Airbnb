<?php

namespace JulienLinard\Cache;

/**
 * Classe statique de façade pour faciliter l'utilisation du cache
 */
class Cache
{
    /**
     * Instance du gestionnaire
     */
    private static ?CacheManager $manager = null;

    /**
     * Initialise le gestionnaire de cache
     *
     * @param array $config Configuration
     */
    public static function init(array $config = []): void
    {
        self::$manager = CacheManager::getInstance($config);
    }

    /**
     * Récupère le gestionnaire de cache
     *
     * @return CacheManager Instance du gestionnaire
     */
    private static function manager(): CacheManager
    {
        if (self::$manager === null) {
            self::$manager = CacheManager::getInstance();
        }

        return self::$manager;
    }

    /**
     * Récupère une valeur du cache
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut
     * @param string|null $driver Nom du driver
     * @return mixed Valeur en cache ou valeur par défaut
     */
    public static function get(string $key, mixed $default = null, ?string $driver = null): mixed
    {
        return self::manager()->get($key, $default, $driver);
    }

    /**
     * Stocke une valeur dans le cache
     *
     * @param string $key Clé du cache
     * @param mixed $value Valeur à stocker
     * @param int|null $ttl Time to live en secondes
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public static function set(string $key, mixed $value, ?int $ttl = null, ?string $driver = null): bool
    {
        return self::manager()->set($key, $value, $ttl, $driver);
    }

    /**
     * Supprime une valeur du cache
     *
     * @param string $key Clé du cache
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public static function delete(string $key, ?string $driver = null): bool
    {
        return self::manager()->delete($key, $driver);
    }

    /**
     * Vérifie si une clé existe dans le cache
     *
     * @param string $key Clé du cache
     * @param string|null $driver Nom du driver
     * @return bool True si la clé existe
     */
    public static function has(string $key, ?string $driver = null): bool
    {
        return self::manager()->has($key, $driver);
    }

    /**
     * Vide tout le cache
     *
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public static function clear(?string $driver = null): bool
    {
        return self::manager()->clear($driver);
    }

    /**
     * Récupère un driver de cache
     *
     * @param string|null $driver Nom du driver
     * @return CacheInterface Instance du driver
     */
    public static function driver(?string $driver = null): CacheInterface
    {
        return self::manager()->driver($driver);
    }

    /**
     * Récupère un cache avec tags
     *
     * @param string|array $tags Tag(s) à utiliser
     * @param string|null $driver Nom du driver
     * @return TaggedCacheInterface Instance du cache tagué
     */
    public static function tags(string|array $tags, ?string $driver = null): TaggedCacheInterface
    {
        return self::manager()->tags($tags, $driver);
    }

    /**
     * Récupère une valeur et la supprime ensuite
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut
     * @param string|null $driver Nom du driver
     * @return mixed Valeur récupérée ou valeur par défaut
     */
    public static function pull(string $key, mixed $default = null, ?string $driver = null): mixed
    {
        return self::manager()->driver($driver)->pull($key, $default);
    }

    /**
     * Incrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur d'incrémentation
     * @param string|null $driver Nom du driver
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public static function increment(string $key, int $value = 1, ?string $driver = null): int|false
    {
        return self::manager()->driver($driver)->increment($key, $value);
    }

    /**
     * Décrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur de décrémentation
     * @param string|null $driver Nom du driver
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public static function decrement(string $key, int $value = 1, ?string $driver = null): int|false
    {
        return self::manager()->driver($driver)->decrement($key, $value);
    }

    /**
     * Récupère plusieurs valeurs en une fois
     *
     * @param array $keys Tableau de clés
     * @param mixed $default Valeur par défaut
     * @param string|null $driver Nom du driver
     * @return array Tableau associatif [key => value]
     */
    public static function getMultiple(array $keys, mixed $default = null, ?string $driver = null): array
    {
        return self::manager()->driver($driver)->getMultiple($keys, $default);
    }

    /**
     * Stocke plusieurs valeurs en une fois
     *
     * @param array $values Tableau associatif [key => value]
     * @param int|null $ttl Time to live en secondes
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public static function setMultiple(array $values, ?int $ttl = null, ?string $driver = null): bool
    {
        return self::manager()->driver($driver)->setMultiple($values, $ttl);
    }

    /**
     * Supprime plusieurs clés en une fois
     *
     * @param array $keys Tableau de clés
     * @param string|null $driver Nom du driver
     * @return int Nombre de clés supprimées
     */
    public static function deleteMultiple(array $keys, ?string $driver = null): int
    {
        return self::manager()->driver($driver)->deleteMultiple($keys);
    }
}


<?php

namespace JulienLinard\Cache;

use JulienLinard\Cache\Drivers\ArrayCacheDriver;
use JulienLinard\Cache\Drivers\FileCacheDriver;
use JulienLinard\Cache\Drivers\RedisCacheDriver;
use JulienLinard\Cache\Exceptions\DriverException;

/**
 * Gestionnaire principal du cache
 * Factory pour créer et gérer les instances de cache
 */
class CacheManager
{
    /**
     * Instance singleton
     */
    private static ?CacheManager $instance = null;

    /**
     * Drivers de cache enregistrés
     *
     * @var array<string, CacheInterface>
     */
    private array $drivers = [];

    /**
     * Driver par défaut
     */
    private string $defaultDriver = 'array';

    /**
     * Configuration globale
     */
    private array $config = [];

    /**
     * Constructeur privé (singleton)
     *
     * @param array $config Configuration globale
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;
        $this->defaultDriver = $config['default'] ?? 'array';
    }

    /**
     * Récupère l'instance singleton
     *
     * @param array $config Configuration (utilisée uniquement à la première création)
     * @return CacheManager Instance du gestionnaire
     */
    public static function getInstance(array $config = []): CacheManager
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
     * Crée une nouvelle instance (pour les tests)
     *
     * @param array $config Configuration
     * @return CacheManager Nouvelle instance
     */
    public static function create(array $config = []): CacheManager
    {
        return new self($config);
    }

    /**
     * Récupère un driver de cache
     *
     * @param string|null $driver Nom du driver (null = driver par défaut)
     * @return CacheInterface Instance du driver
     * @throws DriverException Si le driver n'existe pas ou ne peut pas être créé
     */
    public function driver(?string $driver = null): CacheInterface
    {
        $driver = $driver ?? $this->defaultDriver;

        // Retourner le driver s'il existe déjà
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver];
        }

        // Créer le driver
        $this->drivers[$driver] = $this->createDriver($driver);

        return $this->drivers[$driver];
    }

    /**
     * Crée une instance de driver
     *
     * @param string $driver Nom du driver
     * @return CacheInterface Instance du driver
     * @throws DriverException Si le driver n'existe pas
     */
    private function createDriver(string $driver): CacheInterface
    {
        $driverConfig = $this->config['drivers'][$driver] ?? [];

        return match ($driver) {
            'array' => new ArrayCacheDriver($driverConfig),
            'file' => new FileCacheDriver($driverConfig),
            'redis' => new RedisCacheDriver($driverConfig),
            default => throw new DriverException($driver, "Driver de cache inconnu: {$driver}"),
        };
    }

    /**
     * Récupère un cache avec tags
     *
     * @param string|array $tags Tag(s) à utiliser
     * @param string|null $driver Nom du driver (null = driver par défaut)
     * @return TaggedCacheInterface Instance du cache tagué
     */
    public function tags(string|array $tags, ?string $driver = null): TaggedCacheInterface
    {
        $tagsArray = is_array($tags) ? $tags : [$tags];
        $cacheDriver = $this->driver($driver);

        return new TaggedCache($cacheDriver, $tagsArray);
    }

    /**
     * Raccourci pour récupérer une valeur
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut
     * @param string|null $driver Nom du driver
     * @return mixed Valeur en cache ou valeur par défaut
     */
    public function get(string $key, mixed $default = null, ?string $driver = null): mixed
    {
        return $this->driver($driver)->get($key, $default);
    }

    /**
     * Raccourci pour stocker une valeur
     *
     * @param string $key Clé du cache
     * @param mixed $value Valeur à stocker
     * @param int|null $ttl Time to live en secondes
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public function set(string $key, mixed $value, ?int $ttl = null, ?string $driver = null): bool
    {
        return $this->driver($driver)->set($key, $value, $ttl);
    }

    /**
     * Raccourci pour supprimer une valeur
     *
     * @param string $key Clé du cache
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public function delete(string $key, ?string $driver = null): bool
    {
        return $this->driver($driver)->delete($key);
    }

    /**
     * Raccourci pour vérifier l'existence d'une clé
     *
     * @param string $key Clé du cache
     * @param string|null $driver Nom du driver
     * @return bool True si la clé existe
     */
    public function has(string $key, ?string $driver = null): bool
    {
        return $this->driver($driver)->has($key);
    }

    /**
     * Raccourci pour vider le cache
     *
     * @param string|null $driver Nom du driver
     * @return bool True si succès
     */
    public function clear(?string $driver = null): bool
    {
        return $this->driver($driver)->clear();
    }

    /**
     * Enregistre un driver personnalisé
     *
     * @param string $name Nom du driver
     * @param CacheInterface $driver Instance du driver
     */
    public function registerDriver(string $name, CacheInterface $driver): void
    {
        $this->drivers[$name] = $driver;
    }

    /**
     * Définit le driver par défaut
     *
     * @param string $driver Nom du driver
     */
    public function setDefaultDriver(string $driver): void
    {
        $this->defaultDriver = $driver;
    }

    /**
     * Récupère le driver par défaut
     *
     * @return string Nom du driver par défaut
     */
    public function getDefaultDriver(): string
    {
        return $this->defaultDriver;
    }
}


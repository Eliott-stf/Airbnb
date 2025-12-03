<?php

namespace JulienLinard\Cache\Drivers;

use JulienLinard\Cache\Exceptions\DriverException;

/**
 * Driver de cache Redis
 * Nécessite l'extension Redis PHP
 */
class RedisCacheDriver extends AbstractCacheDriver
{
    /**
     * Instance Redis
     */
    private ?\Redis $redis = null;

    /**
     * Configuration de connexion
     */
    private array $config;

    /**
     * Constructeur
     *
     * @param array $config Configuration du driver
     * @throws DriverException Si l'extension Redis n'est pas disponible
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!extension_loaded('redis')) {
            throw new DriverException('redis', 'L\'extension Redis n\'est pas installée');
        }

        $this->config = [
            'host' => $config['host'] ?? '127.0.0.1',
            'port' => $config['port'] ?? 6379,
            'timeout' => $config['timeout'] ?? 2.0,
            'password' => $config['password'] ?? null,
            'database' => $config['database'] ?? 0,
            'persistent' => $config['persistent'] ?? false,
            'persistent_id' => $config['persistent_id'] ?? null,
        ];

        $this->connect();
    }

    /**
     * Établit la connexion Redis
     *
     * @throws DriverException Si la connexion échoue
     */
    private function connect(): void
    {
        try {
            $this->redis = new \Redis();

            $connected = false;
            if ($this->config['persistent'] && $this->config['persistent_id'] !== null) {
                $connected = $this->redis->pconnect(
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['timeout'],
                    $this->config['persistent_id']
                );
            } else {
                $connected = $this->redis->connect(
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['timeout']
                );
            }

            if (!$connected) {
                throw new DriverException('redis', 'Impossible de se connecter au serveur Redis');
            }

            // Authentification si nécessaire
            if ($this->config['password'] !== null) {
                if (!$this->redis->auth($this->config['password'])) {
                    throw new DriverException('redis', 'Échec de l\'authentification Redis');
                }
            }

            // Sélectionner la base de données
            if ($this->config['database'] > 0) {
                $this->redis->select($this->config['database']);
            }

            // Options de sérialisation
            $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        } catch (\Throwable $e) {
            throw new DriverException('redis', $e->getMessage(), $e);
        }
    }

    /**
     * Vérifie que la connexion Redis est active
     *
     * @throws DriverException Si la connexion est perdue
     */
    private function ensureConnection(): void
    {
        if ($this->redis === null) {
            $this->connect();
            return;
        }

        try {
            // Ping pour vérifier la connexion
            $this->redis->ping();
        } catch (\Throwable $e) {
            // Reconnexion en cas d'échec
            $this->connect();
        }
    }

    /**
     * Récupère une valeur du cache
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut si la clé n'existe pas
     * @return mixed Valeur en cache ou valeur par défaut
     */
    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $this->ensureConnection();
            $preparedKey = $this->prepareKey($key);

            $value = $this->redis->get($preparedKey);
            
            if ($value === false) {
                return $default;
            }

            return $this->unserializeValue($value);
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Stocke une valeur dans le cache
     *
     * @param string $key Clé du cache
     * @param mixed $value Valeur à stocker
     * @param int|null $ttl Time to live en secondes (null = pas d'expiration)
     * @return bool True si succès, false sinon
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        try {
            $this->ensureConnection();
            $preparedKey = $this->prepareKey($key);
            $serialized = $this->serializeValue($value);

            // Utiliser le TTL fourni ou le TTL par défaut
            $finalTtl = $ttl ?? $this->defaultTtl;

            if ($finalTtl !== null) {
                return $this->redis->setex($preparedKey, $finalTtl, $serialized);
            } else {
                return $this->redis->set($preparedKey, $serialized);
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Supprime une valeur du cache
     *
     * @param string $key Clé du cache
     * @return bool True si succès, false sinon
     */
    public function delete(string $key): bool
    {
        try {
            $this->ensureConnection();
            $preparedKey = $this->prepareKey($key);
            return $this->redis->del($preparedKey) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Vérifie si une clé existe dans le cache
     *
     * @param string $key Clé du cache
     * @return bool True si la clé existe
     */
    public function has(string $key): bool
    {
        try {
            $this->ensureConnection();
            $preparedKey = $this->prepareKey($key);
            return $this->redis->exists($preparedKey) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Vide tout le cache
     *
     * @return bool True si succès
     */
    public function clear(): bool
    {
        try {
            $this->ensureConnection();
            return $this->redis->flushDB();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Incrémente une valeur numérique (optimisé pour Redis)
     *
     * @param string $key Clé du cache
     * @param int $value Valeur d'incrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function increment(string $key, int $value = 1): int|false
    {
        try {
            $this->ensureConnection();
            $preparedKey = $this->prepareKey($key);
            
            if ($value === 1) {
                return $this->redis->incr($preparedKey);
            } else {
                return $this->redis->incrBy($preparedKey, $value);
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Décrémente une valeur numérique (optimisé pour Redis)
     *
     * @param string $key Clé du cache
     * @param int $value Valeur de décrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function decrement(string $key, int $value = 1): int|false
    {
        try {
            $this->ensureConnection();
            $preparedKey = $this->prepareKey($key);
            
            if ($value === 1) {
                return $this->redis->decr($preparedKey);
            } else {
                return $this->redis->decrBy($preparedKey, $value);
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Ferme la connexion Redis
     */
    public function __destruct()
    {
        if ($this->redis !== null) {
            try {
                $this->redis->close();
            } catch (\Throwable $e) {
                // Ignorer les erreurs lors de la fermeture
            }
        }
    }
}


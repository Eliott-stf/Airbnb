<?php

namespace JulienLinard\Cache\Drivers;

/**
 * Driver de cache en mémoire (Array)
 * Utile pour les tests et le développement
 * Attention: Les données sont perdues à la fin du script
 */
class ArrayCacheDriver extends AbstractCacheDriver
{
    /**
     * Stockage en mémoire
     *
     * @var array<string, array{value: string, expires: int|null}>
     */
    private array $storage = [];

    /**
     * Récupère une valeur du cache
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut si la clé n'existe pas
     * @return mixed Valeur en cache ou valeur par défaut
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $preparedKey = $this->prepareKey($key);

        if (!isset($this->storage[$preparedKey])) {
            return $default;
        }

        $item = $this->storage[$preparedKey];

        // Vérifier l'expiration
        if ($item['expires'] !== null && $item['expires'] < time()) {
            unset($this->storage[$preparedKey]);
            return $default;
        }

        try {
            return $this->unserializeValue($item['value']);
        } catch (\Throwable $e) {
            // En cas d'erreur de désérialisation, supprimer l'entrée
            unset($this->storage[$preparedKey]);
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
            $preparedKey = $this->prepareKey($key);
            $serialized = $this->serializeValue($value);

            // Utiliser le TTL fourni ou le TTL par défaut
            $expires = null;
            if ($ttl !== null) {
                $expires = time() + $ttl;
            } elseif ($this->defaultTtl !== null) {
                $expires = time() + $this->defaultTtl;
            }

            $this->storage[$preparedKey] = [
                'value' => $serialized,
                'expires' => $expires,
            ];

            return true;
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
        $preparedKey = $this->prepareKey($key);
        
        if (isset($this->storage[$preparedKey])) {
            unset($this->storage[$preparedKey]);
            return true;
        }

        return false;
    }

    /**
     * Vérifie si une clé existe dans le cache
     *
     * @param string $key Clé du cache
     * @return bool True si la clé existe
     */
    public function has(string $key): bool
    {
        $preparedKey = $this->prepareKey($key);

        if (!isset($this->storage[$preparedKey])) {
            return false;
        }

        $item = $this->storage[$preparedKey];

        // Vérifier l'expiration
        if ($item['expires'] !== null && $item['expires'] < time()) {
            unset($this->storage[$preparedKey]);
            return false;
        }

        return true;
    }

    /**
     * Vide tout le cache
     *
     * @return bool True si succès
     */
    public function clear(): bool
    {
        $this->storage = [];
        return true;
    }

    /**
     * Nettoie les entrées expirées
     *
     * @return int Nombre d'entrées supprimées
     */
    public function cleanExpired(): int
    {
        $count = 0;
        $now = time();

        foreach ($this->storage as $key => $item) {
            if ($item['expires'] !== null && $item['expires'] < $now) {
                unset($this->storage[$key]);
                $count++;
            }
        }

        return $count;
    }
}


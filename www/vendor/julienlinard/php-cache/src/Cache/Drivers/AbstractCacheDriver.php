<?php

namespace JulienLinard\Cache\Drivers;

use JulienLinard\Cache\CacheInterface;
use JulienLinard\Cache\KeyValidator;
use JulienLinard\Cache\ValueSerializer;
use JulienLinard\Cache\Exceptions\InvalidKeyException;
use JulienLinard\Cache\Exceptions\SerializationException;

/**
 * Classe abstraite de base pour tous les drivers de cache
 * Implémente les méthodes communes et la validation
 */
abstract class AbstractCacheDriver implements CacheInterface
{
    /**
     * Préfixe par défaut pour toutes les clés
     */
    protected string $prefix = '';

    /**
     * TTL par défaut en secondes (null = pas d'expiration)
     */
    protected ?int $defaultTtl = null;

    /**
     * Constructeur
     *
     * @param array $config Configuration du driver
     */
    public function __construct(array $config = [])
    {
        $this->prefix = $config['prefix'] ?? '';
        $this->defaultTtl = $config['ttl'] ?? null;
    }

    /**
     * Valide et préfixe une clé
     *
     * @param string $key Clé à valider
     * @return string Clé validée et préfixée
     * @throws InvalidKeyException Si la clé est invalide
     */
    protected function prepareKey(string $key): string
    {
        KeyValidator::validate($key);
        // Utiliser _ comme séparateur au lieu de : pour respecter la validation des clés
        return $this->prefix !== '' ? $this->prefix . '_' . $key : $key;
    }

    /**
     * Sérialise une valeur
     *
     * @param mixed $value Valeur à sérialiser
     * @return string Valeur sérialisée
     * @throws SerializationException Si la sérialisation échoue
     */
    protected function serializeValue(mixed $value): string
    {
        return ValueSerializer::serialize($value);
    }

    /**
     * Désérialise une valeur
     *
     * @param string $serialized Valeur sérialisée
     * @return mixed Valeur désérialisée
     * @throws SerializationException Si la désérialisation échoue
     */
    protected function unserializeValue(string $serialized): mixed
    {
        return ValueSerializer::unserialize($serialized);
    }

    /**
     * Récupère plusieurs valeurs en une fois
     *
     * @param array $keys Tableau de clés à récupérer
     * @param mixed $default Valeur par défaut pour les clés manquantes
     * @return array Tableau associatif [key => value]
     */
    public function getMultiple(array $keys, mixed $default = null): array
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->get($key, $default);
        }
        return $results;
    }

    /**
     * Stocke plusieurs valeurs en une fois
     *
     * @param array $values Tableau associatif [key => value]
     * @param int|null $ttl Time to live en secondes
     * @return bool True si succès
     */
    public function setMultiple(array $values, ?int $ttl = null): bool
    {
        $success = true;
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Supprime plusieurs clés en une fois
     *
     * @param array $keys Tableau de clés à supprimer
     * @return int Nombre de clés supprimées
     */
    public function deleteMultiple(array $keys): int
    {
        $count = 0;
        foreach ($keys as $key) {
            if ($this->delete($key)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Récupère une valeur et la supprime ensuite
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut
     * @return mixed Valeur récupérée ou valeur par défaut
     */
    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->delete($key);
        return $value;
    }

    /**
     * Incrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur d'incrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function increment(string $key, int $value = 1): int|false
    {
        $current = $this->get($key, 0);
        
        if (!is_numeric($current)) {
            return false;
        }

        $newValue = (int)$current + $value;
        
        if ($this->set($key, $newValue)) {
            return $newValue;
        }

        return false;
    }

    /**
     * Décrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur de décrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function decrement(string $key, int $value = 1): int|false
    {
        return $this->increment($key, -$value);
    }
}


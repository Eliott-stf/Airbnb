<?php

namespace JulienLinard\Cache;

use JulienLinard\Cache\Drivers\AbstractCacheDriver;

/**
 * Wrapper pour le cache avec support des tags
 */
class TaggedCache implements TaggedCacheInterface
{
    /**
     * Driver de cache sous-jacent
     */
    private CacheInterface $driver;

    /**
     * Tags actifs pour ce cache
     *
     * @var array<string>
     */
    private array $tags = [];

    /**
     * Préfixe pour les tags
     */
    private const TAG_PREFIX = 'tag_';

    /**
     * Préfixe pour les clés taguées
     */
    private const TAGGED_KEY_PREFIX = 'tagged_';

    /**
     * Constructeur
     *
     * @param CacheInterface $driver Driver de cache
     * @param array $tags Tags initiaux
     */
    public function __construct(CacheInterface $driver, array $tags = [])
    {
        $this->driver = $driver;
        $this->tags = array_unique($tags);
    }

    /**
     * Ajoute des tags à une clé de cache
     *
     * @param string|array $tags Tag(s) à ajouter
     * @return TaggedCacheInterface Instance pour le chaînage
     */
    public function tags(string|array $tags): TaggedCacheInterface
    {
        $tagsArray = is_array($tags) ? $tags : [$tags];
        $this->tags = array_unique(array_merge($this->tags, $tagsArray));
        return $this;
    }

    /**
     * Génère une clé taguée
     *
     * @param string $key Clé originale
     * @return string Clé taguée
     */
    private function getTaggedKey(string $key): string
    {
        if (empty($this->tags)) {
            return $key;
        }

        // Créer un hash des tags pour créer une clé unique
        $sortedTags = $this->tags;
        sort($sortedTags);
        $tagHash = md5(implode('|', $sortedTags));
        // Utiliser _ comme séparateur au lieu de : pour respecter la validation des clés
        return self::TAGGED_KEY_PREFIX . $tagHash . '_' . $key;
    }

    /**
     * Enregistre une clé avec ses tags
     *
     * @param string $key Clé du cache
     */
    private function registerTaggedKey(string $key): void
    {
        if (empty($this->tags)) {
            return;
        }

        foreach ($this->tags as $tag) {
            $tagKey = self::TAG_PREFIX . $tag;
            $keys = $this->driver->get($tagKey, []);
            
            if (!is_array($keys)) {
                $keys = [];
            }

            if (!in_array($key, $keys, true)) {
                $keys[] = $key;
                $this->driver->set($tagKey, $keys);
            }
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
        $taggedKey = $this->getTaggedKey($key);
        return $this->driver->get($taggedKey, $default);
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
        $taggedKey = $this->getTaggedKey($key);
        $result = $this->driver->set($taggedKey, $value, $ttl);
        
        if ($result) {
            $this->registerTaggedKey($key);
        }

        return $result;
    }

    /**
     * Supprime une valeur du cache
     *
     * @param string $key Clé du cache
     * @return bool True si succès, false sinon
     */
    public function delete(string $key): bool
    {
        $taggedKey = $this->getTaggedKey($key);
        return $this->driver->delete($taggedKey);
    }

    /**
     * Vérifie si une clé existe dans le cache
     *
     * @param string $key Clé du cache
     * @return bool True si la clé existe
     */
    public function has(string $key): bool
    {
        $taggedKey = $this->getTaggedKey($key);
        return $this->driver->has($taggedKey);
    }

    /**
     * Vide tout le cache
     *
     * @return bool True si succès
     */
    public function clear(): bool
    {
        return $this->driver->clear();
    }

    /**
     * Supprime plusieurs clés en une fois
     *
     * @param array $keys Tableau de clés à supprimer
     * @return int Nombre de clés supprimées
     */
    public function deleteMultiple(array $keys): int
    {
        $taggedKeys = array_map([$this, 'getTaggedKey'], $keys);
        return $this->driver->deleteMultiple($taggedKeys);
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
     * Incrémente une valeur numérique
     *
     * @param string $key Clé du cache
     * @param int $value Valeur d'incrémentation (défaut: 1)
     * @return int|false Nouvelle valeur ou false en cas d'erreur
     */
    public function increment(string $key, int $value = 1): int|false
    {
        $taggedKey = $this->getTaggedKey($key);
        return $this->driver->increment($taggedKey, $value);
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
        $taggedKey = $this->getTaggedKey($key);
        return $this->driver->decrement($taggedKey, $value);
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
     * Invalide toutes les clés avec un tag donné
     *
     * @param string|array $tags Tag(s) à invalider
     * @return bool True si succès
     */
    public function invalidateTags(string|array $tags): bool
    {
        $tagsArray = is_array($tags) ? $tags : [$tags];
        $success = true;

        foreach ($tagsArray as $tag) {
            $tagKey = self::TAG_PREFIX . $tag;
            $keys = $this->driver->get($tagKey, []);

            if (is_array($keys) && !empty($keys)) {
                // Supprimer toutes les clés associées au tag
                foreach ($keys as $key) {
                    // Supprimer toutes les variantes taguées possibles
                    // On doit itérer sur toutes les combinaisons possibles de tags
                    $this->deleteAllTaggedVariants($key);
                }

                // Supprimer l'entrée du tag
                $this->driver->delete($tagKey);
            }
        }

        return $success;
    }

    /**
     * Supprime toutes les variantes taguées d'une clé
     *
     * @param string $key Clé originale
     */
    private function deleteAllTaggedVariants(string $key): void
    {
        // Cette méthode est simplifiée - dans une implémentation complète,
        // on devrait parcourir toutes les combinaisons de tags possibles
        // Pour l'instant, on supprime la clé directement
        $this->driver->delete($key);
    }

    /**
     * Récupère toutes les clés associées à un tag
     *
     * @param string $tag Tag à rechercher
     * @return array Tableau de clés
     */
    public function getKeysByTag(string $tag): array
    {
        $tagKey = self::TAG_PREFIX . $tag;
        $keys = $this->driver->get($tagKey, []);
        return is_array($keys) ? $keys : [];
    }
}


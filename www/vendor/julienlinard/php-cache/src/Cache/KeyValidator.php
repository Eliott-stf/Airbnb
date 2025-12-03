<?php

namespace JulienLinard\Cache;

use JulienLinard\Cache\Exceptions\InvalidKeyException;

/**
 * Validateur de clés de cache pour la sécurité
 */
class KeyValidator
{
    /**
     * Caractères autorisés dans les clés de cache
     */
    private const ALLOWED_CHARS = '/^[a-zA-Z0-9_\-\.]+$/';

    /**
     * Longueur maximale d'une clé
     */
    private const MAX_KEY_LENGTH = 250;

    /**
     * Longueur minimale d'une clé
     */
    private const MIN_KEY_LENGTH = 1;

    /**
     * Valide une clé de cache
     *
     * @param string $key Clé à valider
     * @throws InvalidKeyException Si la clé est invalide
     */
    public static function validate(string $key): void
    {
        // Vérifier que la clé n'est pas vide
        if (trim($key) === '') {
            throw new InvalidKeyException($key, 'La clé ne peut pas être vide');
        }

        // Vérifier la longueur minimale
        if (strlen($key) < self::MIN_KEY_LENGTH) {
            throw new InvalidKeyException($key, 'La clé est trop courte');
        }

        // Vérifier la longueur maximale
        if (strlen($key) > self::MAX_KEY_LENGTH) {
            throw new InvalidKeyException($key, 'La clé dépasse la longueur maximale de ' . self::MAX_KEY_LENGTH . ' caractères');
        }

        // Vérifier les caractères autorisés (protection contre les injections de chemins)
        if (!preg_match(self::ALLOWED_CHARS, $key)) {
            throw new InvalidKeyException(
                $key,
                'La clé contient des caractères non autorisés. Caractères autorisés: lettres, chiffres, _, -, .'
            );
        }

        // Protection contre les chemins relatifs (path traversal)
        if (strpos($key, '..') !== false || strpos($key, '/') !== false || strpos($key, '\\') !== false) {
            throw new InvalidKeyException($key, 'La clé ne peut pas contenir de chemins relatifs (.., /, \\)');
        }
    }

    /**
     * Nettoie et normalise une clé
     *
     * @param string $key Clé à nettoyer
     * @return string Clé nettoyée
     */
    public static function sanitize(string $key): string
    {
        // Supprimer les espaces en début/fin
        $key = trim($key);

        // Remplacer les caractères non autorisés par des underscores
        $key = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $key);

        // Limiter la longueur
        if (strlen($key) > self::MAX_KEY_LENGTH) {
            $key = substr($key, 0, self::MAX_KEY_LENGTH);
        }

        return $key;
    }
}


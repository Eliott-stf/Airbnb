<?php

namespace JulienLinard\Cache;

use JulienLinard\Cache\Exceptions\SerializationException;

/**
 * Sérialiseur de valeurs pour le cache
 * Utilise JSON pour la sérialisation avec validation de sécurité
 */
class ValueSerializer
{
    /**
     * Sérialise une valeur pour le stockage
     *
     * @param mixed $value Valeur à sérialiser
     * @return string Valeur sérialisée
     * @throws SerializationException Si la sérialisation échoue
     */
    public static function serialize(mixed $value): string
    {
        try {
            // Utiliser JSON pour la sérialisation (plus sûr que serialize())
            $serialized = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if ($serialized === false) {
                throw new SerializationException(
                    'Erreur lors de la sérialisation JSON: ' . json_last_error_msg()
                );
            }

            return $serialized;
        } catch (\Throwable $e) {
            throw new SerializationException('Erreur lors de la sérialisation', $e);
        }
    }

    /**
     * Désérialise une valeur depuis le stockage
     *
     * @param string $serialized Valeur sérialisée
     * @return mixed Valeur désérialisée
     * @throws SerializationException Si la désérialisation échoue
     */
    public static function unserialize(string $serialized): mixed
    {
        try {
            // Décoder JSON avec validation stricte
            $value = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);

            return $value;
        } catch (\JsonException $e) {
            throw new SerializationException('Erreur lors de la désérialisation JSON', $e);
        } catch (\Throwable $e) {
            throw new SerializationException('Erreur lors de la désérialisation', $e);
        }
    }

    /**
     * Vérifie si une valeur peut être sérialisée
     *
     * @param mixed $value Valeur à vérifier
     * @return bool True si la valeur peut être sérialisée
     */
    public static function canSerialize(mixed $value): bool
    {
        try {
            json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}


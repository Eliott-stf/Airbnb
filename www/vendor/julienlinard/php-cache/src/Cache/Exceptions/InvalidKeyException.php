<?php

namespace JulienLinard\Cache\Exceptions;

/**
 * Exception levée lorsqu'une clé de cache est invalide
 */
class InvalidKeyException extends CacheException
{
    public function __construct(string $key, string $reason = '')
    {
        $message = "Clé de cache invalide: '{$key}'";
        if ($reason !== '') {
            $message .= " - {$reason}";
        }
        parent::__construct($message);
    }
}


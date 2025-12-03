<?php

namespace JulienLinard\Cache\Exceptions;

/**
 * Exception levée lorsqu'une erreur survient lors de la sérialisation/désérialisation
 */
class SerializationException extends CacheException
{
    public function __construct(string $message = 'Erreur de sérialisation', ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}


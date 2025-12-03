<?php

namespace JulienLinard\Cache\Exceptions;

/**
 * Exception levée lorsqu'une erreur survient avec un driver de cache
 */
class DriverException extends CacheException
{
    public function __construct(string $driver, string $message = '', ?\Throwable $previous = null)
    {
        $fullMessage = "Erreur avec le driver '{$driver}'";
        if ($message !== '') {
            $fullMessage .= ": {$message}";
        }
        parent::__construct($fullMessage, 0, $previous);
    }
}


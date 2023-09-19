<?php

declare(strict_types = 1);

namespace libs\exception;

use throwable,Exception;

/**
 * Generate message by exception thrown for an error
 * 
 * @package libs/exception
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class RequireException extends Exception implements throwable{

    /**
     * Contruct new exception
     * @param  string          $message   Error message.
     * @param  int             $code      Error code.
     * @param  Throwable|null  $previous  The previous throwable used for the exception chaining.
     */
    public function __construct(string $message, int $code, ?throwable $previous = null)
    {
        parent::__construct($message, $code,$previous);
    }
}
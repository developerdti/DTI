<?php

declare(strict_types =1);

namespace libs\exception;

use Exception, Throwable;

/**
 * Generate message by exception made by dashboard attempt
 * 
 * @package libs/exception
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class DashboardException extends Exception{


    /**
     * Contruct new exception
     * @param  string          $message   Error message.
     * @param  int             $code      Error code.
     * @param  Throwable|null  $previous  The previous throwable used for the exception chaining.
     */
    public function __construct(string $message, int $code, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }
}

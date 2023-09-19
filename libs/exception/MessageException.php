<?php

declare(strict_types = 1);

namespace libs\exception;

use Exception;

/**
 * Defines message structure
 * @package libs\exception
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class MessageException{

    /**
     * @var     array   $data      message container
     * @access  private
     */
    private array $data;

    /**
     * Generate exception message structure
     * @access  public
     * @param   string  $location   destination error message
     * @param   string  $message    corresponding message
     * @param   int     $code       http code response
     * @param   string  $class      class exception origin
     * @param   string  $previous   previous exception
     * @return  array
     */
    public static function createMessage(string $location, string $message, int $code, string $class, string $previous = null): array
    {
        $error =[            
            'title' => $location,
            'message' => $message,
            'class' => $class,
            'code' => $code,
            'previous' => $previous];
        return $error;
    }

    /**
     * Generate info message structure
     * @access  public
     * @param   string          $affair     message affair
     * @param   string|array   $message    message 
     * @return  void
     */
    public function generateMessage(string $affair, string|array $message): void
    {
        $this->data[$affair] = $message;
    }

    /**
     * Generate info message structure
     * @access  public
     * @param   string          $affair     message affair
     * @param   string          $affair     message subaffair
     * @param   string|array    $message    message 
     * @return  void
     */
    public function generateStatusMessage(string $affair,string $subaffair, string|array $message): void
    {
        $this->data[$affair][$subaffair] = $message;
    }

    /**
     * geter
     * Return message
     * @access  public
     * @global  $data   message
     * @return  array
     */
    public function getdata(): array
    {
        return $this->data;
    }
}
<?php

declare(strict_types = 1);

namespace app\controllers;

use libs\exception\SupportException;


class Support{

    /**
     * Retrieve IP address.
     * @access  public
     * @return  string IP address.
     */
    public static function getIp(): string
    {
        return empty($_SERVER['HTTP_X_FORWARDED_FOR']) ?
            $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    /**
     * Checks if the string has special characters and numbers.
     * @access  public
     * @param   string  $string  String to validate.
     * @return  void
     * @throws  SupportException If the string doesn't have the requested format.
     */
    public static function filterCharacters(string $string): void
    {
        $regex = '/[\^<,\"@\/{}()*$%¿?=>:|;#+\-0-9]+/i';

        if (preg_match($regex, $string)) {
            throw new SupportException('Contiene caracteres no permitidos.', 422);
        }
    }


    /**
     * Format string for gramatical purpose
     * @access  public
     * @param   string  $string  String to validate.
     * @param   int     $type    Format type
     * @return  string  format string
     */
    public static function firstletterupper(string $string, int $type): string
    {
        if($type === 0){
            $string = strtolower($string);
            $string = ucfirst($string);
        }
        if($type === 1){
            $string = strtolower($string);
            $string = ucwords($string);
        }

        return $string;
    } 
}
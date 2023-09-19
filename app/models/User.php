<?php

declare(strict_types = 1);

namespace app\models;
use app\models\DataBase;
class User{

    public static function valideOldPassword(string $jobcode):array
    {
        $stm = "SELECT 
                    password
                FROM 
                    [cyberdti].[dbo].[user]
                WHERE
                    jobcode = :jobcode and isActive = 1";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "single"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function savePassword(string $password, string $jobcode): int
    {
        $stm = "UPDATE
                    [cyberdti].[dbo].[user]
                SET 
                    password = :password
                WHERE
                    jobcode = :jobcode";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":password"=> $password
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        return DataBase::stmGenerator($stm,$config);
    }
}
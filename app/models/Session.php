<?php

declare(strict_types = 1);

namespace app\models;
use app\models\DataBase;
class Session{

    public static function getJobcode(string $jobcode):array
    {
        $stm = "SELECT 
                    userI.isActive as status,
                    jobCode,
                    password,
                    profileId,
                    userI.isActive as isActive,
                    userp.firstName as firstName,
                    userp.secondName as secondName,
                    userp.lastName as lastName,
                    clientGroup,
                    pro.kind as kind
                FROM 
                    [cyberdti].[dbo].[user] AS userI
                LEFT JOIN 
                    [cyberdti].[dbo].[profile] AS pro ON (pro.id = userI.profileid)
                LEFT JOIN 
                    [cyberdti].[dbo].[userInfo] AS userp ON (userp.userid = userI.id)
                LEFT JOIN 
                    [cyberdti].[dbo].[client] AS client ON (userp.clientId = client.id)
                WHERE
                    jobcode = :jobcode";

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

    public static function saveLog(string $jobcode,int $loginStatus,string $logMessage,int $islogin, string $ip): int
    {
        $stm = "INSERT INTO Sessionlog (
                    jobCode,
                    loginAttempt,
                    loginDate,
                    descriptionAttempt,
                    islogin,
                    ip) 
                VALUES 
                    (:jobcode,:loginStatus,GETDATE(),:logMessage,:islogin,:ip)";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":loginStatus" => $loginStatus,
                ":logMessage" => $logMessage,
                ":islogin" => $islogin,
                ":ip" => $ip
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        return DataBase::stmGenerator($stm,$config);
        
    }

    public static function saveSignUpRequest(array $data): void
    {
        $stm = 
            "INSERT INTO [cyberdti].[dbo].[userRequest](
                jobCode,
                firstName,
                secondName,
                lastName,
                status,
                insertedDate,
                passWord
                )
            VALUES
                (:jobcode,:firstname,:secondname,:lastname,0,GETDATE(),:passWord)";

        $config = [
            "bindvalues"=> [
                ":jobcode" => $data['jobCode'],
                ":firstname" => $data['firstName'],
                ":secondname" => $data['secondName'],
                ":lastname" => $data['lastName'],
                ":passWord" => $data['signUpPassWord']
                ],
            "connection" => "server",
            "actions" => "modify",
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function valideRequestJobcode(string $jobcode):array
    {
        $stm = "SELECT 
                    jobCode, status
                FROM 
                    [cyberdti].[dbo].[userRequest]
                WHERE
                    jobcode = :jobcode";

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


    public static function sessionActive(string $jobcode):array
    {
        $stm = "SELECT 
                    isLogin
                FROM 
                    [cyberdti].[dbo].[Sessionlog]
                WHERE
                    jobcode = :jobcode and isLogin = 1";

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

    public static function sessionDestroy(string $jobcode): int
    {
        $stm = "UPDATE
                    [cyberdti].[dbo].[Sessionlog]
                SET 
                    isLogin = 0
                WHERE
                    jobcode = :jobcode and isLogin = 1";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        return DataBase::stmGenerator($stm,$config);
    }
}


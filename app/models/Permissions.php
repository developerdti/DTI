<?php
declare(strict_types = 1);

namespace app\models;

class Permissions{

    public static function searchRequests(): array
    {
        $stm =
        "SELECT 
            id,jobCode,firstName,secondName,lastName,concat(firstName,' ',secondName,' ',lastName) as name
        FROM 
            [dbo].[userRequest]
        WHERE 
            status = 0
        ";

        $config = [
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function searchProfile(): array
    {
        $stm =
        "SELECT 
            id,name,kind
        FROM 
            profile
        where isActive = 1
        ";

        $config = [
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function searchUsers(string $jobcode): array
    {
        $stm =
        "SELECT * FROM 
            [dbo].[user] 
        WHERE 
            jobcode <> :jobcode and isActive = 1
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function searchClient(): array
    {
        $stm =
        "SELECT 
            id,clientKey,name,clientGroup
        FROM 
            client 
        WHERE 
            isActive = 1;
        ";

        $config = [
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function searchManager(int $clientId): array
    {
        $stm = 
        "SELECT 
            userP.id,concat(firstName,' ',lastName) as name
        FROM 
            [dbo].[userInfo] as userI
        LEFT JOIN 
            [dbo].[user] as userP on (userI.userId = userP.id)
        LEFT JOIN 
            [dbo].[profile] as profile on (profile.id = userP.profileId)
        LEFT JOIN 
            [dbo].[client] as client on (client.id = userI.clientId)
        WHERE
            client.id = :clientId";

        $config = [
            "bindvalues"=> [
                ":clientId"=> $clientId
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function requestData(string $jobcode): array
    {
        $stm =
        "SELECT 
            jobcode,password,firstName,secondName,lastName
        FROM
            userRequest
        WHERE 
            jobCode = :jobcode and status = 0
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "single"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function enableUser(array $data, array $configData): void
    {
        $stm = 
        "INSERT INTO 
            [dbo].[user](jobCode,password,profileId,isActive,insertedDate)
        VALUES
            (:jobcode,:password,:profileId,1,GETDATE())";

        $config = [
            "bindvalues"=> [
                    ":jobcode" => $data['jobcode'],
                    ":password" => $data['password'],
                    ":profileId" => $configData['profile']
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function enableUserInfo(array $data, array $configData): void
    {
        $stm = 
        "INSERT INTO 
                [dbo].[userInfo](userId,clientId,firstName,secondName,lastName,isActive,insertedDate,managerId)
        VALUES
            ((SELECT Id FROM [dbo].[user] WHERE jobCode = :jobcode AND isActive = 1),
            :clientId,:firstName,:secondName,:lastName,1,GETDATE(),:managerId)
        ";

        $config = [
            "bindvalues"=> [
                    ":clientId" => $configData['Client'] ?? null,
                    ":jobcode" => $data['jobcode'],
                    ":firstName" => $data['firstName'],
                    ":secondName" => $data['secondName'],
                    ":lastName" => $data['lastName'],
                    ":managerId" => $configData['managerId'] ?? null,
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function requestStatus(string $jobcode): void
    {
        $stm = 
        "UPDATE 
            [dbo].[userRequest] 
        SET 
            status = 1
        WHERE
            jobCode = :jobcode AND status = 0
        ";

        $config = [
            "bindvalues"=> [
                    ":jobcode" => $jobcode
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

}
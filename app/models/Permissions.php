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
            userI.id,concat(firstName,' ',lastName) as name
        FROM 
            [dbo].[userInfo] as userI
        LEFT JOIN 
            [dbo].[user] as userP on (userI.userId = userP.id)
        LEFT JOIN 
            [dbo].[profile] as profile on (profile.id = userP.profileId)
        LEFT JOIN 
            [dbo].[client] as client on (client.id = userI.clientId)
        WHERE
            client.id = :clientId AND profile.id = 3";

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
                    ":managerId" => $configData['Manager'] ?? null,
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

    public static function refusedJobcode(string $jobcode): void
    {
        $stm = 
        "UPDATE 
            [dbo].[userRequest] 
        SET 
            status = 2
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

    public static function searchUsersRegistered(string $jobcode): array
    {
        $stm =
        "SELECT userI.id as id,userP.jobCode as jobcode, concat(lastName,' ',firstName,' ',secondName) as name FROM 
            [dbo].[userInfo] as userI
        INNER JOIN 
            [dbo].[user] as userP ON (userI.userId = userP.id)
        WHERE
            (LOWER(lastName) like :jobcode) OR (LOWER(firstName) like :jobcode2) 
            OR (LOWER(secondName) like :jobcode3) OR (LOWER(userP.jobCode) like :jobcode4)
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":jobcode2"=> $jobcode,
                ":jobcode3"=> $jobcode,
                ":jobcode4"=> $jobcode,
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function searchUserInfo(string $jobCode): array
    {
        $stm =
        "SELECT 
            CONCAT(lastName,' ',firstName,' ',secondName) as 'Nombre de usuario',
            userp.jobCode as Claves,pro.name as Perfil,client.name as Cliente,client.clientGroup as Grupo,
            userP.isActive as status
        FROM 
            [dbo].[userInfo] as userI
        LEFT JOIN 
            [dbo].[user] as userP ON (userI.userId = userP.id)
        LEFT JOIN 
            [dbo].[client] as client ON (userI.clientId = client.id)
        LEFT JOIN 
            [dbo].[profile] as pro ON (userP.profileId = pro.id)
        WHERE 
            userP.jobCode = :jobCode
        ";

        $config = [
            "bindvalues"=> [
                ":jobCode"=> $jobCode,
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "single"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function modifyUserInfo(array $data): void
    {
        $stm = 
        "UPDATE 
            userI
        SET 
            managerId = :manager, clientId = :clientId
        FROM 
            [dbo].[userInfo] as userI
        INNER JOIN 
            [dbo].[user] as userP ON (userI.userId = userP.id)
        WHERE
            userP.jobCode = :jobcode
        ";

        $config = [
            "bindvalues"=> [
                ":clientId" => $data['Client'] ?? null,
                ":manager" => $data['Manager'] ?? null,
                ":jobcode" => $data['jobcode']
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function modifyUser(array $data): void
    {
        $stm = 
        "UPDATE 
            [dbo].[user]
        SET 
            profileId = :profileId
        WHERE
            jobCode = :jobcode
        ";

        $config = [
            "bindvalues"=> [
                ":profileId" => $data['profile'] ?? null,
                ":jobcode" => $data['jobcode']
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function disableUser(string $jobcode): void
    {
        $stm = 
        "UPDATE 
            [dbo].[user] 
        SET 
            isActive = 0 
        WHERE 
            jobCode = :jobcode;
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

    public static function activeUser(string $jobcode): void
    {
        $stm = 
        "UPDATE 
            [dbo].[user] 
        SET 
            isActive = 1
        WHERE 
            jobCode = :jobcode;
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

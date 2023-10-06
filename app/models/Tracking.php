<?php
declare(strict_types = 1);

namespace app\models;

class Tracking{

    public static function addFolio(string $jobcode,string $folio, string $petition, string $description): int
    {
        $stm =
        "INSERT INTO 
            [dbo].[sendFolio] (jobcodePetition,folio,petition,status,insertedDate,description) 
        VALUES 
            (:jobcode,:folio,:petition,0,GETDATE(),:description)
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":folio"=> $folio,
                ":petition"=> $petition,
                ":description"=> $description
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function getTrackingFolio(string $jobcode): array
    {
        $stm =
        "SELECT 
            folio,petition,description,comment,status,insertedDate 
        FROM 
            sendFolio
        WHERE
            jobcodePetition = :jobcode
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function getManagerJobcode(string $jobcode): array
    {
        $stm =
        "SELECT 
            jobcode
        FROM 
            [dbo].[userInfo] userI
        left join
            [dbo].[user] userP on (userP.id = userI.userId)
        WHERE userI.id = 
            (SELECT 
                managerId
            FROM 
                [dbo].[userInfo] userI
            left join
                [dbo].[user] userP on (userP.id = userI.userId)
            WHERE jobCode = :jobcode)
        ";

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

    public static function getmanualDialing(string $jobcode, int $group, string $jobcodeSuper): array
    {
        $stm =
        "SELECT folio, clientKey, petition, markingDate FROM 
            manualDialingList list
        RIGHT JOIN 
            manualDialingListInfo listInfo ON (list.id = listInfo.infoId)
        WHERE list.jobcode = :jobcodeSuper AND clientGroup = :group AND listInfo.jobcode = :jobcode
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":group"=> $group,
                ":jobcodeSuper"=> $jobcodeSuper,
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];


        return DataBase::stmGenerator($stm,$config);
    }
}
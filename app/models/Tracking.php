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
    public static function getmanualDialing(string $jobcode): array
    {
        $stm =
        "SELECT 
            manualD.id, manualD.jobcode,manualI.folio,manualD.clientKey,manualD.insertedDate 
        FROM 
            manualDialingList 
        AS 
            manualD
        LEFT JOIN 
            manualDialingListInfo 
        AS 
            manualI ON(manualD.id = manualI.infoId)
        WHERE 
            manualD.jobcode = :jobcode
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

    
}
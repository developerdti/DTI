<?php
declare(strict_types = 1);

namespace app\models;

class Productivity{

    public static function addmarkingFile(array $querys, int $id): void
    {
        foreach($querys as $key){
            $stm[] =
            "INSERT INTO 
                manualDialingListInfo (jobcode,folio,clientKey,infoId,petition,markingDate) 
            VALUES 
                ('".$key[0]."','".$key[1]."','".$key[2]."','".$id."','".$key[3]."','".$key[4]."')
            ";
        }

        $config = [
            "connection" => "server"
        ];

        DataBase::uploadMultipleTransactions($stm,$config);
    }

    public static function addSupervisorReference(string $jobcode, string $group): void
    {
        $stm =
        "INSERT INTO 
            manualDialingList (jobcode,clientGroup,isActive,insertedDate) 
        VALUES 
            (:jobcode,:group,'1',GETDATE())
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":group"=> $group
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function getSupervisorReference(string $jobcode, string $group): array
    {
        $stm =
        "SELECT 
            id 
        FROM 
            manualDialingList 
        WHERE 
            jobcode = :jobcode AND clientGroup = :group AND isActive = 1
        ";

        $config = [
            "bindvalues"=> [
                ":jobcode"=> $jobcode,
                ":group"=> $group,
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "single"
        ];
        return DataBase::stmGenerator($stm,$config);
    }

    public static function getmanualDialing(string $jobcode): array
    {
        $stm =
        "SELECT 
            manualI.jobcode,folio,clientKey,markingDate,petition 
        FROM 
            manualDialingList 
        AS 
            manualD
        LEFT JOIN 
            manualDialingListInfo 
        AS 
            manualI ON(manualD.id = manualI.infoId)
        WHERE 
            manualI.jobcode = :jobcode
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

    static public function deactivateSupervisroReference(int $id): void
    {
        $stm =
        "UPDATE 
            manualDialingList 
        SET 
            isActive = 0
        WHERE 
            id = :id
        ";

        $config = [
            "bindvalues"=> [
                ":id"=> $id
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    static public function deleteManualDialingFile(int $id): void
    {
        $stm =
        "DELETE 
            listI 
        FROM 
            manualDialingListInfo listI
        left join 
            manualDialingList list ON (list.id = listI.infoId)
        WHERE 
            list.id = :id
        ";

        $config = [
            "bindvalues"=> [
                ":id"=> $id
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }
}
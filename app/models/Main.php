<?php
declare(strict_types = 1);

namespace app\models;

class Main{

    public static function sectionInfo(string|null $group): array
    {
        $stm =
        "SELECT
            id,name,location 
        FROM 
            dashboard
        where
            clientGroup = :group
        ";

        $config = [
            "bindvalues"=> [
                ":group"=> $group ?? ''
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function imageInfo(int $id): array
    {
        $stm =
        "SELECT 
            imageI.name as name,description 
        FROM
            dashboard AS dash
        LEFT JOIN
            imageInfo AS imageI
        ON
            (imageI.infoId = dash.id)
        WHERE 
            dash.id = :id
        ";

        $config = [
            "bindvalues"=> [
                ":id"=> $id
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }
}
<?php
declare(strict_types = 1);

namespace app\models;

class Dashboard{

    public static function getSections(string|null $group): array
    {
        $stm =
        "SELECT
            id,name
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

    public static function sectionInfo(int $id): array
    {
        $stm =
        "SELECT
            name,location 
        FROM 
            dashboard
        where
            id = :id
        ";

        $config = [
            "bindvalues"=> [
                ":id"=> $id ?? ''
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "single"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function addImage(int $id,string $name,string|null $description): void
    {
        $stm =
        "INSERT INTO 
            imageInfo (infoId,name,description,insertedDate)
        VALUES
            (:id,:name,:description,GETDATE())
        ";

        $config = [
            "bindvalues"=> [
                ":id"=> $id,
                ":name" => $name,
                ":description" => empty($description) ? null : $description 
                ],
            "connection" => "server",
            "actions" => "modify",
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function imageInfo(int $id): array
    {
        $stm =
        "SELECT 
            imageI.id,imageI.name as name,description 
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

    public static function deleteImage(int $id): int
    {
        $stm =
        "DELETE 
            imageInfo 
        WHERE id = :id
        ";

        $config = [
            "bindvalues"=> [
                ":id"=> $id
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        return DataBase::stmGenerator($stm,$config);
    }
}
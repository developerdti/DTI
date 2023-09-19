<?php
declare(strict_types = 1);

namespace app\models;

class Sidebar{

    public static function SidebarCatalogueModules(int $kind,string $sectionName,?string $group = ''):array
    {
        $stm =
        "SELECT 
            sidebar.icon,sidebar.moduleName,sidebar.descriptionName
        FROM 
            usersidebar as sidebarU
        LEFT JOIN
            sidebar ON (sidebarU.sidebarId = sidebar.id)
        LEFT JOIN 
            sidebarcatalogue as sidebarC ON (sidebarU.sidebarCatalogueId = sidebarC.id)
        WHERE
            (kind = :kind OR (kind = :kindg AND clientGroup = :group)) AND isActive = 1 AND sidebarC.sectionName = :sectionName";

        $config = [
            "bindvalues"=> [
                ":group"=> $group,
                ":sectionName"=> $sectionName,
                ":kind"=> $kind,
                ":kindg"=> $kind
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function SidebarCatalogue(int $kind,?string $group = ''):array
    {
        $stm =
        "SELECT 
            DISTINCT icon,sectionName,descriptionName
        FROM 
            usersidebar as sidebarU
        LEFT JOIN
            sidebarcatalogue as sidebarC ON (sidebarU.sidebarCatalogueId = sidebarC.id)
        WHERE
            (kind = :kind OR (kind = :kindg AND clientGroup = :group)) AND isActive = 1";

        $config = [
            "bindvalues"=> [
                ":kind"=> $kind,
                ":kindg"=> $kind,
                ":group"=> $group
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }

    public static function Sidebar(int $kind,?string $group = ''): array
    {
        $stm =
        "SELECT 
            icon,moduleName,descriptionName
        FROM 
            usersidebar as sidebarU
        LEFT JOIN
            sidebar ON (sidebarU.sidebarId = sidebar.id)
        WHERE
            (kind = :kind OR (kind = :kindg AND clientGroup = :group)) AND isActive = 1";

        $config = [
            "bindvalues"=> [
                ":kind"=> $kind,
                ":kindg"=> $kind,
                ":group"=> $group
                ],
            "connection" => "server",
            "actions" => "search",
            "fetch" => "all"
        ];

        return DataBase::stmGenerator($stm,$config);
    }
}
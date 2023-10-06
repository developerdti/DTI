<?php

declare(strict_types = 1);

namespace app\models;

use libs\exception\DataBaseException;
use PDO, Throwable;
use PDOException;

class DataBase{
    private static array $sqlserver = ["dsn"=>"sqlsrv:Server = MEXDQAH055-TI\DESARROLLO;database=cyberdti",
                                        "user"=>"sa",
                                        "password" =>"Desa"];

    private mixed $db;

    private mixed $stm;

    public function __construct($connection){
        try {
            match ($connection) {
                 'server'=>$this->db = new PDO(self::$sqlserver['dsn'],self::$sqlserver['user'],self::$sqlserver['password']),
                 'oracle'=>$this->db = new PDO(self::$sqlserver['dsn'],'sa','<Desarrollo_2023>')
            };

            $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new DataBaseException("No se pudo conectar a la base de datos: ", 500,$e);
        }
    }

    private function prepare($stm): void
    {
        $this->stm = $this->db->prepare($stm);
    }

    private function beginTransaction(array $stm): void
    {
        try {
            $this->db->beginTransaction();
            foreach($stm as $key => $value){
                $this->db->query($value);
            }
            $this->db->commit();
        } catch (PDOException $e){
            $this->db->rollback();
            throw new DataBaseException("Error al subir informacion: ", 500,$e);
        }
    }

    private function bindPrepare($stm,$param){
        $this->prepare($stm);

        if(isset($param["bindvalues"])){
            foreach($param["bindvalues"] as $key => $value){
                match (true) {
                    is_int($value)=> PDO::PARAM_INT,
                    is_bool($value)=> PDO::PARAM_BOOL,
                    is_string($value)=> PDO::PARAM_STR,
                    is_null($value)=> PDO::PARAM_NULL,
                    default => throw new DataBaseException("No se encontro un parametro valido", 500)
                };
    
                $this->stm->bindvalue($key,$value);
            }
        }
    }

    private function fetchAll():array
    {
        $this->stm->execute();
        return $this->stm->fetchALL(PDO::FETCH_ASSOC);
    }

    private function fetchSingle():array
    {
        $result = [];
        $this->stm->execute();

        $result = $this->stm->fetch(PDO::FETCH_ASSOC) ? : [];
        
        return $result;
    }

    private function fetchCount():int
    {

        $this->stm->execute();

        $this->stm->fetch(PDO::FETCH_ASSOC);
        
        return $this->db->rowcount();
    }

    public function search(array $param): mixed
    {
        try {
            return match ($param['fetch']) {
                 'single'=> $this->fetchSingle(),
                 'all'=> $this->fetchAll(),
                 'count' => $this->fetchCount(),
            };
        } catch (PDOException $e) {
            throw new DataBaseException("Error al ejecutar sentencia de busqueda", 500,$e);
        }
    }

    public function modify(): int
    {
        try {
            $this->stm->execute();

            return $this->stm->rowCount();
        } catch (PDOException $e) {
            throw new DataBaseException("Error al ejecutar sentencia", 500,$e);
        }
    }

    public static function stmGenerator($stm,$param):mixed
    {
        $db = new DataBase($param["connection"]);

        $db->bindPrepare($stm,$param);

        return match ($param["actions"]) {
            'search'=> $db->search($param),
            'modify'=> $db->modify($param)
        };

    }

    public static function uploadMultipleTransactions($stm,$param):mixed
    {
        $db = new DataBase($param["connection"]);

        return $db->beginTransaction($stm);
    }

    public function __destruct()
    {
        $this->db = null;
        $this->stm = null;
    }
}


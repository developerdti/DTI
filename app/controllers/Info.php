<?php
// phpinfo();

DB();
    function DB(){
        $db_username = 'RCVRY'; 
        $db_password = 'CR3c0V3ryS3rt3C2018'; 
        $db_hostname = '192.168.90.29';
        $db_port = 1521; 
        $db_service_name = 'sertecp';
        $db_name = "
        (DESCRIPTION =
            (ADDRESS_LIST =
            (ADDRESS = (PROTOCOL = TCP)(HOST = $db_hostname)(PORT = $db_port))
            )
            (CONNECT_DATA =
            (SERVICE_NAME = $db_service_name)
            )
        )";

    try {
        $conexion= new PDO('oci:dbname='.$db_name,$db_username, $db_password);
        echo 'Si conecto';
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error de conexion:'.$e->getMessage());
        }
        return $conexion;
}

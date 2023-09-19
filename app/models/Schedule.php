<?php
declare(strict_types = 1);

namespace app\models;

class Schedule{

    public static function ppCurrentMonth(string $jobcode):array
    {
        $stm =
        "SELECT 
            COUNT(*) AS ppCount,SUM(monto) AS amount, SUM(pago_banco) AS bankPayment, SUM(capital) AS capital 
        FROM 
            seguimiento 
        WHERE 
            claves=:jobcode AND fecha_pago 
        BETWEEN 
            DATEADD(month, DATEDIFF(month, 0, DATEADD(month, 0,GETDATE())), 0) 
        AND 
            dateadd(ms,-3,DATEADD(mm, DATEDIFF(m,0,getdate() )+1, 0))";


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

    public static function ppMonthBefore(string $jobcode):array
    {
        $stm =
        "SELECT 
            COUNT(*) AS ppCount,SUM(monto) AS amount, SUM(pago_banco) AS bankPayment, SUM(capital) AS capital 
        FROM 
            seguimiento 
        WHERE 
            claves = :jobcode AND fecha_pago 
        BETWEEN 
            DATEADD(month, DATEDIFF(month, 0, DATEADD(month, -1,GETDATE())), 0) 
        AND 
            dateadd(ms,-3,DATEADD(mm, DATEDIFF(m,-1,getdate() )-1, 0));";


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

    public static function ppCurrentDay(string $jobcode):array
    {
        $stm =
        "SELECT 
            COUNT(*) AS conteo2,SUM(monto) AS amount, SUM(pago_banco) AS bankpayment,SUM(capital) AS capital 
        FROM 
            seguimiento 
        WHERE 
            CONVERT(nvarchar(30), FECHA_ACCION, 111)=CONVERT(nvarchar(30), GETDATE(), 111) and claves = :jobcode";


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

    public static function saveNote(string $folio,string $comment,string $jobcode): void
    {
        $stm = "INSERT INTO	notes
                (folio,jobcode,comment,insertedDate)
            VALUES
                (:folio,:jobcode,:comment,GETDATE())";

        $config = [
            "bindvalues"=> [
                ":folio"=> $folio,
                ":jobcode"=> $jobcode,
                ":comment" => $comment,
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function getNotes($jobcode): array
    {
        $stm =
        "SELECT * FROM 
            notes
        WHERE 
            jobcode = :jobcode
        ORDER BY
            insertedDate DESC";

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

    public static function getfollowUp($jobcode): array
    {
        $stm =
        "SELECT 
            folio,CONVERT(varchar,fecha_accion,23) as actionDate,monto as amount,CONVERT(varchar,fecha_pago,23) 
            as paymentDay,RMT as promise,pago_banco as bankPayment,CONVERT(varchar,fecha_pago_banco,23) 
            as bankPaymentDay,capital,status,tipo_neg as businessType
        FROM 
            seguimiento
        WHERE
            claves = :jobcode
        AND
            fecha_pago > DATEADD(month, DATEDIFF(month, 0, DATEADD(month, 0,GETDATE())), 0) 
        ORDER BY
            fecha_pago";

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

    public static function saveFollowUp(array $row): void
    {
        $stm = "UPDATE seguimiento
        SET 
            monto = :amount, RMT = :promise, pago_banco = :bankPayment, fecha_pago_banco = :bankPaymentDate, tipo_neg = :businessType
        WHERE 
            folio = :folio";

        $config = [
            "bindvalues"=> [
                ":amount"=> $row[0],
                ":promise"=> $row[1],
                ":bankPayment" => $row[2],
                ":bankPaymentDate" => $row[3],
                ":businessType" => $row[4],
                ":folio" => $row[5],
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }

    public static function delete_note(int $id)
    {
        $stm = 
        "DELETE 
            notes 
        WHERE
            id = :id";

        $config = [
            "bindvalues"=> [
                ":id"=> $id,
                ],
            "connection" => "server",
            "actions" => "modify"
        ];

        DataBase::stmGenerator($stm,$config);
    }
}


<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;

class Conta extends Record
{
    const TABLENAME = 'conta';
    const TABLE_PK  = 'idConta';

    public static function totalContas($id)
    {
        $classe = get_called_class();
    
        $sql = 'SELECT COUNT(*) as total FROM '.constant("{$classe}::TABLENAME")." WHERE id_usuario  = {$id}";

        if($conn = Transaction::get())
        {
            $result = $conn->query($sql);

            $r = $result->fetch(\PDO::FETCH_OBJ);

            return $r->total;
        }
        else{
            throw new \Exception('Não há transação ativa!');
        }
    }

    public static function loadAll($id)
    {
        $classe = get_called_class();
    
        $sql = 'SELECT * FROM '.constant("{$classe}::TABLENAME")." WHERE  id_usuario = {$id}";

        if($conn = Transaction::get())
        {
            $result = $conn->query($sql);

            return $result->fetchAll(\PDO::FETCH_CLASS,$classe);
        }
        else{
            throw new \Exception('Não há transação ativa!');
        }
    }

}
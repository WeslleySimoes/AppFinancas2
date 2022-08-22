<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;

class Categoria extends Record
{
    const TABLENAME = 'categoria';
    const TABLE_PK  = 'idCategoria';

    // public static function findBy($culunaValor)
    // {
    //     $sql = "SELECT * FROM ".self::TABLENAME." WHERE {$culunaValor}";

    //     if($conn = Transaction::get())
    //     {
    //         if($result = $conn->query($sql))
    //         {
    //             //retorna os dados em forma de objeto
    //             $object = $result->fetchAll(\PDO::FETCH_CLASS,get_called_class());
    //         }

    //         return $object ?? NULL;
    //     }
        
    //     throw new \Exception('Não há transação aberta!');
    // }
}
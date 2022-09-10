<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;

class Categoria extends Record
{
    const TABLENAME = 'categoria';
    const TABLE_PK  = 'idCategoria';

    public function TotalGastoMesAtual()
    {

        try {
            Transaction::open('db');
            
            $sql = "SELECT SUM(valor) as total FROM transacao WHERE id_categoria = {$this->data[self::TABLE_PK]} AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE())";

            $conn = Transaction::get();

            $result = $conn->query($sql);

            $r =  (float) $result->fetch()['total'];    

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        $this->data['totalGastoMesAtual'] = $r;
        return $r;

    }

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
<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;

class Transferencia extends Record
{
    const TABLENAME = 'transferencia';
    const TABLE_PK  = 'idTransferencia';


    public function getNomeConta($conta)
    {
        try {   
            
            $tipoConta = '';
            
            switch($conta){
                case 'origem':
                    $tipoConta = 'origem';
                    break;
                case 'destino':
                    $tipoConta = 'destino';  
                    break;
                default:
                    return false;
            }
            
            Transaction::open('db');
                        
            $contaOrigem = Conta::find($this->data["id_conta_{$tipoConta}"]);

            Transaction::close();
            
            return $contaOrigem->descricao;
        
        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }
}
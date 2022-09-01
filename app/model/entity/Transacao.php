<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\model\entity\Conta;
use app\model\entity\Categoria;
use app\model\entity\Transferencia;

class Transacao extends Record
{
    const TABLENAME = 'transacao';
    const TABLE_PK  = 'idTransacao';
    

    public function store()
    {
        if(isset($this->data['transferencia']))
        {
            $resultado = $this->data['transferencia']->store();

            if($resultado)
            {
                if(isset($this->data['transferencia']->idTransferencia))
                {
                    $this->data['id_transferencia'] = $this->data['transferencia']->idTransferencia;

                    return parent::store() or $resultado;
                }
                else{
                    $this->data['id_transferencia'] = $this->data['transferencia']->getLast();
                    return parent::store();
                }
            }else
            {
                return false;
            }
            
        }
        else{    
            return parent::store();
        }
    }

    //obter nome de categoria
    public function getNomeCategoria()
    {
        try {   
            Transaction::open('db');

            $c = Categoria::find($this->data['id_categoria']);
            return $c->nome;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }

    //obter nome de categoria
    public function getDescricaoConta()
    {
        try {   
            Transaction::open('db');

            $c = Conta::find($this->data['id_conta']);
            return $c->descricao;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }


    public function getTransferencia()
    {
        try {   
            Transaction::open('db');

            $c = Transferencia::find($this->data['id_transferencia']);
            return $c;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }

    public static function total(int $id_usuario)
    {
        $classe = get_called_class();

        $sql = "SELECT COUNT(*) AS total FROM ".constant("{$classe}::TABLENAME")." WHERE id_usuario =  {$id_usuario}";

        if($conn = Transaction::get())
        {
            $result = $conn->query($sql);

            return $result->fetch();
        }
        else{
            throw new \Exception('Não há transação ativa!');
        }
        
    }


    // public static findByPg($inicio,$qtd,$where = '')
    // {
    //     "SELECT COUNT(*) AS total FROM ".constant("{$classe}::TABLENAME")." WHERE id_usuario =  {$id_usuario}";
    // }
}


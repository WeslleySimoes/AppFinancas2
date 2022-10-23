<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\session\Usuario as UsuarioSession;

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

    public function TotalGastoPeriodo($perido)
    {
        try {
            Transaction::open('db');
            
            $sql = "SELECT SUM(valor) as total FROM transacao WHERE id_categoria = {$this->data[self::TABLE_PK]} AND DATE(data_trans) BETWEEN {$perido} ";

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

    public static function addCategoriasIniciais()
    {
        $categoriasLista = [
            
            // DESPESAS

            ["nome" => 'Mercado',"cor_cate" => "#6b2a39", "tipo" => 'despesa',"status_cate" => 'ativo'],

            ["nome" => 'Lazer',"cor_cate" => "#68a530", "tipo" => 'despesa',"status_cate" => 'ativo'],

            ["nome" => 'Saúde',"cor_cate" => "#47e5bb", "tipo" => 'despesa',"status_cate" => 'ativo'],

            // RECEITAS

            ["nome" => 'Salário',"cor_cate" => "#2b34ad", "tipo" => 'receita',"status_cate" => 'ativo'],

            ["nome" => 'Renda extra',"cor_cate" => "#f73b3b", "tipo" => 'receita',"status_cate" => 'ativo'],

            ["nome" => 'Dividendos',"cor_cate" => "#5a6045", "tipo" => 'receita',"status_cate" => 'ativo']
        ];


        foreach($categoriasLista as $c)
        {
            try {
                Transaction::open('db');

                $categoria1 = new Categoria();
                $categoria1->nome = $c['nome'];
                $categoria1->cor_cate = $c['cor_cate'];
                $categoria1->tipo = $c['tipo'];
                $categoria1->id_usuario = UsuarioSession::get('id') ;
                $categoria1->status_cate = $c['status_cate'];

                $categoria1->store();

                Transaction::close();
            } catch (\Exception $e) {
                Transaction::rollback();
            }

        }
    }

    public static function totalCategorias($id)
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
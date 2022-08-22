<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\helpers\FormataMoeda;
use app\model\entity\Categoria;

class ReceitaFixa extends Record
{
    const TABLENAME = 'receita_fixa';
    const TABLE_PK  = 'idRec';

    //Método responsável por realizar a parcela da receita
    public function parcelar(int $quantidade)
    {
        $dataHoje = new \DateTime($this->data_inicio,new \DateTimeZone('America/Sao_Paulo'));
        $dataHoje->add(new \DateInterval("P{$quantidade}M"));

        $this->valor  = FormataMoeda::moedaParaFloat($this->valor) / $quantidade;
        $this->data_fim = $dataHoje->format('Y/m/d'); 
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
}
<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\helpers\FormataMoeda;
use app\model\entity\Categoria;
use app\model\entity\Transacao as TransacaoModel;
use app\session\Usuario as UsuarioSession;

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

    //REMOVER RECEITAS FIXAS/FUTURAS FUTURAS
    public function removeFuturas()
    {

        //OBTENDO A TRANSAÇÃO RELACIONA A FIXA/PARCELADA ATUAL
        $t = TransacaoModel::findBy("id_usuario = ".UsuarioSession::get('id')." and id_receitaFixa = {$this->data['idRec']}");

        //ALTERANDO ID_RECEITAFIXAS PARA NULL, para não haver conflito
        foreach($t as $transacao)
        {
          $t2 = clone $transacao;
          $t2->removeProp('idTransacao');
          $t2->removeProp('id_receitaFixa');

          $transacao->delete();
          $t2->store();
        }

        return parent::delete();
    }

    //REMOVER TODAS RECEITAS FIXAS/FUTURAS
    public function removeTodas()
    {
        //OBTENDO A TRANSAÇÃO RELACIONA A FIXA/PARCELADA ATUAL
        $t = TransacaoModel::findBy("id_usuario = ".UsuarioSession::get('id')." and id_receitaFixa = {$this->data['idRec']}");

        //ALTERANDO ID_RECEITAFIXAS PARA NULL, para não haver conflito
        foreach($t as $transacao)
        {
            $transacao->delete();
        }

        return parent::delete();
    }

}
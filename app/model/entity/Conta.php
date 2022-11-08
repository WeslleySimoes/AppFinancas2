<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\session\Usuario as UsuarioSession;

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

    public function getSaldoAtual($anoMes = null)
    {
        if($anoMes == null)
        {

            $anoMes = getDataFinalMesAtual();
        }

        try {
            Transaction::open('db');

            $sql1 = "SELECT (IFNULL(montante,0) + (SELECT IFNULL(SUM(valor),0) FROM transacao WHERE id_usuario = ".UsuarioSession::get('id')." AND tipo = 'receita' AND status_trans = 'fechado' AND id_conta = {$this->data['idConta']} AND DATE(data_trans) <= DATE('{$anoMes}')) - (SELECT IFNULL(SUM(valor),0) FROM transacao WHERE id_usuario = ".UsuarioSession::get('id')." AND tipo = 'despesa' AND status_trans = 'fechado' AND id_conta = {$this->data['idConta']} AND DATE(data_trans) <= DATE('{$anoMes}'))) total FROM conta WHERE id_usuario = ".UsuarioSession::get('id')." AND idConta = {$this->data['idConta']}";
            //-
            $sql2 = "SELECT IFNULL(SUM(valor),0) AS total FROM transferencia WHERE id_usuario = ".UsuarioSession::get('id')." AND id_conta_origem = {$this->data['idConta']} AND status_transf = 'fechado' AND DATE(data_transf) <= DATE('{$anoMes}')";
            //+
            $sql3 = "SELECT IFNULL(SUM(valor),0) AS total FROM transferencia WHERE id_usuario = ".UsuarioSession::get('id')." AND id_conta_destino = {$this->data['idConta']} AND status_transf = 'fechado' AND DATE(data_transf) <= DATE('{$anoMes}')";


            $conn = Transaction::get();

            $resultado1 = $conn->query($sql1);
            $resultado1 = $resultado1->fetch(\PDO::FETCH_ASSOC);

            $resultado2 = $conn->query($sql2);
            $resultado2 = $resultado2->fetch(\PDO::FETCH_ASSOC);

            $resultado3 = $conn->query($sql3);
            $resultado3 = $resultado3->fetch(\PDO::FETCH_ASSOC);

            return floatval($resultado1['total']) - floatval($resultado2['total']) + floatval($resultado3['total']);


            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }
    
    }

}
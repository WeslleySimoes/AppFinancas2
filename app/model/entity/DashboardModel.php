<?php 

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Conta as ContaModel;

class DashboardModel extends Record
{
    public static function getTotalRD($tipoTransacao = 'receita')
    {
        $sql = "SELECT IFNULL(SUM(valor),0) AS total FROM transacao WHERE id_usuario = ".UsuarioSession::get("id")." AND tipo = '{$tipoTransacao}' AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE()) ";

        
        if($conn = Transaction::get())
        {
            if($resultado = $conn->query($sql))
            {
                return $resultado->fetch(\PDO::FETCH_OBJ);
            }

            return NULL;
        }
        
        throw new \Exception('Não há transação aberta!');
    }

    public static function saldoTotalContas()
    {   
        $contas = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id'));
        $saldoTotal = 0.00;

        foreach ($contas as $conta) {
            $saldoTotal += floatval($conta->getSaldoAtual());
        }   

        return $saldoTotal;
    }

    public static function getLastTransacoes($qtd = 10)
    {
        $sql = " SELECT * FROM transacao WHERE id_usuario = ".UsuarioSession::get('id')." AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE()) ORDER BY DATE(data_trans) DESC LIMIT {$qtd};";
    
        if($conn = Transaction::get())
        {
            if($resultado = $conn->query($sql))
            {
                return $resultado->fetchAll(\PDO::FETCH_CLASS,'app\model\entity\Transacao');
            }

            return NULL;
        }
        
        throw new \Exception('Não há transação aberta!');
    }

    public static function drPorCategoria($tipo = 'despesa')
    {
        $sql = "SELECT t.id_categoria,c.nome,c.cor_cate,SUM(t.valor) as total FROM transacao as t INNER JOIN categoria as c ON t.id_categoria = c.idCategoria WHERE t.tipo = '{$tipo}' AND t.id_usuario = ".UsuarioSession::get("id")." AND MONTH(t.data_trans) = MONTH(CURDATE()) AND YEAR(t.data_trans) = YEAR(CURDATE()) GROUP BY t.id_categoria";

        if($conn = Transaction::get())
        {
            if($resultado = $conn->query($sql))
            {
                return $resultado->fetchAll(\PDO::FETCH_ASSOC);
            }

            return NULL;
        }
        
        throw new \Exception('Não há transação aberta!');

    }
}



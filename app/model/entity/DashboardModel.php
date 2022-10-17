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

    public static function despRespMensal($tipo = 'despesa')
    {
        $sql = "SELECT data_trans,IFNULL(SUM(valor),0) as total FROM transacao WHERE id_usuario =".UsuarioSession::get('id')." AND tipo = '{$tipo}' AND YEAR(data_trans) = YEAR(CURDATE()) GROUP BY DATE_FORMAT(data_trans, '%Y%m')";

        if($conn = Transaction::get())
        {
            if($resultado = $conn->query($sql))
            {
                $resultado = $resultado->fetchAll(\PDO::FETCH_ASSOC);

                $arrCombine = array_combine(array_column($resultado,'data_trans'),array_column($resultado,'total'));

                $meses = self::getDatasDoMes(date('Y').'-01-01',date('Y').'-12-01','P1M');

                // ARRAY FINAL DE DESPESAS
                $arrFinalDespesas = [];

                foreach ($meses as $mes => $valor) {
                    $bandeira = true;
                    foreach ($arrCombine as $data => $value) {
                        $data1 = explode('-',$data);
                        $mes1 = explode('-',$mes);

                        if($data1[0] == $mes1[0] AND $data1[1] == $mes1[1])
                        {
                            unset($meses[$mes]);
                            $meses[$data] = $value;
                            $bandeira = false;

                            $monthName = strftime("%B", strtotime($data));

                            $arrFinalDespesas[$monthName] = $value;

                            break;
                        }
                    }      
                    
                    if($bandeira)
                    {
                        
                        $monthName = utf8_encode(strftime("%B", strtotime($mes)));

                        $arrFinalDespesas[$monthName] = $valor;
                    }
                }

                return $arrFinalDespesas;

            }

            return NULL;
        }
        
        throw new \Exception('Não há transação aberta!');

    }

    private static function getDatasDoMes($inicioData = null,$finalDAta = null,$periodoData = 'P1D')
    {
        if(isset($inicioData) and isset($finalDAta))
        {
            $inicio = $inicioData;
            $final  = $finalDAta;
            
            $finalCerto = new \DateTime($final);
            $finalCerto->modify('+1 day');
        }
        else{
            $inicio = date('Y-m').'-01';
            $final  = getDataFinalMesAtual();

            $finalCerto = new \DateTime($final);
            $finalCerto->modify('+1 day');
        }

    
        $periodo = new \DatePeriod(
            new \DateTime($inicio),
            new \DateInterval($periodoData),
            new \DateTime($finalCerto->format('Y-m-d')),
        );
        
        $arrDate = [];
        
        foreach ($periodo as $key => $value) {
            $arrDate[] = $value->format('Y-m-d');
        }

        $arrFlip = array_flip($arrDate);
        $arrFlip = array_map(function(){return '0.00';},$arrFlip);

        return $arrFlip;
    }
}



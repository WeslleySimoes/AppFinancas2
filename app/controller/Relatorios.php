<?php 

namespace app\controller;

use app\helpers\Validacao;
use app\model\Transaction;
use app\helpers\FlashMessage;
use app\model\entity\Categoria;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Conta as ContaModel;
use app\model\entity\Transacao;
use app\model\entity\Usuario;

class Relatorios extends BaseController
{
    private function getDatasDoMes()
    {
        $inicio = date('Y-m').'-01';
        $final  = getDataFinalMesAtual();
        $finalCerto = new \DateTime($final);
        $finalCerto->modify('+1 day');

    
        $periodo = new \DatePeriod(
            new \DateTime($inicio),
            new \DateInterval('P1D'),
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
    //=========================================================================================
    //Mostra Relatórios no gráfico de linha
    //=========================================================================================
    public function linha()
    {
        UsuarioSession::deslogado();
        
        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];
        
        
        //RETORNA TODAS AS CONTAS/CATEGORIAS DO USUÁRIO
        try {
            Transaction::open('db');

            $dados['contas_usuario'] = ContaModel::loadAll(UsuarioSession::get('id'));
            $dados['categorias_usuario'] = Categoria::loadAll(UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        //LISTANDO DESPESAS POR CATEGORIAS
        if(empty($_POST))
        {
            try {
                Transaction::open('db');

                $sql = "SELECT data_trans,SUM(valor) AS total FROM transacao WHERE tipo = 'despesa' AND id_usuario = ".UsuarioSession::get('id')." AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE()) GROUP BY DATE_FORMAT(data_trans, '%Y%m%d')";

                $conn = Transaction::get();

                $resultado = $conn->query($sql);

                $resultado = $resultado->fetchAll(\PDO::FETCH_ASSOC);

                Transaction::close();

                $arrCombine = array_combine(array_column($resultado,'data_trans'),array_column($resultado,'total'));

                $arrDataTotal= array_merge($this->getDatasDoMes(),$arrCombine);

                $arrKeys = array_map(function($a){return formataDataBR($a);},array_keys($arrDataTotal));

                $arrDataTotal = array_combine($arrKeys,$arrDataTotal);

                
                $dados['despesa_por_categoria'] = $resultado;
                $dados['arr_dados'] = $arrDataTotal;
               
                
            } catch (\Exception $e) {
                Transaction::rollback();
            }
        }
        
        //Caso O usuário utilize o filtro
        if(!empty($_POST))
        {
            $sql = '';
            
            //FILTRAR PELA DATA
            $filtroData = '';
            switch($_POST['dataRadio'])
            {
                case 'dataMesAno':
                    $filtroData = "MONTH(t.data_trans) = MONTH('{$_POST['mesAno']}-01') AND YEAR(t.data_trans) = YEAR('{$_POST['mesAno']}-01') ";
                    break;
                case 'dataPeriodo':
                    $filtroData = "DATE(t.data_trans) BETWEEN DATE('{$_POST['dataInicio']}') AND DATE('{$_POST['dataFim']}')";
                    break;
                case 'dataAno':
                    $filtroData = "YEAR(data_trans) = YEAR('{$_POST['ano']}')";
                    break;
            }

            //SITUAÇÃO EFETUADAS E PENDENTES
            $situacao = '';
            switch($_POST['situacao'])
            {
                case 'todas':
                    $situacao = '';
                    break;
                case 'efetuadas':
                    $situacao = " AND t.status_trans = 'fechado' ";
                    break;
                case 'pendentes':
                    $situacao = " AND t.status_trans = 'pendente' ";
                    break;
            }

            //CONTA
            $conta = $_POST['conta'] == 0 ? '' : " AND id_conta = {$_POST['conta']}";

            //CATEGORIA
            $categoria = $_POST['categoria'] == 0 ? '' : " AND id_conta = {$_POST['conta']}";


    }

        $this->view([
            'templates/header',
            'relatorios/relatorio_linha',
            'templates/footer'
        ],$dados);
    }


    //=========================================================================================
    //Mostra Relatórios no gráfico de pizza
    //=========================================================================================
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        try {
            Transaction::open('db');

            $dados['contas_usuario'] = ContaModel::loadAll(UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }


        //LISTANDO DESPESAS POR CATEGORIAS
        if(empty($_POST))
        {
            try {
                Transaction::open('db');

                $sql = "SELECT t.id_categoria,c.nome,SUM(t.valor) as total FROM transacao as t INNER JOIN categoria as c ON t.id_categoria = c.idCategoria WHERE t.tipo = 'despesa' AND t.id_usuario = ".UsuarioSession::get("id")." AND MONTH(t.data_trans) = MONTH(CURDATE()) AND YEAR(t.data_trans) = YEAR(CURDATE()) GROUP BY t.id_categoria";

                $conn = Transaction::get();

                $resultado = $conn->query($sql);

                $resultado = $resultado->fetchAll(\PDO::FETCH_ASSOC);

                Transaction::close();


                $dados['despesa_por_categoria'] = $resultado;
                $dados['arr_total'] = implode(',',array_column($resultado,'total'));
                $dados['arr_nomeCate'] = "'".implode("','",array_column($resultado,'nome'))."'";



            } catch (\Exception $e) {
                Transaction::rollback();
            }
        }
        
        if(!empty($_POST))
        {       
            //Obtendo a lista de ids de contas do usuário
            $arrIdContas = [0];

            foreach ($dados['contas_usuario'] as $cu) {
               $arrIdContas[] = $cu->idConta;
            }
            // -------------------------------------------

            $v = new Validacao;

            if(isset($_POST['dataRadio']))
            {
                if($_POST['dataRadio'] == 'dataMesAno')
                {
                    if(isset($_POST['mesAno']))
                    {
                        $v->setCampo('Data mês')
                            ->data($_POST['mesAno'],'Y-m');
                    }
                    else{
                        FlashMessage::set('Data não especificada!','error','relatorios');
                    }
                }
                else if($_POST['dataRadio'] == 'dataPeriodo')
                {
                    if(isset($_POST['dataInicio']) and isset($_POST['dataFim']) )
                    {
                        $v->setCampo('dataInicio')
                            ->data($_POST['dataInicio'],'Y-m-d');

                        $v->setCampo('dataFim')
                            ->data($_POST['dataFim'],'Y-m-d');
                        
                        $v->setCampo('Data inicio e data Fim')
                            ->compararData($_POST['dataInicio'],$_POST['dataFim'],'maior');
                    }
                    else{
                        FlashMessage::set('Data não especificada!','error','relatorios');
                    }
                }
                else{
                    FlashMessage::set('Data não especificada!','error','relatorios');
                }
            }   
            else{
                FlashMessage::set('Data não especificada!','error','relatorios');
            }

            $v->setCampo('Selecione')
                ->select($_POST['filtrarPor'],[1,2,3,4,5]);

            $v->setCampo('Situação')
                ->select($_POST['situacao'],['todas','efetuadas','pendentes']);
            
            $v->setCampo('Conta')
                ->select($_POST['conta'],$arrIdContas);
            
            if($v->validar())
            {
                $sql = '';
                //CRIANDO QUERY PARA DATA
                $filtroData = '';

                switch($_POST['dataRadio'])
                {
                    case 'dataMesAno':
                        $filtroData = "MONTH(t.data_trans) = MONTH('{$_POST['mesAno']}-01') AND YEAR(t.data_trans) = YEAR('{$_POST['mesAno']}-01') ";
                        break;
                    case 'dataPeriodo':
                        $filtroData = "DATE(t.data_trans) BETWEEN DATE('{$_POST['dataInicio']}') AND DATE('{$_POST['dataFim']}')";
                        break;
                }


                //SITUAÇÃO
                $situacao = '';
                switch($_POST['situacao'])
                {
                    case 'todas':
                        $situacao = '';
                        break;
                    case 'efetuadas':
                        $situacao = " AND t.status_trans = 'fechado' ";
                        break;
                    case 'pendentes':
                        $situacao = " AND t.status_trans = 'pendente' ";
                        break;
                }

                //CONTA
                $conta = $_POST['conta'] == 0 ? '' : " AND t.id_conta = {$_POST['conta']}";

                //FILTRAR POR
                switch($_POST['filtrarPor'])
                {
                    case 1:
                        $sql = "SELECT t.id_categoria,c.nome,SUM(t.valor) as total FROM transacao as t INNER JOIN categoria as c ON t.id_categoria = c.idCategoria WHERE t.tipo = 'despesa' AND t.id_usuario = ".UsuarioSession::get('id')." AND {$filtroData} {$situacao} {$conta} GROUP BY t.id_categoria";
                        break;
                    case 2:
                        $sql = "SELECT t.id_conta,c.descricao,SUM(t.valor)  as total FROM transacao as t INNER JOIN conta AS c ON t.id_conta = c.idConta WHERE t.tipo = 'despesa' AND t.id_usuario = ".UsuarioSession::get('id')." AND {$filtroData} {$situacao} {$conta} GROUP BY t.id_conta";
                        break;
                    case 3:
                        $sql = "SELECT t.id_categoria,c.nome,SUM(t.valor)  as total FROM transacao as t INNER JOIN categoria as c ON t.id_categoria = c.idCategoria WHERE t.tipo = 'receita' AND t.id_usuario = ".UsuarioSession::get('id')." AND {$filtroData} {$situacao} {$conta} GROUP BY t.id_categoria";
                        break;
                    case 4:
                        $sql = "SELECT t.id_conta,c.descricao,SUM(t.valor)  as total FROM transacao as t INNER JOIN conta AS c ON t.id_conta = c.idConta WHERE t.tipo = 'receita' AND t.id_usuario = ".UsuarioSession::get('id')." AND {$filtroData} {$situacao} {$conta} GROUP BY t.id_conta";
                        break;
                }

                if(strlen($sql) < 1)
                {
                    FlashMessage::set('Ocorreu um erro!','error','relatorios');
                }

                try {
                    Transaction::open('db');

                    $conn = Transaction::get();

                    $resultado = $conn->query($sql);
                    $resultado = $resultado->fetchAll(\PDO::FETCH_ASSOC);

                    Transaction::close();


                } catch (\Exception $e) {
                    Transaction::rollback();
                }

                //FILTRAR POR
                switch($_POST['filtrarPor'])
                {
                    case 1:
                    $dados['despesa_por_categoria'] = $resultado;
                    $dados['arr_total'] = implode(',',array_column($resultado,'total'));
                    $dados['arr_nomeCate'] = "'".implode("','",array_column($resultado,'nome'))."'";
                    break;
                    case 2:
                    $dados['despesa_por_conta'] = $resultado;
                    $dados['arr_total'] = implode(',',array_column($resultado,'total'));
                    $dados['arr_nomeCate'] = "'".implode("','",array_column($resultado,'descricao'))."'";
                    break;
                    case 3:
                    $dados['receita_por_categoria'] = $resultado;
                    $dados['arr_total'] = implode(',',array_column($resultado,'total'));
                    $dados['arr_nomeCate'] = "'".implode("','",array_column($resultado,'nome'))."'";
                    break;
                    case 4:
                    $dados['receita_por_conta'] = $resultado;
                    $dados['arr_total'] = implode(',',array_column($resultado,'total'));
                    $dados['arr_nomeCate'] = "'".implode("','",array_column($resultado,'descricao'))."'";
                    break;
                }

            }
            else{
                FlashMessage::set($v->getMsgErros(),'error',"relatorios");
            }
        }

        $this->view([
            'templates/header',
            'relatorios/relatorio_pizza',
            'templates/footer'
        ],$dados);
    }
}
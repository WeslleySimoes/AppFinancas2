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
    private function getDatasDoMes($inicioData = null,$finalDAta = null,$periodoData = 'P1D')
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
            FlashMessage::set('Ocorreu um erro!','error','relatorios/linha');
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
                FlashMessage::set('Ocorreu um erro!','error','relatorios/linha');
            }
        }

        //Caso O usuário utilize o filtro
        if(!empty($_POST))
        {

            //Obtendo a lista de ids de contas do usuário
            $arrIdContas = [0];

            foreach ($dados['contas_usuario'] as $cu) {
                $arrIdContas[] = $cu->idConta;
            }

            //Obtendo a lista de ids de categorias do usuário
            $arrIdCategorias = ['todas'];

            foreach ($dados['categorias_usuario'] as $cu) {
                $arrIdCategorias[] = $cu->idCategoria;
            }

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
                        FlashMessage::set('Data não especificada!','error','relatorios/linha');
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
                        
                        $v->setCampo('Periodo')
                            ->diffMesesDatas($_POST['dataInicio'],$_POST['dataFim'],2);
                    }
                    else{
                        FlashMessage::set('Data não especificada!','error','relatorios/linha');
                    }
                }
                else if($_POST['dataRadio'] == 'dataAno')
                {   
                    $v->setCampo('Ano')
                        ->data($_POST['ano'],'Y');
                }
                else{
                    FlashMessage::set('Data não especificada!','error','relatorios/linha');
                }
            }   
            else{
                FlashMessage::set('Data não especificada!','error','relatorios/linha');
            }

            $v->setCampo('Selecione')
                ->select($_POST['selecione'],['despesas','receitas']);

            $v->setCampo('Situação')
                ->select($_POST['situacao'],['todas','efetuadas','pendentes']);
            
            $v->setCampo('Conta')
                ->select($_POST['conta'],$arrIdContas);
            
            $v->setCampo('categoria')
                ->select($_POST['categoria'],$arrIdCategorias);
            
            if($v->validar())
            {
                $sql = '';
                
                //FILTRAR PELA DATA
                $filtroData = '';
                switch($_POST['dataRadio'])
                {
                    case 'dataMesAno':
                        $filtroData = "MONTH(data_trans) = MONTH('{$_POST['mesAno']}-01') AND YEAR(data_trans) = YEAR('{$_POST['mesAno']}-01') ";
                        break;
                    case 'dataPeriodo':
                        $filtroData = "DATE(data_trans) BETWEEN DATE('{$_POST['dataInicio']}') AND DATE('{$_POST['dataFim']}')";
                        break;
                    case 'dataAno':
                        $filtroData = "YEAR(data_trans) = YEAR('{$_POST['ano']}-01-01')";
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
                        $situacao = " AND status_trans = 'fechado' ";
                        break;
                    case 'pendentes':
                        $situacao = " AND status_trans = 'pendente' ";
                        break;
                }
             
                //SELECIONE
                $selecione = $_POST['selecione'] == 'despesas' ? " AND tipo = 'despesa'" : " AND tipo = 'receita'";
                
                //CONTA
                $contaSQL = $_POST['conta'] == "0" ? "" : " AND id_conta = {$_POST['conta']}";
    
                //CATEGORIA
                $categoria = $_POST['categoria'] == "todas" ? "" : " AND id_categoria = {$_POST['categoria']}";
    
            
                switch($_POST['dataRadio'])
                {
                    case 'dataMesAno':
                        $sql = "SELECT data_trans,SUM(valor) AS total FROM transacao WHERE id_usuario = ".UsuarioSession::get('id')."{$situacao} {$selecione} {$contaSQL} {$categoria} AND {$filtroData} GROUP BY DATE_FORMAT(data_trans, '%Y%m%d')";
                        break;
                    case 'dataPeriodo':
                        $sql = "SELECT data_trans,SUM(valor) AS total FROM transacao WHERE id_usuario = ".UsuarioSession::get('id')."{$situacao} {$selecione} {$contaSQL} {$categoria} AND {$filtroData} GROUP BY DATE_FORMAT(data_trans, '%Y%m%d')";
                        break;
                    case 'dataAno':
                        $sql = "SELECT data_trans,SUM(valor) AS total FROM transacao WHERE id_usuario = ".UsuarioSession::get('id')."{$situacao} {$selecione} {$contaSQL} {$categoria} AND {$filtroData} GROUP BY DATE_FORMAT(data_trans, '%Y%m')";
                        break;
                }   
    
                try {
                    Transaction::open('db');
    
                    $conn = Transaction::get();
    
                    $resultado = $conn->query($sql);
    
                    $resultado = $resultado->fetchAll(\PDO::FETCH_ASSOC);
    
                    Transaction::close();               
                    
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro!','error','relatorios/linha');
                }
    
                if(isset($_POST['dataRadio']) and $_POST['dataRadio'] == 'dataAno')
                {
                    //dd($resultado);
                    $meses = $this->getDatasDoMes($_POST['ano'].'-01-01',$_POST['ano'].'-12-01','P1M');
    
                    $arrCombine = array_combine(array_column($resultado,'data_trans'),array_column($resultado,'total'));
    
                    $arrFinal = [];
    
    
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
    
                                $arrFinal[$monthName] = $value;
    
                                break;
                            }
                        }      
                        
                        if($bandeira)
                        {
                         
                            $monthName = utf8_encode(strftime("%B", strtotime($mes)));
    
                            $arrFinal[$monthName] = $valor;
                        }
                    }
    
                    $dados['arr_dados'] = empty($resultado) ? $meses  : $arrFinal;
                }
                else{
                    if(isset($_POST['dataRadio']) and $_POST['dataRadio'] == 'dataPeriodo')
                    {
                        $arrCombine = array_combine(array_column($resultado,'data_trans'),array_column($resultado,'total'));
                        
                        $arrDataTotal= array_merge($this->getDatasDoMes($_POST['dataInicio'],$_POST['dataFim']),$arrCombine);
                        
                        $arrKeys = array_map(function($a){return formataDataBR($a);},array_keys($arrDataTotal));
                        
                        $arrDataTotal = array_combine($arrKeys,$arrDataTotal);
                        
                        $dados['arr_dados'] = empty($resultado) ? $this->getDatasDoMes($_POST['dataInicio'],$_POST['dataFim']) : $arrDataTotal;
                    }
                    else{ 
    
    
                        $arrCombine = array_combine(array_column($resultado,'data_trans'),array_column($resultado,'total'));
        
                        $inicio = $_POST['mesAno'].'-01';
                        $fim    = $_POST['mesAno'].'-'.getDataFinalMesAtual($_POST['mesAno'].'-01');
    
                        $arrDataTotal= array_merge($this->getDatasDoMes($inicio,$fim),$arrCombine);
    
                        
                        $arrKeys = array_map(function($a){return formataDataBR($a);},array_keys($arrDataTotal));
        
                        $arrDataTotal = array_combine($arrKeys,$arrDataTotal);
    
                        $dados['arr_dados'] = empty($resultado) ? $this->getDatasDoMes($inicio,$fim) : $arrDataTotal;
                    }
                }
            } else{
                FlashMessage::set($v->getMsgErros(),'error',"relatorios/linha");
            } 
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
                        $filtroData = " MONTH(t.data_trans) = MONTH('{$_POST['mesAno']}-01') AND YEAR(t.data_trans) = YEAR('{$_POST['mesAno']}-01') ";
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



    //=========================================================================================
    //Mostra Relatórios no gráfico de linha
    //=========================================================================================
    public function barra()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];
        
        $dataAno = date('Y-m-d');
        $anoAtual = date('Y');

        //LISTANDO DESPESAS POR CATEGORIAS
        if(isset($_GET['ano']))
        {
            $dataAno = $_GET['ano'].'-01-01';
            $anoAtual = $_GET['ano'];
        }

        try {
            Transaction::open('db');

            $sqlDespesas = "SELECT data_trans,SUM(valor) as total FROM transacao WHERE id_usuario =".UsuarioSession::get('id')." AND tipo = 'despesa' AND YEAR(data_trans) = YEAR('{$dataAno}') GROUP BY DATE_FORMAT(data_trans, '%Y%m')";

            $sqlReceitas = "SELECT data_trans,SUM(valor) as total FROM transacao WHERE id_usuario =".UsuarioSession::get('id')." AND tipo = 'receita' AND YEAR(data_trans) = YEAR('{$dataAno}') GROUP BY DATE_FORMAT(data_trans, '%Y%m')";

            $conn = Transaction::get();

            $resultadoDespesas = $conn->query($sqlDespesas);
            $resultadoDespesas = $resultadoDespesas->fetchAll(\PDO::FETCH_ASSOC);
            
            $resultadoReceitas = $conn->query($sqlReceitas);
            $resultadoReceitas = $resultadoReceitas->fetchAll(\PDO::FETCH_ASSOC);
            Transaction::close();


            //Definindo as 'data_trans' como chaves e 'total' como valor das chaves
            $arrCombineDespesas = array_combine(array_column($resultadoDespesas,'data_trans'),array_column($resultadoDespesas,'total'));

            $arrCombineReceitas = array_combine(array_column($resultadoReceitas,'data_trans'),array_column($resultadoReceitas,'total'));

            $meses = $this->getDatasDoMes($anoAtual.'-01-01',$anoAtual.'-12-01','P1M');
            $meses2 = $this->getDatasDoMes($anoAtual.'-01-01',$anoAtual.'-12-01','P1M');

            // ARRAY FINAL DE DESPESAS
            $arrFinalDespesas = [];
            foreach ($meses as $mes => $valor) {
                $bandeira = true;
                foreach ($arrCombineDespesas as $data => $value) {
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

            // ARRAY FINAL DE RECEITAS

            $arrFinalReceitas = [];
            foreach ($meses2 as $mes => $valor) {
                $bandeira = true;
                foreach ($arrCombineReceitas as $data => $value) {
                    $data1 = explode('-',$data);
                    $mes1 = explode('-',$mes);

                    if($data1[0] == $mes1[0] AND $data1[1] == $mes1[1])
                    {
                        unset($meses2[$mes]);
                        $meses2[$data] = $value;
                        $bandeira = false;

                        $monthName = strftime("%B", strtotime($data));

                        $arrFinalReceitas[$monthName] = $value;

                        break;
                    }
                }      
                
                if($bandeira)
                {
                    
                    $monthName = utf8_encode(strftime("%B", strtotime($mes)));
                    $arrFinalReceitas[$monthName] = $valor;
                }
            }


            //ARRAY FINAL DE BALANÇO MENSAL

            $arrFinalBalancoMensal = [];

            foreach ($arrFinalDespesas as $mes => $valor) {
                $arrFinalBalancoMensal[$mes] = floatval(number_format($arrFinalReceitas[$mes] - $valor,2,'.',''));
            }
            $dados['arr_despesas'] = $arrFinalDespesas;
            $dados['arr_receitas'] = $arrFinalReceitas;
            $dados['arr_balancoMensal'] = $arrFinalBalancoMensal;

        
        } catch (\Exception $e) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro!','error','relatorios/linha');
        }
        
        

        $this->view([
            'templates/header',
            'relatorios/relatorio_barra',
            'templates/footer'
        ],$dados);
    }
}
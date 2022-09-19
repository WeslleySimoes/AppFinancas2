<?php 

namespace app\controller;

use app\helpers\Validacao;
use app\model\Transaction;
use app\helpers\FlashMessage;
use app\helpers\FormataMoeda;
use app\model\entity\Categoria;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Planejamento as PlanejamentoModel;
use app\model\entity\PlanejamentoCate;
use app\utils\FormataMoeda as UtilsFormataMoeda;

class Planejamento extends BaseController
{
    //=========================================================================================
    //Mostra o planejamento feito no mês ou planejamento personalizado
    //=========================================================================================
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        $dataFiltro = null;

        if(isset($_GET['data']))
        {
            $filtroData = \DateTime::createFromFormat('Y-m', $_GET['data']);
        
            if(!$filtroData || $filtroData->format('Y-m') != $_GET['data']){
                header('location: '.HOME_URL.'/planejamento');
                exit;
            }
            
            $dataFiltro = $_GET['data'];
        }


        try {
            Transaction::open('db');

            if($dataFiltro)
            {
                $df = explode('-',$dataFiltro);
                $dados['total_plan_mensal'] = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = '{$df[1]}' AND YEAR(data_fim) = '{$df[0]}'");
            }
            else{
                $dados['total_plan_mensal'] = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = MONTH(CURDATE()) AND YEAR(data_fim) = YEAR(CURDATE())");
            }

            $dados['total_plan_semanal'] =  PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'personalizado'");


            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        $this->view([
            'templates/header',
            'planejamento/list_planejamentoMensal',
            'templates/footer'
        ],$dados);
    }

    //=========================================================================================
    // CADASTRO DE PLANEJAMENTO MENSAL 
    //=========================================================================================

    public function cadastrarPM()
    {
        UsuarioSession::deslogado();

        $dataFiltro = null;

        if(isset($_GET['data']))
        {
            $filtroData = \DateTime::createFromFormat('Y-m', $_GET['data']);
        
            if(!$filtroData || $filtroData->format('Y-m') != $_GET['data']){
                header('location: '.HOME_URL.'/planejamento');
                exit;
            }
            
            $dataFiltro = $_GET['data'];
        }


        //VERIFICANDO SE JÁ EXISTE PLANEJAMENTO PARA O MÊS ATUAL
        try {
            Transaction::open('db');

            if($dataFiltro)
            {
                $df = explode('-',$dataFiltro);
                $planejamentoMensal = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = '{$df[1]}' AND YEAR(data_fim) = '{$df[0]}'");
            }
            else{
                $planejamentoMensal = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = MONTH(CURDATE())");
            }

            Transaction::close();

            if(count($planejamentoMensal) > 0)
            {
                header("location: ".HOME_URL."/planejamento");
                exit;
            }
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        //PROCESSO DE INSERÇÃO
        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        try {
            Transaction::open('db');

            $dados['categoriasDesp'] = Categoria::findBy(" tipo = 'despesa' AND id_usuario = ".UsuarioSession::get('id'));
            
            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
        }


        if(!empty($_POST))
        {
            $arrCatDesp = [];

            foreach($dados['categoriasDesp'] as $catDesp)
            {
                $arrCatDesp[] = $catDesp->idCategoria;
            }

            //VERIFICANDO SE EXISTE AS CATEGORIA SELECIONADAS NO BANCO DE DADOS
            if(empty(array_diff($_POST['categoria'],$arrCatDesp)) and !empty($_POST['item']))
            {
                foreach($_POST['item'] as $valor)
                {
                    $v = FormataMoeda::moedaParaFloat($valor);
                    
                    if(!is_float($v))
                    {
                        if($dataFiltro)
                        {
                            FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error','planejamento?data='.$dataFiltro);
                        }
                        else{
                            FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error');
                        }
                        break;
                    }              
                }

                //Continua para o cadastro
                $v = new Validacao;

                $v->setCampo('Renda mensal')
                    ->moeda($_POST['renda']);
                    
                $v->setCampo('Meta de gasto:')
                    ->campoNumInteiro($_POST['porcentMeta'])
                    ->max_int_valor($_POST['porcentMeta'],90)
                    ->min_int_valor($_POST['porcentMeta'],10);

                if($v->validar())
                {
                    //dd($_POST);

                    // REALIZANDO O CADASTRO DE PLENAJAMENTO MENSAL
                    try {
                        Transaction::open('db');

                        $r              = new PlanejamentoModel();
                        $r->valor       = FormataMoeda::moedaParaFloat($_POST['renda']);
                        $r->porcentagem = (int) $_POST['porcentMeta'];

                        if($dataFiltro)
                        {
                            $r->data_inicio = $dataFiltro.'-01';
                            $r->data_fim    = $dataFiltro.'-'.getDataFinalMesAtual($dataFiltro);
                        }
                        else{
                            $r->data_inicio = date('Y-m').'-01';
                            $r->data_fim    = getDataFinalMesAtual();
                        }

                        $r->tipo        = 'mensal';
                        $r->id_usuario  = UsuarioSession::get('id');

                        $pResultado = $r->cadastrar($_POST['categoria'],$_POST['item']);

                        Transaction::close();

                        if($pResultado)
                        {
                            if($dataFiltro)
                            {
                                FlashMessage::set('Planejamento criado com sucesso!','success','planejamento?data='.$dataFiltro);
                            }
                            else{
                                FlashMessage::set('Planejamento criado com sucesso!','success');
                            }
                        }
                        else{
                            if($dataFiltro)
                            {
                                FlashMessage::set('Erro ao criar planejamento ','error','planejamento?data='.$dataFiltro);
                            }
                            else{

                                FlashMessage::set('Erro ao criar planejamento ','error');
                            }
                        }
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }
                }
                else{
                    FlashMessage::set($v->getMsgErros(),'error');
                }
            }
            else{
                if($dataFiltro)
                {
                    FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error','planejamento?data='.$dataFiltro);
                }
                else{
                    FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error');
                }
            }

        }


        $this->view([
            'templates/header',
            'planejamento/cadastrar_planMensal',
            'templates/footer'
        ],$dados);

    }

    //=========================================================================================
    // REMOVER PLANEJAMENTO MENSAL
    //=========================================================================================
    public function removerPM()
    {
        UsuarioSession::deslogado();

        // =======================================================================
        // VALIDAÇÃO DO PARÂMETRO GET 'ID' E VERIFICANDO SE EXISTE O PLANEJAMENTO
        // =======================================================================
        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/planejamento");
            exit;
        }

        try {
            Transaction::open('db');

            $planejamento = PlanejamentoModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND tipo = 'mensal' AND idPlan = {$id}")[0];

            Transaction::close();


            //Se não achar o planejamento, o sistema volta a página de planejamento
            if(empty($planejamento) or !isset($planejamento))
            {
                header("location: ".HOME_URL."/planejamento");
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }
        
        // ==========================================
        // PROCESSO DE REMOÇÃO
        // ==========================================
        try {
            Transaction::open('db');

            $resultado = $planejamento->removerTudo();

            Transaction::close();

            if($resultado)
            {
                FlashMessage::set('Planejamento Removido com sucesso!','success',"planejamento");
            }
            else{
                FlashMessage::set('Erro ao tentar remover planejamento!','error',"planejamento");
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }
    }


    //=========================================================================================
    // EDITAR PLANEJAMENTO MENSAL
    //=========================================================================================
    public function editarPM()
    {
        UsuarioSession::deslogado();

        // =======================================================================
        // VALIDAÇÃO DO PARÂMETRO GET 'ID' E VERIFICANDO SE EXISTE O PLANEJAMENTO
        // =======================================================================
        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/planejamento");
            exit;
        }

        try {
            Transaction::open('db');

            $planejamento = PlanejamentoModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND tipo = 'mensal' AND idPlan = {$id}")[0];

            Transaction::close();


            //Se não achar o planejamento, o sistema volta a página de planejamento
            if(empty($planejamento) or !isset($planejamento))
            {
                header("location: ".HOME_URL."/planejamento");
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }

        // ==========================================
        // PROCESSO DE EDIÇÃO
        // ==========================================
        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'planejamento_atual' => $planejamento
        ];

        try {
            Transaction::open('db');

            $dados['categoriasDesp'] = Categoria::findBy(" tipo = 'despesa' AND id_usuario = ".UsuarioSession::get('id'));
            
            Transaction::close();


        } catch (\Exception $e) {
            Transaction::rollback();
        }


        if(!empty($_POST))
        {
            $arrIdCatPlanCat = [];
            foreach($planejamento->getPlanCategorias() as $planCat)
            {
                $arrIdCatPlanCat[] = $planCat->id_categoria;
            }

            //Fazendo alterações somente nos dados
            try {
                Transaction::open('db');
    
                $planejamentoEditar = new PlanejamentoModel($id);
                $planejamentoEditar->valor = FormataMoeda::moedaParaFloat($_POST['renda']);
                $planejamentoEditar->porcentagem = (int) $_POST["porcentMeta"];

                $resultado = $planejamentoEditar->editarM($_POST['categoria'],$_POST['item'],$arrIdCatPlanCat);

                Transaction::close();

                if($resultado)
                {
                    FlashMessage::set('Planejamento alterado com sucesso!','success',"planejamento");
                }
                else{
                    FlashMessage::set('Erro ao tentar efdditar planejamento!','error',"planejamento");
                }

                dd($planejamentoEditar);
            } catch (\Exception $e) {
                Transaction::rollback();
                FlashMessage::set('Erro ao tentar editar planejamento!','error',"planejamento");
            }
        }

        $this->view([
            'templates/header',
            'planejamento/editar_planMensal',
            'templates/footer'
        ],$dados);
    }

    //=========================================================================================
    // CADASTRAR PLANEJAMENTO PERSONALIZADO
    //=========================================================================================
    public function cadastrarPP()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        //##############################################
        // OBTENDO TODAS AS CATEGORIAS DO TIPO DESPESA
        //##############################################
        try {
            Transaction::open('db');

            $dados['categoriasDesp'] = Categoria::findBy(" tipo = 'despesa' AND id_usuario = ".UsuarioSession::get('id'));
            
            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
        }

        #INSERÇÃO DE DADOS
        if(!empty($_POST))
        {

            //dd($_POST);
            $arrCatDesp = [];

            foreach($dados['categoriasDesp'] as $catDesp)
            {
                $arrCatDesp[] = $catDesp->idCategoria;
            }

            //VERIFICANDO SE EXISTE AS CATEGORIA SELECIONADAS NO BANCO DE DADOS
            if(empty(array_diff($_POST['categoria'],$arrCatDesp)) and !empty($_POST['item']))
            {
                foreach($_POST['item'] as $valor)
                {
                    $v = FormataMoeda::moedaParaFloat($valor);
                    
                    if(!is_float($v))
                    {
                        FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error');
                        break;
                    }              
                }

                //Continua para o cadastro
                $v = new Validacao;

                FormataMoeda::somarMoedas($_POST['item']);
    
                $v->setCampo('Renda mensal')
                    ->moeda($_POST['renda'])
                    ->max_moeda($_POST['renda']);
                    
                $v->setCampo('Descrição')
                    ->min_caracteres($_POST['descricao'])
                    ->max_caracteres($_POST['descricao']);
                
                $v->setCampo('Data início')
                    ->data($_POST['dataInicio'],'Y-m-d');

                $v->setCampo('Data final')
                    ->data($_POST['dataFinal'],'Y-m-d');

                $v->setCampo('Data início e Data final')
                    ->compararData($_POST['dataInicio'],$_POST['dataFinal'],'maior');

                $v->setCampo('Valor (R$) e Insira uma meta para cada categoria')
                  ->moedasIguais($_POST['renda'],$_POST['item']);

                $v->setCampo('Categoria e valor de categoria')
                  ->arrayIguais($_POST['categoria'],$_POST['item']);

                if($v->validar())
                {
                    //CADASTRO DE PLANEJAMENTO PERSONALIZADO
                    try {
                        Transaction::open('db');

                        $planS = new PlanejamentoModel();
                        $planS->valor = FormataMoeda::moedaParaFloat($_POST['renda']);
                        $planS->descricao = $_POST['descricao'];
                        $planS->data_inicio = $_POST['dataInicio'];
                        $planS->data_fim = $_POST['dataFinal'];
                        $planS->tipo = 'personalizado';
                        $planS->id_usuario = UsuarioSession::get('id');

                        $pResultado = $planS->cadastrar($_POST['categoria'],$_POST['item']);

                        Transaction::close();

                        if($pResultado)
                        {
                            FlashMessage::set('Planejamento personalizado criado com sucesso!','success');
                        }
                        else{
                            FlashMessage::set('Erro ao criar planejamento personalizado ','error');
                        }
                    } catch (\Exception $e) {
                        Transaction::rollback();
                        FlashMessage::set('Erro ao tentar cadastrar planejamento personalizado!','error');
                    }
                }
                else{
                    FlashMessage::set($v->getMsgErros(),'error');
                }
            }


        }   
        
        $this->view([
            'templates/header',
            'planejamento/cadastro_planPersonalizado',
            'templates/footer'
        ],$dados);
    }

        //=========================================================================================
    // REMOVER PLANEJAMENTO PESONALIZADO
    //=========================================================================================
    public function removerPP()
    {
        UsuarioSession::deslogado();

        // =======================================================================
        // VALIDAÇÃO DO PARÂMETRO GET 'ID' E VERIFICANDO SE EXISTE O PLANEJAMENTO
        // =======================================================================
        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/planejamento?p=personalizado");
            exit;
        }

        try {
            Transaction::open('db');

            $planejamento = PlanejamentoModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND tipo = 'personalizado' AND idPlan = {$id}")[0];

            Transaction::close();

            //Se não achar o planejamento, o sistema volta a página de planejamento
            if(empty($planejamento) or !isset($planejamento))
            {
                header("location: ".HOME_URL."/planejamento?p=personalizado");
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }
        
        // ==========================================
        // PROCESSO DE REMOÇÃO
        // ==========================================
        try {
            Transaction::open('db');

            $resultado = $planejamento->removerTudo();

            Transaction::close();

            if($resultado)
            {
                FlashMessage::set('Planejamento Removido com sucesso!','success',"planejamento?p=personalizado");
            }
            else{
                FlashMessage::set('Erro ao tentar remover planejamento!','error',"planejamento?p=personalizado");
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }
    }

    //=========================================================================================
    // EDITAR PLANEJAMENTO PESONALIZADO
    //=========================================================================================
    public function editarPP()
    {
        UsuarioSession::deslogado();

        // =======================================================================
        // VALIDAÇÃO DO PARÂMETRO GET 'ID' E VERIFICANDO SE EXISTE O PLANEJAMENTO
        // =======================================================================
        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/planejamento?p=personalizado");
            exit;
        }

        try {
            Transaction::open('db');

            $planejamento = PlanejamentoModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND tipo = 'personalizado' AND idPlan = {$id}")[0];

            Transaction::close();

            //Se não achar o planejamento, o sistema volta a página de planejamento
            if(empty($planejamento) or !isset($planejamento))
            {
                header("location: ".HOME_URL."/planejamento?p=personalizado");
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }

        // ==========================================
        // PROCESSO DE EDIÇÃO
        // ==========================================
        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'planejamento_atual' => $planejamento
        ];

        try {
            Transaction::open('db');

            $dados['categoriasDesp'] = Categoria::findBy(" tipo = 'despesa' AND id_usuario = ".UsuarioSession::get('id'));
            
            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
        }


        if(!empty($_POST))
        {
            $arrIdCatPlanCat = [];
            foreach($planejamento->getPlanCategorias() as $planCat)
            {
                $arrIdCatPlanCat[] = $planCat->id_categoria;
            }

            //Fazendo alterações somente nos dados
            try {
                Transaction::open('db');
    
                $planS = new PlanejamentoModel($id);
                $planS->valor = FormataMoeda::moedaParaFloat($_POST['renda']);
                $planS->descricao = $_POST['descricao'];
                $planS->data_inicio = $_POST['dataInicio'];
                $planS->data_fim = $_POST['dataFinal'];
                $planS->tipo = 'personalizado';
                $planS->id_usuario = UsuarioSession::get('id');

                $resultado = $planS->editarM($_POST['categoria'],$_POST['item'],$arrIdCatPlanCat);

                Transaction::close();

                if($resultado)
                {
                    FlashMessage::set('Planejamento personalizado alterado com sucesso!','success',"planejamento?p=personalizado");
                }
                else{
                    FlashMessage::set('Erro ao tentar editar planejamento personalizado!','error',"planejamento?p=personalizado");
                }
            } catch (\Exception $e) {
                Transaction::rollback();
                FlashMessage::set('Erro ao tentar editar planejamento personalizado!','error',"planejamento?p=personalizado");
            }
        }

        $this->view([
            'templates/header',
            'planejamento/editar_planPersonalizado',
            'templates/footer'
        ],$dados);
    }

    //=========================================================================================
    // DETALHE DE PLANEJAMENTO PESONALIZADO
    //=========================================================================================
    public function detalhePP()
    {
        UsuarioSession::deslogado();

        // =======================================================================
        // VALIDAÇÃO DO PARÂMETRO GET 'ID' E VERIFICANDO SE EXISTE O PLANEJAMENTO
        // =======================================================================
        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/planejamento?p=personalizado");
            exit;
        }

        try {
            Transaction::open('db');

            $planejamento = PlanejamentoModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND tipo = 'personalizado' AND idPlan = {$id}")[0];

            Transaction::close();

            //Se não achar o planejamento, o sistema volta a página de planejamento
            if(empty($planejamento) or !isset($planejamento))
            {
                header("location: ".HOME_URL."/planejamento?p=personalizado");
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();
        }

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'detalhePP' => $planejamento

        ];

        $this->view([
            'templates/header',
            'planejamento/detalhe_planPerso',
            'templates/footer'
        ],$dados);
    }
}
 
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

        try {
            Transaction::open('db');

            $dados['total_plan_mensal'] = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = MONTH(CURDATE())");

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
    // CADASTRO DE PLANEJAMENTO MENSAL OU PERSONALIZADO
    //=========================================================================================

    public function cadastrarPM()
    {
        UsuarioSession::deslogado();

        //VERIFICANDO SE JÁ EXISTE PLANEJAMENTO PARA O MÊS ATUAL
        try {
            Transaction::open('db');

            $planejamentoMensal = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = MONTH(CURDATE())");

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
                        FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error');
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
                        $r->data_inicio = date('Y-m').'-01';
                        $r->data_fim    = getDataFinalMesAtual();
                        $r->tipo        = 'mensal';
                        $r->id_usuario  = UsuarioSession::get('id');

                        $pResultado = $r->cadastrar($_POST['categoria'],$_POST['item']);

                        Transaction::close();

                        if($pResultado)
                        {
                            FlashMessage::set('Planejamento criado com sucesso!','success');
                        }
                        else{
                            FlashMessage::set('Erro ao criar planejamento ','error');
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
                FlashMessage::set('Ocorreu um erro ao cadastrar planejamento!','error');
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
        
        $this->view([
            'templates/header',
            'planejamento/cadastro_planPersonalizado',
            'templates/footer'
        ],$dados);
    }
}
 
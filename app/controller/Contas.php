<?php 

namespace app\controller;

use app\session\Usuario as UsuarioSession;
use app\helpers\{FlashMessage, Validacao,FormataMoeda};
use app\model\entity\Conta as ContaModel;
use app\model\Transaction;

class Contas extends BaseController
{
    // Listar Contas Cadastradas
    public function index()
    {
        UsuarioSession::deslogado();
        
        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        ###########################################################################################
        //Verifica se o usuário possui conta, caso contrário o redireciona para cadastro de contas
        ###########################################################################################
        try {
            Transaction::open('db');

            $totalContasUsuarioAtual = ContaModel::totalContas(UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }
        
        if($totalContasUsuarioAtual  == 0)
        {
            header('location: '.HOME_URL.'/contas/cadastrar');
            exit;
        }
        //##################################################################################

        $status_conta = htmlspecialchars(filter_input(INPUT_GET,'status'));
        $status_conta = in_array($status_conta,['ativo','arquivado']) ? " status_conta = '{$status_conta}'" : "status_conta = 'ativo'";

        if(isset($_GET['data']))
        {
            $v = new Validacao();

            $v->setCampo('Mês/Ano')
                ->validateDate($_GET['data'],'Y-m');

            if(!$v->validar())
            {   
                FlashMessage::set($v->getMsgErros(),'error','contas/listar');
            }
            else{
                $dados['data_filtro_mes'] = $_GET['data'].'-'.getDataFinalMesAtual($_GET['data'].'-01');
            }
        }

        try {
            Transaction::open('db');

            $dados['contas_usuario'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND {$status_conta}");

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }
        //Transaction::open('db');

        $this->view([
            'templates/header',
            'contas/listagem_contas',
            'templates/footer'
        ],$dados);
    }

    // Cadastrar contas para o usuário
    public function cadastrar()
    {
        UsuarioSession::deslogado();

        if(isset($_POST['valor']) and !empty($_POST))
        {
            $v = new Validacao;

            $v->setCampo('Valor')
                ->moeda($_POST['valor']);

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);

            $v->setCampo('Instituição')
                ->min_caracteres($_POST['instituicao'])
                ->max_caracteres($_POST['instituicao']);

            $v->setCampo('Tipo de conta')
                ->select($_POST['tipoConta'],['Corrente','Poupança','Dinheiro','Outros']);

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    $cm = new ContaModel();
                    $cm->montante        = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $cm->descricao       = $_POST['descricao'];
                    $cm->instituicao_fin = $_POST['instituicao'];
                    $cm->tipo_conta      = $_POST['tipoConta'];
                    $cm->id_usuario      = UsuarioSession::get('id');
    
                    $resultado = $cm->store();
    
                    Transaction::close();
                    
                    if($resultado)
                    {
                        FlashMessage::set('Conta cadastrada com sucesso!','success');
                    }
                    else{
                        FlashMessage::set('Ocorreu um erro ao cadastrar!','error');
                    }
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro ao cadastrar!','error');
                }

            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }

        $this->view([
            'templates/header',
            'contas/cadastro_conta',
            'templates/footer'
        ],[
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg'=> FlashMessage::get()
        ]);
    }

    public function editar()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg'=> FlashMessage::get()
        ];

        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/contas/listar");
            exit;
        }

        try {
            Transaction::open('db');

            $contasUsuario = ContaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND idConta = ".$id)[0];

            Transaction::close();

            if(empty($contasUsuario))
            {
                header('location: '.HOME_URL.'/contas/listar');
                exit;
            }

            $dados['dados_conta'] = $contasUsuario;

        } catch (\Exception $e) {
            Transaction::rollback();
        }

        if(!empty($_POST))
        {
            $v = new Validacao;

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);

            $v->setCampo('Instituição')
                ->min_caracteres($_POST['instituicao'])
                ->max_caracteres($_POST['instituicao']);

            $v->setCampo('Tipo de conta')
                ->select($_POST['tipoConta'],['Corrente','Poupança','Dinheiro','Outros']);

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    $cm = $contasUsuario;
                    $cm->descricao       = $_POST['descricao'];
                    $cm->instituicao_fin = $_POST['instituicao'];
                    $cm->tipo_conta      = $_POST['tipoConta'];
   
                    $resultado = $cm->store();
    
                    Transaction::close();
                    
                    if($resultado)
                    {
                        FlashMessage::set('Conta alterada com sucesso!','success','contas/editar?id='.$id);
                    }
                    else{
                        FlashMessage::set('Ocorreu um erro ao alterar conta!','error','contas/editar?id='.$id);
                    }
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro ao alterar conta!','error','contas/editar?id='.$id);
                }
            } else{
                FlashMessage::set($v->getMsgErros(),'error','contas/editar?id='.$id);
            }
        }
        $this->view([
            'templates/header',
            'contas/editar_conta',
            'templates/footer'
        ],$dados);
    }

    //ARQUIVAR CONTA
    public function arquivar()
    {
        UsuarioSession::deslogado();

        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/contas/listar");
            exit;
        }

        try {
            Transaction::open('db');

            $contasUsuario = ContaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND idConta = ".$id)[0];

            Transaction::close();

            if(empty($contasUsuario))
            {
                header('location: '.HOME_URL.'/contas/listar');
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();

        }

        try {
            Transaction::open('db');

            $contaUsuario = $contasUsuario;
            $contaUsuario->status_conta = 'arquivado';

            $resultado = $contaUsuario->store();
    
            Transaction::close();
            
            if($resultado)
            {
                FlashMessage::set('Conta arquivada com sucesso!','success','contas/listar');
            }
            else{
                FlashMessage::set('Ocorreu um erro ao arquivar conta!','error','contas/listar');
            }

        } catch (\Exception $e) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao arquivar conta!','error','contas/listar');
        }

    }

    //DESARQUIVAR CONTA
    public function desarquivar()
    {
        UsuarioSession::deslogado();

        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/contas/listar");
            exit;
        }

        try {
            Transaction::open('db');

            $contasUsuario = ContaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND idConta = ".$id)[0];

            Transaction::close();

            if(empty($contasUsuario))
            {
                header('location: '.HOME_URL.'/contas/listar');
                exit;
            }

        } catch (\Exception $e) {
            Transaction::rollback();

        }

        try {
            Transaction::open('db');

            $contaUsuario = $contasUsuario;
            $contaUsuario->status_conta = 'ativo';

            $resultado = $contaUsuario->store();
    
            Transaction::close();
            
            if($resultado)
            {
                FlashMessage::set('Conta desarquivada com sucesso!','success','contas/listar');
            }
            else{
                FlashMessage::set('Ocorreu um erro ao desarquivada conta!','error','contas/listar');
            }

        } catch (\Exception $e) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao desarquivada conta!','error','contas/listar');
        }

    }
}
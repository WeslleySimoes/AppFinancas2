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
            'usuario_logado' => UsuarioSession::get('nome')
        ];

        try {
            Transaction::open('db');

            $dados['contas_usuario'] = ContaModel::loadAll(UsuarioSession::get('id'));

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
                ->select($_POST['tipoConta'],['Corrente','Poupança','Dinheiro','Outro']);

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
}
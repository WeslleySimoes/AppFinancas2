<?php 

namespace app\controller;

use app\session\Usuario as UsuarioSession;
use app\helpers\{FlashMessage, Validacao};
use app\model\Transaction;
use app\model\entity\Categoria as CategoriaModel;
use app\model\entity\Transacao;

class Categoria extends BaseController
{
    // Método responsável por listar todas as categorias
    public function index()
    {
        UsuarioSession::deslogado();

        $this->view([
            'templates/header',
            'categorias/listagem_categorias',
            'templates/footer'
        ],[
            'usuario_logado' => UsuarioSession::get('nome')
        ]);
    }   

    // Método responsável por cadastrar as categorias
    public function cadastrar()
    {
        UsuarioSession::deslogado();

        if(isset($_POST['nomeCategoria']) and !empty($_POST))
        {
            $v = new Validacao;
            $v->setCampo('Nome da categoria')
                ->min_caracteres($_POST['nomeCategoria'])
                ->max_caracteres($_POST['nomeCategoria']);

            $v->setCampo('Tipo de categoria')
                ->select($_POST['tipoCategoria'],['despesa','receita']);
            
            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    $cm = new CategoriaModel();
                    $cm->nome       = $_POST['nomeCategoria'];
                    $cm->tipo       = $_POST['tipoCategoria'];
                    $cm->id_usuario = UsuarioSession::get('id');

                    $resultado = $cm->store();

                    Transaction::close();

                    if($resultado)
                    {
                        FlashMessage::set('Categoria cadastrada com sucesso!','success');
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
            'categorias/cadastro_categorias',
            'templates/footer'
        ],[
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg'=> FlashMessage::get()
        ]);
    }
}
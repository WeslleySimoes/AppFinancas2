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

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg'=> FlashMessage::get()
        ];

        try {
            Transaction::open('db');
                
            $categoria = CategoriaModel::loadAll(UsuarioSession::get('id'));

            $categoriasAtivas = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND status_cate = 'ativo'");

            Transaction::close();

            $nomeCategorias = [];

            foreach($categoria as $c)
            {
                $nomeCategorias[] = $c->nome;
            }
            
            $dados['categoriasAtivas'] = $categoriasAtivas;

           
            
        } catch (\Exception $th) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao cadastrar!','error');
        }

        $this->view([
            'templates/header',
            'categorias/listagem_categorias',
            'templates/footer'
        ],$dados);
    }   

    // Método responsável por cadastrar as categorias
    public function cadastrar()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg'=> FlashMessage::get()
        ];


        try {
            Transaction::open('db');
                
            $categoria = CategoriaModel::loadAll(UsuarioSession::get('id'));

            $categoriasAtivas = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND status_cate = 'ativo'");

            Transaction::close();

            $nomeCategorias = [];

            foreach($categoria as $c)
            {
                $nomeCategorias[] = $c->nome;
            }
        } catch (\Exception $th) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao cadastrar!','error');
        }

        if(count($categoriasAtivas) >= 10)
        {
            header('location: '.HOME_URL.'/categorias');
            exit;
        }
        
        if(isset($_POST['nomeCategoria']) and !empty($_POST))
        {


            $v = new Validacao;

            $v->setCampo('Nome da categoria')
                ->min_caracteres($_POST['nomeCategoria'])
                ->max_caracteres($_POST['nomeCategoria'])
                ->select(ucfirst(mb_strtolower($_POST['nomeCategoria'])),$nomeCategorias,false);

            $v->setCampo('Tipo de categoria')
                ->select($_POST['tipoCategoria'],['despesa','receita']);
            
            $v->setCampo('Cor da categoria')
                ->is_hex($_POST['corCate']);
            
            if($v->validar())
            {
                try {
                    Transaction::open('db');
                    
                    $cm = new CategoriaModel();
                    $cm->nome        = ucfirst(mb_strtolower($_POST['nomeCategoria']));
                    $cm->tipo        = $_POST['tipoCategoria'];
                    $cm->id_usuario  = UsuarioSession::get('id');
                    $cm->status_cate = 'ativo';
                    $cm->cor_cate    = $_POST['corCate'];

                    $resultado = $cm->store();

                    Transaction::close();

                    if($resultado)
                    {
                        FlashMessage::set('Categoria cadastrada com sucesso!','success','categorias');
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
            'categorias/cadastro_categorias',
            'templates/footer'
        ],$dados);
    }

    // Método responsável por cadastrar as categorias
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
            header("location: ".HOME_URL."/categorias");
            exit;
        }

        try {
            Transaction::open('db');

            $categoria = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND status_cate = 'ativo'");

            $categoriaAtivas = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND status_cate = 'ativo' AND idCategoria = {$_GET['id']}");

            Transaction::close();

            $nomeCategorias = [];

            foreach($categoria as $c)
            {
                $nomeCategorias[] = $c->nome;
            }
            
            $dados['get_categoria'] = $categoriaAtivas[0];

            $nomeCategorias = array_count_values($nomeCategorias);
            unset($nomeCategorias[$categoriaAtivas[0]->nome]);



        } catch (\Exception $th) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao cadastrar!','error',"categorias/editar?id={$id}");
        }


        if(isset($_POST['nomeCategoria']) and !empty($_POST))
        {
            $v = new Validacao;

            $v->setCampo('Nome da categoria')
                ->min_caracteres($_POST['nomeCategoria'])
                ->max_caracteres($_POST['nomeCategoria'])
                ->select(ucfirst(mb_strtolower($_POST['nomeCategoria'])),array_keys($nomeCategorias),false);

            $v->setCampo('Tipo de categoria')
                ->select($_POST['tipoCategoria'],['despesa','receita']);
            
            $v->setCampo('Cor da categoria')
                ->is_hex($_POST['corCate']);

            
            if($v->validar())
            {
                try {
                    Transaction::open('db');
                    
                    $cm = new CategoriaModel();
                    $cm->idCategoria = $id;
                    $cm->nome        = ucfirst(mb_strtolower($_POST['nomeCategoria']));
                    $cm->tipo        = $_POST['tipoCategoria'];
                    $cm->id_usuario  = UsuarioSession::get('id');
                    $cm->status_cate = 'ativo';
                    $cm->cor_cate    = $_POST['corCate'];

                    $resultado = $cm->store();

                    Transaction::close();

                    if($resultado)
                    {
                        FlashMessage::set('Categoria alterada com sucesso!','success','categorias');
                    }
                    else{
                        FlashMessage::set('Ocorreu um erro ao alterar!','error',"categorias/editar?id={$id}");
                    }
                    
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro ao alterar!','error',"categorias/editar?id={$id}");
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error',"categorias/editar?id={$id}");
            }
        }

        $this->view([
            'templates/header',
            'categorias/editar_categorias',
            'templates/footer'
        ],$dados);
    }
}
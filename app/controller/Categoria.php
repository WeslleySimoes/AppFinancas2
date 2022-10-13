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

        unset($_GET['categorias']);

        $where = " AND status_cate = 'ativo'";

        if(!empty($_GET))
        {
            $arquivadas = htmlspecialchars(filter_input(INPUT_GET,'status'));
            $arquivadas = $arquivadas == 'arquivado' ? $arquivadas : '';

            $tipo = htmlspecialchars(filter_input(INPUT_GET,'tipo'));
            $tipo = in_array($tipo,['despesa','receita']) ? $tipo : '';
    
            $condicoes = [
                strlen($arquivadas) ? " status_cate = '{$arquivadas}'" : "status_cate = 'ativo'",
                strlen($tipo) ? " tipo = '{$tipo}'" : ''
            ];
    
            $condicoes = array_filter($condicoes);
    
            $where = strlen(implode(" AND ",$condicoes)) ? "AND ".implode(" AND ",$condicoes) : '';
        }

        try {
            Transaction::open('db');

            $pg = isset($_GET['pg']) ? intval($_GET['pg']) : 1;
            $dados['pg_atual'] = $pg;

            unset($_GET['categorias']);
            unset($_GET['pg']);
    
            $dados['query_get'] = http_build_query($_GET);

            $categoriasAtivas = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." {$where}");
  
            //Obtendo total de categorias
            $totalCate = count($categoriasAtivas);

            //Quantidade de transações por página
            $quantidade_pg = 15;
            
            //Total de links antes e depois da página atual
            $dados['max_links'] = 2;

            //Calculando total de páginas
            $num_pag = ceil($totalCate/$quantidade_pg);

            $dados['num_pag'] = $num_pag;

            //Calcular o inicio da visualização
            $inicio = ($quantidade_pg * $pg) - $quantidade_pg;

            $where .= " LIMIT {$inicio}, {$quantidade_pg} ";

            $categoriasFinal = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." {$where}");

            Transaction::close();
            
            $dados['categoriasAtivas'] = $categoriasFinal;

            if($pg > $num_pag and $num_pag > 0)
            {
                echo 'Pagina não encontrada!';
                echo "<br><a href='".HOME_URL."/categorias'>Voltar</a>";
                exit;
            }
        
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

    //=========================================================================================
    // MÉTODO RESPONSÁVEL ARQUIVAR CATEGORIA
    //=========================================================================================
    public function arquivar()
    {
        UsuarioSession::deslogado();

        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/categorias");
            exit;
        }

        try {
            Transaction::open('db');

            $getCategoria = CategoriaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND idCategoria = {$id}")[0];

            $getCategoria->status_cate = 'arquivado';

            $resultado = $getCategoria->store();

            Transaction::close();
        }catch(\Exception $e)
        {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao arquivar a categoria','error','categorias');
        }
        
        if($resultado)
        {
            FlashMessage::set('Categoria arquivada com sucesso!','success','categorias');
        }else{
            FlashMessage::set('Ocorreu um erro ao arquivar a categoria!','error','categorias');
        }
    }


    //=========================================================================================
    // MÉTODO RESPONSÁVEL DESARQUIVAR CATEGORIA
    //=========================================================================================
    public function desarquivar()
    {
        UsuarioSession::deslogado();

        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        if(!isset($id) or !$id > 0)
        {
            header("location: ".HOME_URL."/categorias");
            exit;
        }

        try {
            Transaction::open('db');

            $getCategoria = CategoriaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND idCategoria = {$id}")[0];

            $getCategoria->status_cate = 'ativo';

            $resultado = $getCategoria->store();

            Transaction::close();
        }catch(\Exception $e)
        {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro ao desarquivar a categoria','error','categorias?status=arquivado');
        }
        
        if($resultado)
        {
            FlashMessage::set('Categoria desarquivada com sucesso!','success','categorias?status=arquivado');
        }else{
            FlashMessage::set('Ocorreu um erro ao desarquivar a categoria!','error','categorias?status=arquivado');
        }
    }
}   
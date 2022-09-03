<?php 

namespace app\controller;

use app\model\Transaction;
use app\helpers\FlashMessage;
use app\model\entity\Transacao;
use app\session\Usuario as UsuarioSession;
use app\model\entity\{Categoria, Conta, ReceitaFixa, DespesaFixa}; 

class Transacoes extends BaseController
{
    // Método responsável por listar todas as transações
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = array(
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        );

        if(isset($_GET['s']))
        {
            switch ($_GET['s']) {
                case 'receitasFixas':

                    try {
                
                        Transaction::open('db');
            
                        $dados['arr_dados'] = ['receitas',ReceitaFixa::loadALl(UsuarioSession::get('id'))];
            
                        Transaction::close();
            
                    } catch (\Exception $e) {
                        Transaction::rollback();
                        echo $e->getMessage();
                    }
                    break;

                case 'despesasFixas':
                    try {
                
                        Transaction::open('db');
            
                        $dados['arr_dados'] = ['despesas',DespesaFixa::loadALl(UsuarioSession::get('id'))];
            
                        Transaction::close();
            
                    } catch (\Exception $e) {
                        Transaction::rollback();
                        echo $e->getMessage();
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }
        else{
        
            if(isset($_GET['pg']) and $_GET['pg'] < 1)
            {
                echo 'Pagina não encontrada!';
                echo "<br><a href='".HOME_URL."/transacoes'>Voltar</a>";
                exit;
             }

            try {
                
                Transaction::open('db');

                //Verifica se existe o get chamado 'pg'
                $pg = isset($_GET['pg']) ? intval($_GET['pg']) : 1;
                $dados['pg_atual'] = $pg;

                //################ FILTRO ######################

                //OBTENDO TODOS OS ID DA CONTA DO USUÁRIO ATUAL
                $conta = Conta::loadAll(UsuarioSession::get('id'));
                $idContas = [];

                $dados['listaContas'] = $conta;

                foreach($conta as $c)
                {
                    $idContas[] = $c->idConta;
                }

                //OBTENDO TODOS OS ID DAS CATEGORIAS DO USUÁRIO ATUAL
                $categoria = Categoria::loadAll(UsuarioSession::get('id'));
                $idCategorias = [];

                $dados['listaCategorias'] = $categoria;

                foreach($categoria as $c)
                {
                    $idCategorias[] = $c->idCategoria;
                }
                //####################################################
                
                //Status
                $filtroStatus = htmlspecialchars(filter_input(INPUT_GET,'status'));
                $filtroStatus = in_array($filtroStatus,['pendente','fechado']) ? $filtroStatus : '';

                //Tipo
                $tipoTrans    = htmlspecialchars(filter_input(INPUT_GET,'tipo'));
                $tipoTrans    = in_array($tipoTrans,['despesa','receita','transferencia']) ? $tipoTrans : '';

                //Conta
                $contaURL    = filter_input(INPUT_GET,'conta',FILTER_SANITIZE_NUMBER_INT);
                $contaURL    = in_array($contaURL,$idContas) ? $contaURL : '';

                //Categoria
                $categoriaURL = filter_input(INPUT_GET,'categoria',FILTER_SANITIZE_NUMBER_INT);
                $categoriaURL = in_array($categoriaURL,$idCategorias) ? $categoriaURL : '';

                //Data
                $dataURL    = isset($_GET['data']) ? $_GET['data'] : '';

                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dataURL)) {
                    $dataURL = $dataURL;
                } else {
                    $dataURL = '';
                }

                $condicoes = [
                    strlen($filtroStatus) ? "status_trans = '{$filtroStatus}'" : null,
                    strlen($tipoTrans) ? "tipo = '{$tipoTrans}'" : null,
                    $contaURL > 0 ? "id_conta = {$contaURL}": null,
                    $categoriaURL > 0 ? "id_categoria = {$categoriaURL}": null,
                    strlen($dataURL) ? "data_trans = '{$dataURL}'" : null,
                ];

                $condicoes = array_filter($condicoes);

                $where = implode(" AND ",$condicoes);

                unset($_GET['transacoes']);
                unset($_GET['pg']);

                $dados['query_get'] = http_build_query($_GET);

                //############ FIM DO FILTRO ###################

                //Obtendo total de transações
                $totalT = Transacao::total(UsuarioSession::get('id'),$where)['total'];

                //Quantidade de transações por página
                $quantidade_pg = 15;

                //Total de links antes e depois da página atual
                $dados['max_links'] = 2;

                //Calculando total de páginas
                $num_pag = ceil($totalT/$quantidade_pg);

                $dados['num_pag'] = $num_pag;

                //Calcular o inicio da visualização
                $inicio = ($quantidade_pg * $pg) - $quantidade_pg;

                //LIMPANDO A WHERE DO FILTRO, CASO ELA ESTEJA SETADO COLOCA O AND NA FRENTE
                $where = strlen($where) ? " AND $where " : '';

                //ORDENANDO TRANSAÇÕES PELA DATA DA MAIOR PARA O MENOR
                $dados['transacoes_cliente'] = Transacao::findBy(
                    "id_usuario = ".UsuarioSession::get('id')." {$where} ORDER BY data_trans DESC LIMIT {$inicio}, {$quantidade_pg} "
                );
    
                Transaction::close();

                if($pg > $num_pag and $num_pag > 0)
                {
                    echo 'Pagina não encontrada!';
                    echo "<br><a href='".HOME_URL."/transacoes'>Voltar</a>";
                    exit;
                }
    
            } catch (\Exception $e) {
                Transaction::rollback();
                echo $e->getMessage();
            }
        }

        $this->view([
            'templates/header',
            'transacoes/transacoes_listagem',
            'templates/footer'
        ],$dados);
    }   
}
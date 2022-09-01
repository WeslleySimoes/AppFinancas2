<?php 

namespace app\controller;

use app\model\Transaction;
use app\helpers\FlashMessage;
use app\model\entity\Transacao;
use app\session\Usuario as UsuarioSession;
use app\model\entity\{ReceitaFixa, DespesaFixa}; 

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
                header('location: '.HOME_URL.'/transacoes');
                exit;
            }

            try {
                
                Transaction::open('db');

                //Verifica se existe o get chamado 'pg'
                $pg = isset($_GET['pg']) ? intval($_GET['pg']) : 1;
                $dados['pg_atual'] = $pg;

                //Obtendo total de transações
                $totalT = Transacao::total(UsuarioSession::get('id'))['total'];

                //Quantidade de transações por página
                $quantidade_pg = 15;

                //Total de links antes e depois da página atual
                $dados['max_links'] = 2;

                //Calculando total de páginas
                $num_pag = ceil($totalT/$quantidade_pg);

                $dados['num_pag'] = $num_pag;

                //Calcular o inicio da visualização
                $inicio = ($quantidade_pg * $pg) - $quantidade_pg;

                //ORDENANDO TRANSAÇÕES PELA DATA DA MAIOR PARA O MENOR
                $dados['transacoes_cliente'] = Transacao::findBy(
                    "id_usuario = ".UsuarioSession::get('id')." ORDER BY data_trans DESC LIMIT {$inicio}, {$quantidade_pg} "
                );

                //dd($dados['transacoes_cliente']);
    
                Transaction::close();

                if($pg > $num_pag)
                {
                    header('location: '.HOME_URL.'/transacoes');
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
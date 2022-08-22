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
            
            try {
                
                Transaction::open('db');
    
                $dados['transacoes_cliente'] = Transacao::loadALl(UsuarioSession::get('id'));
    
                Transaction::close();
    
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
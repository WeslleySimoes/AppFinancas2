<?php 

namespace app\controller;

use app\model\Transaction;
use app\helpers\FlashMessage;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Planejamento as PlanejamentoModel;

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
        echo 'Cadastrar';
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
}

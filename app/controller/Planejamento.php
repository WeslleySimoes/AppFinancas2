<?php 

namespace app\controller;

use app\helpers\Validacao;
use app\model\Transaction;
use app\helpers\FlashMessage;
use app\model\entity\Categoria;
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
            if(empty(array_diff($_POST['categoria'],$arrCatDesp)))
            {
               dd($_POST);
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
}

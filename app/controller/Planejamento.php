<?php 

namespace app\controller;

use app\session\Usuario as UsuarioSession;
use app\model\Transaction;
use app\model\entity\Planejamento as PlanejamentoModel;

class Planejamento extends BaseController
{
    //Mostra o planejamento feito no mês
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome')
        ];

        try {
            Transaction::open('db');

            $dados['total_plan_mensal'] = PlanejamentoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND tipo = 'mensal' AND MONTH(data_fim) = MONTH(CURDATE())");

           // dd($dados['total_plan_mensal']);

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

    //CADASTRO DE PLANEJAMENTO MENSAL OU PERSONALIZADO
    public function cadastrar()
    {
        echo 'Cadastrar';
    }
}

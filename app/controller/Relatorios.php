<?php 

namespace app\controller;

use app\helpers\FlashMessage;
use app\session\Usuario as UsuarioSession;

class Relatorios extends BaseController
{
    //=========================================================================================
    //Mostra Relatórios no gráfico de pizza
    //=========================================================================================
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];
        


        $this->view([
            'templates/header',
            'relatorios/relatorio_pizza',
            'templates/footer'
        ],$dados);
    }
}
<?php 

namespace app\controller;

use app\helpers\FlashMessage;
use app\session\Usuario as UsuarioSession;


class Relatorios extends BaseController
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

        $this->view([
            'templates/header',
            'relatorios/gerar_relatorios',
            'templates/footer'
        ],$dados);
    }
}
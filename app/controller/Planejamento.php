<?php 

namespace app\controller;

use app\session\Usuario as UsuarioSession;

class Planejamento extends BaseController
{
    //Mostra o planejamento feito no mês
    public function index()
    {
        UsuarioSession::deslogado();

        $this->view([
            'templates/header',
            'planejamento/list_planejamentoMensal',
            'templates/footer'
        ],[
            'usuario_logado' => UsuarioSession::get('nome')
        ]);
    }
}

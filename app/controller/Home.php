<?php 

namespace app\controller;

use app\session\Usuario as UsuarioSession;

class Home extends BaseController
{
    public function index()
    {
        UsuarioSession::deslogado();

        //dd(session_cache_expire());

        $this->view([
            'templates/header',
            'dashboard',
            'templates/footer'
        ],[
            'usuario_logado' => UsuarioSession::get('nome')
        ]);
    }

    public function deslogar()
    {
        UsuarioSession::destroy();

        header('location: '.HOME_URL.'/');
        exit;
    }

}
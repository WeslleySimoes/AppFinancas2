<?php 

namespace app\session;

use app\session\Session;

class Usuario extends Session
{
    // Se o usuário estiver deslogado será redirecionado para a página de login
    public static function deslogado()
    {
        if(!isset($_SESSION['usuario']))
        {   
            //Manda para a página de login
            header('location:'.HOME_URL.'/');
            exit();
        }        
    }

    // Se o usuário estiver logado será redirecionado para a página de dashboard
    public static function logado()
    {
        if(isset($_SESSION['usuario']))
        {   
            //Manda para a página de login
            header('location:'.HOME_URL.'/dashboard');
            exit();
        } 
    }

    // Cria um novo parâmetro com valores dentro da sessão
    public static function set($values)
    {   
        self::setSession('usuario',$values);           
    }

    // Obtêm o valor de um parâmetro dentro da sessão
    public static function get($prop)
    {
        return self::getSession('usuario',$prop);
    }
}
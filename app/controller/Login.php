<?php 

namespace app\controller;

use app\model\entity\Usuario as UsuarioModel;
use app\session\Usuario as UsuarioSession;
use app\model\Transaction;

class Login extends BaseController
{
    public function index()
    {
        echo 'Página login';

        UsuarioSession::logado();

        try { 
             
            Transaction::open('db');
           // $usuario = UsuarioModel::checarUsuario('jose@pires.com','123');
            $usuario = UsuarioModel::checarUsuario('weslley@teste.com','123456');
            Transaction::close();
            
            if($usuario)
            {
                // Iniciando a sessão do usuário
                UsuarioSession::set([
                    'id' 	    => $usuario->idUsuario,
                    'nome' 	    => $usuario->nome,
                    'sobrenome' => $usuario->sobrenome
                ]);
                //header('location:'.HOME_URL.'/');
            }
            else {
                echo 'Usuário não existe!';
            }

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }  

        /*          
        if(isset($_GET['nome']))
        {
            $nome         = 'aaaa26526565';
            $sobrenome    = 'bsdsdsdsdbbbb';
            $email        = 'e@w.com';
            $telefoneFixo = '(11) 11dd11-1111';
            $telefoneCel  = '(11) 96111-1111';
    
            $v = new Validacao;
    
            $v->setCampo('Nome')
                ->min_caracteres($nome)
                ->max_caracteres($nome,5);
    
            $v->setCampo('Sobrenome')
                ->min_caracteres($sobrenome)
                ->max_caracteres($sobrenome,5);
    
            $v->setCampo('email')
                ->email($email)
                ->min_caracteres($email,5)
                ->max_caracteres($email);
    
            $v->setCampo('Telefone fixo')
                ->telefoneFixo($telefoneFixo);
            
            $v->setCampo('Telefone celular')
                ->celular($telefoneCel);
    
            if($v->validar())
            {
                $usuario = new UsuarioModel;
                $usuario->nome      = 'weslley';
                $usuario->sobrenome = 'simões';
                $usuario->email     = 'weslley@teste.com';
    
                if($usuario->store())
                {
                    FlashMessage::set('Cadastrado com sucesso!','success');
                }
                else{
                    FlashMessage::set('Erro ao tentar cadastrar!','error');
                }
            }
            else{
                dd($v->getMsgErros());
            }
        }
          */
        /*

        // Iniciando a sessão do usuário
        UsuarioSession::set([
            'id' 	=> 12,
            'nome' 	=> 'Weslley',
            'sobrenome' => 'Simões'
        ]);
    
        echo 'Página login';*/
    }

    public function cadastrar()
    {
        return;
    }
}
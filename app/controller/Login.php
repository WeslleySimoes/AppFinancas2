<?php 

namespace app\controller;

use app\helpers\FlashMessage;
use app\helpers\Validacao;
use app\model\entity\Usuario as UsuarioModel;
use app\session\Usuario as UsuarioSession;
use app\model\Transaction;
use app\utils\EnviarEmail;

class Login extends BaseController
{
    public function index()
    {
        UsuarioSession::logado();

        if(!empty($_POST))
        {
            $v = new Validacao;

            $v->setCampo('E-mail')
                ->max_caracteres($_POST['email'],100)
                ->email($_POST['email']);

            $v->setCampo('Senha')
                ->min_caracteres($_POST['senha'],8)
                ->max_caracteres($_POST['senha'],8);

            if($v->validar())
            {
                try { 
             
                    Transaction::open('db');

                    $usuario = UsuarioModel::checarUsuario(mb_strtolower($_POST['email']),$_POST['senha']);

                    Transaction::close();
                    
                    if($usuario)
                    {
                        // Iniciando a sessão do usuário
                        UsuarioSession::set([
                            'id' 	    => $usuario->idUsuario,
                            'nome' 	    => $usuario->nome,
                            'sobrenome' => $usuario->sobrenome
                        ]);

                        header("location: ".HOME_URL.'/dashboard');
                    }
                    else {
                        FlashMessage::set('E-mail e/ou senha incorreto!','error');
                    }
        
                } catch (\Exception $e) {
                    Transaction::rollback();
                    echo $e->getMessage();
                }  
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }

        $this->view([
            'login/login_usuario',
        ],[
           'msg' => FlashMessage::get()
        ]);
    }

    public function cadastrar()
    {
        UsuarioSession::logado();
        
        if(!empty($_POST))
        {
            $v = new Validacao;

            $v->setCampo('Nome')
                ->min_caracteres($_POST['nome'],3)
                ->max_caracteres($_POST['nome'],30);
            
            $v->setCampo('Sobrenome')
                ->min_caracteres($_POST['sobrenome'],3)
                ->max_caracteres($_POST['sobrenome'],30);

            $v->setCampo('Data Nascimento')
                ->validateDate($_POST['dataNasc']);

            $v->setCampo('Sexo')
                ->select($_POST['sexo'],['masculino','feminino']);
            
            $v->setCampo('E-mail')
                ->max_caracteres($_POST['email'],100)
                ->email($_POST['email']);

            $v->setCampo('Senha')
                ->min_caracteres($_POST['senha'],8)
                ->max_caracteres($_POST['senha'],8);

            $v->setCampo('Confirmar senha')
                ->min_caracteres($_POST['confirmarSenha'],8)
                ->max_caracteres($_POST['confirmarSenha'],8);

            $v->confirmaSenha($_POST['senha'],$_POST['confirmarSenha']);

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    $totalEmail = UsuarioModel::findBy("email = '".mb_strtolower($_POST['email'])."'");

                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro inesperado!','error');
                }

                if(!empty($totalEmail)){FlashMessage::set('O E-mail inserido já existe!','error');}

                $usuario = new UsuarioModel();
                $usuario->nome = ucfirst(mb_strtolower($_POST['nome']));
                $usuario->sobrenome = ucfirst(mb_strtolower($_POST['sobrenome']));
                $usuario->sexo = $_POST['sexo'];
                $usuario->data_nasc = $_POST['dataNasc'];
                $usuario->email = $_POST['email'];
                $usuario->senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);
                $usuario->status_usu = 'ABERTO';

                try {
                    Transaction::open('db');

                    $resultado = $usuario->store();

                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro inesperado!','error');
                }
                
                if($resultado)
                {
                    FlashMessage::set('Cadastro realizado com sucesso!','success');
                }
                else{
                    FlashMessage::set('Erro ao realizar cadastro!','error');
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }
        $this->view([
            'login/cadastro_usuario',
        ],[
           'msg' => FlashMessage::get()
        ]);
    }


    public function requisarAlteracaoSenha()
    {
        UsuarioSession::logado();

        if(!empty($_POST))
        {
            $v = new Validacao;
            $v->setCampo('E-mail')
                ->max_caracteres($_POST['email'],100)
                ->email($_POST['email']);

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    $emailUsuario = UsuarioModel::findBy("email = '".$_POST['email']."'");

                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro inesperado!','error');
                }

                if(!empty($emailUsuario))
                {
                    $tokenGerado = token(100);

                    try {
                        Transaction::open('db');
    
                        $usuario = $emailUsuario[0];
                        $usuario->token_usuario = $tokenGerado;

                        $resultado = $usuario->store();
                        
                        Transaction::close();

                    } catch (\Exception $e) {
                        Transaction::rollback();
                        FlashMessage::set('Ocorreu um erro inesperado!','error');
                    }

                    if($resultado)
                    {
                        $emailusuarioURL = urlencode($_POST['email']);

                        $enviarEmail = new EnviarEmail;
    
                        $enviarEmail->EmailDestinatario = $_POST['email'];
                        $enviarEmail->Subject = 'Alteracao de senha - Finanças pessoais';
                        $enviarEmail->body = "<h3><b>Clique no link abaixo para alterar sua senha: </br></br> <a href='".HOME_URL."/novaSenha?token={$tokenGerado}&email={$emailusuarioURL}'>Link para alterar senha</a></br></br>";
    
                        if($enviarEmail->enviar())
                        {
                            FlashMessage::set('Um e-mail foi enviado para realizar alteração da senha!','success');     
                        }
                        else{
                            FlashMessage::set('Este e-mail não foi encontrado!','error');
                        }

                    }else{
                        FlashMessage::set('Ocorreu um erro inesperado!','error');
                    }

                }
                else{
                    FlashMessage::set('Este e-mail não foi encontrado!','error');
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }

        $this->view([
            'login/requisitarAltSenha',
        ],[
           'msg' => FlashMessage::get()
        ]);
    }

    public function novaSenha()
    {   
        UsuarioSession::logado();

        unset($_GET['novaSenha']);

        if(!empty($_GET))
        {            
            $token = filter_var($_GET['token'],FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_GET,'email',FILTER_VALIDATE_EMAIL);

            if($token && $email && mb_strlen($token) == 100)
            {
                try {
                    Transaction::open('db');
    
                    $procurarUsuario = UsuarioModel::findBy("token_usuario = '{$token}' AND email = '{$email}'");
                    
                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::rollback();
                }

                if(empty($procurarUsuario)){header('location: '.HOME_URL.'/');exit;}

                if(!empty($_POST))
                {
                    $v = new Validacao;

                    $v->setCampo('Senha')
                        ->min_caracteres($_POST['senha'],8)
                        ->max_caracteres($_POST['senha'],8);

                    $v->setCampo('Confirmar senha')
                        ->min_caracteres($_POST['confirmarSenha'],8)
                        ->max_caracteres($_POST['confirmarSenha'],8);

                    $v->confirmaSenha($_POST['senha'],$_POST['confirmarSenha']);

                    if($v->validar())
                    {
                        try {
                            Transaction::open('db');
        
                            $altSenhaUsuario = $procurarUsuario[0];
                            $altSenhaUsuario->senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);
                            $altSenhaUsuario->token_usuario = 'NULL';

                            $resultado = $altSenhaUsuario->store();
                            
                            Transaction::close();
                        } catch (\Exception $e) {
                            Transaction::rollback();
                        }
                        
                        if($resultado)
                        {
                            header('location: '.HOME_URL.'/senhaAlteradaInfo');
                            exit;
                        }
                        else{
                            FlashMessage::set('Ocorreu um erro ao cadastrar nova senha','error',"novaSenha?token={$token}&email={$email}");
                        }
                    }
                    else{
                        FlashMessage::set($v->getMsgErros(),'error',"novaSenha?token={$token}&email={$email}");
                    }
                }
            }
            else{
                header('location: '.HOME_URL.'/');
                exit;
            }
        }
        else{
            header('location: '.HOME_URL.'/');
            exit;
        }


        $this->view([
            'login/nova_senha',
        ],[
           'msg' => FlashMessage::get()
        ]);
    }


    public function senhaAlteradaInfo()
    { 
        UsuarioSession::logado();

        $this->view([
            'login/senha_alterada',
        ]);
    }

}

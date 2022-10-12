<?php 

namespace app\helpers;

class FlashMessage
{
    private static $tipos = ['info','success','warning','error'];
    
    public static function set($message,$tipo = 'info',$local=null,$redirecionamento=true)
    {
        if(!in_array($tipo,self::$tipos))
        {
            throw new \Exception('Tipo definido não encontrado!');
        }
        
        $_SESSION['msg'] = [$tipo,$message];

        if($redirecionamento)
        {
            if(isset($local))
            {
                header('location:'.HOME_URL.'/'.$local);
            }
            else{
                header('location:'.HOME_URL.'/'.$_SERVER['QUERY_STRING']);
            }
            exit();
        }
    }

    public static function get()
    {
        $msg = null;

        if(isset($_SESSION['msg']))
        {
            $msg = $_SESSION['msg'];

            unset($_SESSION['msg']);
        }

        if(is_array($msg) and is_array($msg[1]))
        {
            $msgFinal = "<div class='alert {$msg[0]}-alert'><div><ul>";

            foreach($msg[1] as $mensagem)
            {
                $msgFinal.= "<li> {$mensagem} </li>";
            }

            $msgFinal.= "</ul></div><Button class='btn-alert-close'>X</Button></div>";

            return $msgFinal;
        }
        
        return isset($msg) ? "<div class='alert {$msg[0]}-alert'><div>{$msg[1]}</div><Button class='btn-alert-close'>X</Button></div>" : null;  
        

        
    }
}
<?php 

namespace app\controller;

use app\helpers\FlashMessage;
use app\helpers\Validacao;
use app\session\Usuario as UsuarioSession;

class Calculadoras extends BaseController
{
    public function listagem()
    {
        UsuarioSession::deslogado();
        
        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        if(isset($_GET['t']))
        {
            $v = new Validacao;
            $v->setCampo('tipo de calculadora')
                ->select($_GET['t'],['jurosCompostos','jurosSimples','converterMoeda']);

            if($v->validar())
            {
                switch($_GET['t'])
                {
                    case 'jurosCompostos':
                        $caminhoCalc = 'calculadoras/juros_compostos';
                        break;
                    case 'jurosSimples':
                        $caminhoCalc = 'calculadoras/juros_simples';
                        break;
                    case 'converterMoeda':
                        $caminhoCalc = 'calculadoras/conversorMoeda';
                        break;
                }
            }
            else{
                header('location: '.HOME_URL.'/calculadoras');
                exit;
            }
        }
        else{
            $caminhoCalc = 'calculadoras/listagem_calculadoras';
        }

        $this->view([
            'templates/header',
            $caminhoCalc,
            'templates/footer'
        ],$dados);
    }
}
<?php 

namespace app\controller;

use app\helpers\Validacao;
use app\model\Transaction;
use app\helpers\FlashMessage;
use app\model\entity\Usuario;
use app\model\entity\Transacao;
use app\model\entity\DashboardModel;
use app\model\entity\Conta as ContaModel;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Categoria as CategoriaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'nomepagina' => ['dashboard','Dashboard'],
            'link_caminho_pagina' => [
                ['dashboard','Dashboard']
            ]
        ];

        ###########################################################################################
        //Verifica se o usuário possui conta, caso contrário o redireciona para cadastro de contas
        ###########################################################################################
        try {
            Transaction::open('db');

            $totalContasUsuarioAtual = ContaModel::totalContas(UsuarioSession::get('id'));

            $totaCategoriaAtual = CategoriaModel::totalCategorias(UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }
        
        if($totaCategoriaAtual == 0)
        {
            CategoriaModel::addCategoriasIniciais();
        }

        if($totalContasUsuarioAtual  == 0)
        {
            header('location: '.HOME_URL.'/contas/cadastrar');
            exit;
        }
        //##################################################################################
        

        Transacao::inseriDespesasFixasMesAtual();
        Transacao::inseriReceitasFixasMesAtual();
        
        try {
            Transaction::open('db');

            $totalReceitas = DashboardModel::getTotalRD('receita');
            $totalDespesas = DashboardModel::getTotalRD('despesa');

            $contas = DashboardModel::saldoTotalContas();

            $ultimasTransacoes = DashboardModel::getLastTransacoes();

            $despesasCategorias = DashboardModel::drPorCategoria('despesa');
            $receitasCategorias = DashboardModel::drPorCategoria('receita');

            $depMensal = DashboardModel::despRespMensal('despesa');
            $recMensal = DashboardModel::despRespMensal('receita');

            $transacoesPendentes = Transacao::total(UsuarioSession::get('id')," status_trans = 'pendente' AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE()) ");
            
            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        $dados['totalTransPendentes'] = $transacoesPendentes['total'];
 
        //Gráfico Receitas x Despesas
        $dados['depMensal'] = $depMensal;
        $dados['recMensal'] = $recMensal;

        //DESPESAS
        $dados['arr_total_despesas'] = implode(',',array_column($despesasCategorias,'total'));
        $dados['arr_nomeCate_despesas'] = "'".implode("','",array_column($despesasCategorias,'nome'))."'";
        $dados['cores_despesas'] = "'".implode("','",array_column($despesasCategorias,'cor_cate'))."'";

        //RECEITAS
        $dados['arr_total_receitas'] = implode(',',array_column($receitasCategorias,'total'));
        $dados['arr_nomeCate_receitas'] = "'".implode("','",array_column($receitasCategorias,'nome'))."'";
        $dados['cores_receitas'] = "'".implode("','",array_column($receitasCategorias,'cor_cate'))."'";

        $dados['totalReceitas'] = $totalReceitas;
        $dados['totalDespesas'] = $totalDespesas;
        $dados['totalSaldoContas'] = $contas;
        $dados['ultimasTransacoes'] = $ultimasTransacoes;


        $this->view([
            'templates/header',
            'dashboard',
            'templates/footer'
        ],$dados);
    }

    public function deslogar()
    {
        
        UsuarioSession::destroy();

        header('location: '.HOME_URL.'/');
        exit;
    }

    //#########################################################
    // PÁGINA MEU CADASTRO
    //#########################################################
    public function configuracoes()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['configuracoes','Meu cadastro'],
            'link_caminho_pagina' => [
                ['configuracoes','Meu cadastro']
            ]
        ];


        try {
            Transaction::open('db');

            $usuarioLogado = Usuario::find(UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
            FlashMessage::set('Ocorreu um erro!','error');
        }   

        $dados['dadosUsuario'] = $usuarioLogado;

        if(!empty($_POST))
        {
            $v = new Validacao;

            $v->setCampo('Nome')
                ->min_caracteres($_POST['nome'],3)
                ->max_caracteres($_POST['nome'],60);

            $v->setCampo('Sobrenome')
                ->min_caracteres($_POST['sobrenome'],3)
                ->max_caracteres($_POST['sobrenome'],60);

            $v->setCampo('Data Nascimento')
                ->validateDate($_POST['dataNasc']);

            $v->setCampo('Sexo')
                ->select($_POST['sexo'],['masculino','feminino']);

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    $usuarioAlteracao = clone $usuarioLogado;
                    $usuarioAlteracao->nome = ucfirst(mb_strtolower($_POST['nome']));
                    $usuarioAlteracao->sobrenome = ucfirst(mb_strtolower($_POST['sobrenome']));
                    $usuarioAlteracao->data_nasc = $_POST['dataNasc'];
                    $usuarioAlteracao->sexo = $_POST['sexo'];
                    
                    $resultado = $usuarioAlteracao->store();


                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::rollback();
                    FlashMessage::set('Ocorreu um erro!','error');
                } 

                if($resultado)
                {
                    FlashMessage::set('Dados alterados com sucesso!','success');
                }else{
                    FlashMessage::set('Erro ao alterar dados!','error');
                }
            }else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }

        $this->view([
            'templates/header',
            'dashboard/meuCadastro',
            'templates/footer'
        ],$dados);
    }

    public function configuracoesAltEmail()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['configuracoes/alterarEmail','Alteração de E-mail'],
            'link_caminho_pagina' => [
                ['configuracoes','Configurações'],
                ['configuracoes/alterarEmail','Alteração de E-mail']
            ]
        ];

        if(!empty($_POST))
        {
            try {
                Transaction::open('db');

                $usuarioEmail = Usuario::find(UsuarioSession::get('id'));

                Transaction::close();
            } catch (\Exception $e) {
                Transaction::close();
            }

            $v = new Validacao;

            $v->setCampo('E-mail atual')
                ->max_caracteres($_POST['emailAtual'],100)
                ->email($_POST['emailAtual']);
            
            $v->setCampo('Novo E-mail')
                ->max_caracteres($_POST['novoEmail'],100)
                ->email($_POST['novoEmail']);

            if($v->validar())
            {   
                if($_POST['emailAtual'] != $usuarioEmail->email)
                {
                    FlashMessage::set('Email atual está incorreto!','error');
                }

                try {
                    Transaction::open('db');
    
                    $usuarioEmailNovo = $usuarioEmail;
                    $usuarioEmailNovo->email = $_POST['novoEmail'];

                    $resultado = $usuarioEmailNovo->store();
    
                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::close();
                }

                if($resultado)
                {
                    FlashMessage::set('Novo E-mail cadastrado com sucesso!','success');
                }else{
                    FlashMessage::set('Erro ao cadastrar a novo E-mail!','error');
                }
            } else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
            
        }

        $this->view([
            'templates/header',
            'dashboard/configuracao',
            'templates/footer'
        ],$dados);
    }

    public function configuracoesAltSenha()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['configuracoes/alterarSenha','Alteração de senha'],
            'link_caminho_pagina' => [
                ['configuracoes','Configurações'],
                ['configuracoes/alterarSenha','Alteração de senha']
            ]
        ];

        if(!empty($_POST))
        {
            try {
                Transaction::open('db');

                $usuarioSenha = Usuario::find(UsuarioSession::get('id'));

                Transaction::close();
            } catch (\Exception $e) {
                Transaction::close();
            }
            
            $v = new Validacao;

            $v->setCampo('Senha Atual')
            ->min_caracteres($_POST['senhaAtual'],8)
            ->max_caracteres($_POST['senhaAtual'],8);

            $v->setCampo('Senha')
                ->min_caracteres($_POST['senha'],8)
                ->max_caracteres($_POST['senha'],8);

            $v->setCampo('Confirmar nova senha:')
                ->min_caracteres($_POST['confirmarSenha'],8)
                ->max_caracteres($_POST['confirmarSenha'],8);

            $v->confirmaSenha($_POST['senha'],$_POST['confirmarSenha']);

            if($v->validar())
            {
                if(!password_verify($_POST['senhaAtual'],$usuarioSenha->senha))
                {
                    FlashMessage::set('Senha Atual está incorreta!','error');
                }

                try {
                    Transaction::open('db');
    
                    $novaSenha = $usuarioSenha;
                    $novaSenha->senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);

                    $resultado = $novaSenha->store();
    
                    Transaction::close();
                } catch (\Exception $e) {
                    Transaction::close();
                    FlashMessage::set('Ocorreu um erro inesperado!','error');
                }


                if($resultado)
                {
                    FlashMessage::set('Nova senha cadastrada com sucesso!','success');
                }else{
                    FlashMessage::set('Erro ao cadastrar a nova senha!','error');
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }



        $this->view([
            'templates/header',
            'dashboard/conf_alterarSenha',
            'templates/footer'
        ],$dados);
    }

}
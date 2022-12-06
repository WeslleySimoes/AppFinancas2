<?php 

namespace app\controller;

use app\session\Usuario as UsuarioSession;
use app\model\Transaction;
use app\model\entity\{Transacao as TransacaoModel,DespesaFixa as DespesaFixaModel};
use app\model\entity\{Categoria as CategoriaModel,Conta as ContaModel};
use app\helpers\{FlashMessage, Validacao, FormataMoeda};

class Despesa extends BaseController
{
    //=========================================================================================
    // Método responsável por cadastrar despesa ou despesa fixa
    //=========================================================================================
    public function cadastrar()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['despesa/cadastrar','Cadastro de despesa'],
            'link_caminho_pagina' => [
                ['transacoes','Transações'],
                ['despesa/cadastrar','Cadastro de despesa']
            ]
        ];

        //LISTAGEM DOS CAMPOS CATEGORIAS E CONTAS
        try {
            Transaction::open('db');

            $dados['listaCategorias'] = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and tipo = 'despesa' AND status_cate = 'ativo'");
            $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND status_conta = 'ativo'");

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }


        //LÓGICA DO FORMULÁRIO  
        if(!empty($_POST) && isset($_POST['valor']))
        {
            $v = new Validacao;

            $v->setCampo('Valor (R$)')
                ->moeda($_POST['valor']);

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);

            $v->setCampo('Data')
                ->data($_POST['dataDespesa'],'Y/m/d');
                
            if($v->validar())
            {
                try {
                    //SE SELECIONAR A DESPESA FIXA
                    if(isset($_POST['despesaFixa']) and $_POST['despesaFixa'] == '1')
                    {
                        //Definindo parâmetros da despesa fixa
                        $df                 = new DespesaFixaModel();
                        $df->valor          = FormataMoeda::moedaParaFloat($_POST['valor']);
                        $df->descricao      = $_POST['descricao'];
                        $df->id_categoria    = $_POST['categoriaDespesa'];
                        $df->data_inicio    = $_POST['dataDespesa'];
                        $df->data_fim       = '0000-00-00';
                        $df->status_desp    = 'aberto';
                        $df->id_usuario     = UsuarioSession::get('id');
                        $df->id_conta       = $_POST['ContaDespesa'];

                        Transaction::open('db');
                        $resultado = $df->store();
                        Transaction::close();

                        if($resultado)
                        {
                            FlashMessage::set('Despesa Fixa Cadastrada com sucesso!','success');
                        }
                        else{
                            FlashMessage::set('Erro ao cadastrar despesa!','error');
                        }
                    }

                    //SE SELECIONA A DESPESA PARCELADA
                    else if(isset($_POST['despesaPercelada']) and $_POST['despesaPercelada'] == '1'){

                        //Definindo parâmetros da despesa fixa
                        $df                 = new DespesaFixaModel();
                        $df->valor          = FormataMoeda::moedaParaFloat($_POST['valor']);
                        $df->descricao      = $_POST['descricao'];
                        $df->id_categoria   = $_POST['categoriaDespesa'];
                        $df->data_inicio    = $_POST['dataDespesa'];
                        $df->status_desp    = 'aberto';
                        $df->id_usuario     = UsuarioSession::get('id');
                        $df->id_conta       = $_POST['ContaDespesa'];

                        if(isset($_POST['totalParcelas']))
                        {
                            $v = new Validacao;
                            $v->setCampo('Total de Parcelas:')
                                ->campoNumInteiro($_POST['totalParcelas']);
                            
                            if($v->validar())
                            {
                                $df->parcelar((int) $_POST['totalParcelas']);

                                Transaction::open('db');
                                $resultado = $df->store();
                                Transaction::close();
        
                                if($resultado)
                                {
                                    FlashMessage::set('Despesa Fixa Cadastrada com sucesso!','success');
                                }
                                else{
                                    FlashMessage::set('Erro ao cadastrar despesa!','error');
                                }
                            }
                            else{
                                FlashMessage::set($v->getMsgErros(),'error');
                            }
                        }
                    }
                    
                    //DESPESA NORMAL (NÃO FIXA OU PARCELADA)
                    else{

                        $t = new TransacaoModel;

                        $t->data_trans          = $_POST['dataDespesa']." ".date('H:i:s');
                        $t->valor               = FormataMoeda::moedaParaFloat($_POST['valor']);
                        $t->descricao           = $_POST['descricao'];
                        $t->id_categoria        = $_POST['categoriaDespesa'];
                        $t->tipo                = 'despesa';
                        // $t->fixo                = 0;
                        /*
                        $t->id_despesaFixa       = 'NULL';
                        $t->id_receitaFixa       = 'NULL';
                        $t->id_transferencia     = 'NULL';*/
                        $t->id_usuario           = UsuarioSession::get('id');
                        $t->id_conta             = $_POST['ContaDespesa'];

                        //SE SELECIONAR DESPESA PAGA (PAGA NO MÊS ATUAL)
                        if(isset($_POST['despesaPaga']) and $_POST['despesaPaga'] == '1')
                        {
                            $t->status_trans = 'fechado';
                        }
                        else{
                            $t->status_trans = 'pendente';
                        }

                        Transaction::open('db');
                        $resultado = $t->store();
                        Transaction::close();

                        if($resultado)
                        {
                            FlashMessage::set('Despesa Cadastrada com sucesso!','success');
                        }
                        else{
                            FlashMessage::set('Erro ao cadastrar despesa!','error');
                        }
                    }
                } catch (\Exception $e) {
                    Transaction::rollback();

                    dd($e->getMessage());
                    exit;
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
            }
        }

        $this->view([
            'templates/header',
            'despesas/cadastro_despesa',
            'templates/footer'
        ], $dados);
    }   

    //=========================================================================================
    // MÉTODO RESPONSÁVEL POR EDITAR UMA DESPESA
    //=========================================================================================
    public function editar()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['despesa/editar?id='.$_GET['id'],'Editar despesa'],
            'link_caminho_pagina' => [
                ['transacoes','Transações'],
                ['despesa/editar?id='.$_GET['id'],'Editar despesa']
            ]
        ];

        // VALIDANDO ID INSERIDO NA URL 
        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {
            //LISTAGEM DOS CAMPOS CATEGORIAS E CONTAS E RECEITA
            try {
                Transaction::open('db');
                
                $dados['listaCategorias'] = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and tipo = 'despesa' AND status_cate = 'ativo'");
                
                $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND status_conta = 'ativo'");
                
                $dados['dadosDespesa'] = TransacaoModel::findBy("idTransacao = ".$_GET['id']." and id_usuario =".UsuarioSession::get('id'));   
                
                // Caso o id não existe e/ou não seja do usuário da sessão atual, será redirecionado para a pagina de não encontrado
                $r = TransacaoModel::findBy('idTransacao = '.$_GET['id'].' and id_usuario = '.UsuarioSession::get('id'));
                Transaction::close();

                if(!$r)
                {
                    echo 'Página não encontrada';
                    exit;
                }

            } catch (\Exception $e) {
                Transaction::rollback();
            } 

        }
        else{
            echo 'Página não encontrada!';
            exit;
        }

        if(!empty($_POST) and isset($_POST['valor']))
        {
            $v = new Validacao;

            $v->setCampo('Valor (R$)')
                ->moeda($_POST['valor']);

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);

            $v->setCampo('Data')
                ->data($_POST['dataDespesa'],'Y/m/d');
                
            
            if($v->validar())
            {
                try {

                    Transaction::open('db');

                    if(!CategoriaModel::find(intval($_POST['categoriaDespesa'])))
                    {
                        FlashMessage::set('Categoria escolhida não existe!','error',"despesa/editar?id={$_GET['id']}");
                    }

                    if(!ContaModel::find(intval($_POST['ContaDespesa'])))
                    {
                        FlashMessage::set('Conta escolhida não existe!','error',"despesa/editar?id={$_GET['id']}");
                    }

                   

                    $ts = new TransacaoModel($_GET['id']);

                    $ts->data_trans = $_POST['dataDespesa']." ".date('H:i:s');
                    $ts->valor = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $ts->descricao = $_POST['descricao'];
                    $ts->id_categoria = $_POST['categoriaDespesa'];
                    $ts->id_conta = $_POST['ContaDespesa'];

                    if(intval($_POST['despesaPaga']) == 1)
                    {
                        $ts->status_trans = 'fechado';
                    }
                    else{
                        $ts->status_trans = 'pendente';
                    }

                    $r = $ts->store();

                    Transaction::close();

                    if($r)
                    {
                        FlashMessage::set('Despesa alterada com sucesso!','success',"despesa/editar?id={$_GET['id']}");    
                    }
                    elseif($r === 0)
                    {
                        FlashMessage::set('Nenhum dado foi alterado!','warning',"despesa/editar?id={$_GET['id']}");    
                    }
                    elseif($r === false or $r === "") {

                        FlashMessage::set('Erro ao alterar despesa!','error',"despesa/editar?id={$_GET['id']}");
                    }

                } catch (\Exception $e) {
                    Transaction::rollback();
                    echo $e->getMessage();
                }

            } else{
                FlashMessage::set($v->getMsgErros(),'error',"despesa/editar?id={$_GET['id']}");
            }
        }
        

        $this->view([
            'templates/header',
            'despesas/editar_despesa',
            'templates/footer'
        ], $dados);
    }

    //=========================================================================================
    // MÉTODO REPONSÁVEL POR REMOVER DESPESA NORMAL
    //=========================================================================================
    public function remover()
    {
        UsuarioSession::deslogado();

        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {
    
            try {
                Transaction::open('db');

                $ts = new TransacaoModel($_GET['id']);
                $r = $ts->delete();
                
                Transaction::close();

                if($r)
                {
                    FlashMessage::set('Despesa Removida com sucesso!','success',"transacoes");
                }
                else{
                    FlashMessage::set('Erro ao remover despesa!','error',"transacoes");
                }
        
            } catch (\Exception $e) {
                Transaction::rollback();
            }
        }
        else{
            echo 'Página não encontrada!';
            exit;
        }
    }

    //=========================================================================================
    // MÉTODO REPONSÁVEL POR EFETIVAR DESPESA NORMAL
    //=========================================================================================
    public function efetivar()
    {
        UsuarioSession::deslogado();

        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {
    
            try {
                Transaction::open('db');

                $ts = new TransacaoModel($_GET['id']);
                $ts->status_trans = 'fechado';
                
                $r = $ts->store();
                
                Transaction::close();

                if($r)
                {
                    FlashMessage::set('Despesa efetivada com sucesso!','success',"transacoes");
                }
                else{
                    FlashMessage::set('Erro ao efetivar despesa!','error',"transacoes");
                }
        
            } catch (\Exception $e) {
                Transaction::rollback();
            }
        }
        else{
            echo 'Página não encontrada!';
            exit;
        }
    }

    //=========================================================================================
    // MÉTODO REPONSÁVEL POR EDITAR DESPESA FIXA/PARCELADA
    //=========================================================================================
    public function editarFP()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['despesa/editarFP?id='.$_GET['id'].'&t='.$_GET['t'],'Editar despesa'],
            'link_caminho_pagina' => [
                ['transacoes','Transações'],
                ['despesa/editarFP?id='.$_GET['id'].'&t='.$_GET['t'],'Editar despesa']
            ]
        ];

        switch($_GET['t'])
        {
            case 'fixa':
                $dados['titulo'] = 'Editar depesa fixa';
                break;
            case 'parcelada':
                $dados['titulo'] = 'Editar despesa parcelada';
                break;
        }

        // VALIDANDO ID INSERIDO NA URL 
        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {

            //LISTAGEM DOS CAMPOS CATEGORIAS E CONTAS E RECEITA
            try {
                Transaction::open('db');

                $dados['listaCategorias'] = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and tipo = 'despesa'  AND status_cate = 'ativo'");
                
                $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND status_conta = 'ativo'");

                $r = DespesaFixaModel::findBy('idDesp = '.$_GET['id'].' and id_usuario = '.UsuarioSession::get('id'));
                
                $dados['dadosEdicao'] = $r;

                Transaction::close();

                if(!$r)
                {
                    echo 'Página não encontrada';
                    exit();
                }

            } catch (\Exception $e) {
                Transaction::rollback();
            } 
        }
        else{
            echo 'Página não encontrada!';
            exit;
        }

        //EDITAR RECEITA FIXA
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            //EDITAR RECEITA FIXA

            $v = new Validacao;

            $v->setCampo('Valor (R$)')
                ->moeda($_POST['valor']);

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    if(!CategoriaModel::find($_POST['categoriaDespesa']))
                    {
                        FlashMessage::set('Categoria escolhida não existe!','error',"despesa/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }

                    if(!ContaModel::find($_POST['ContaDespesa']))
                    {
                        FlashMessage::set('Conta escolhida não existe!','error',"despesa/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }
    
                    $rf = new DespesaFixaModel($_GET['id']);
                    $rf->valor          = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $rf->descricao      = $_POST['descricao'];
                    $rf->id_categoria   = (int) $_POST['categoriaDespesa'];
                    $rf->id_conta       = (int) $_POST['ContaDespesa'];
    
                    if(isset($_POST['despesaRecebida']))
                    {
                        $rf->status_desp = 'fechado';
                    }
                    else{
                        $rf->status_desp = 'aberto';
                    }
    
                    $resultado = $rf->store();
    
                    Transaction::close();
    
                    if($resultado)
                    {
                        FlashMessage::set('Despesa alterada com sucesso!','success',"despesa/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }
                    else{
                        FlashMessage::set('Erro ao alterar despesa!','error',"despesa/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }
    
                } catch (\Exception $e) {
                    Transaction::rollback();
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error',"despesa/editarFP?id={$_GET['id']}&t={$_GET['t']}");
            }
        }

        $this->view([
            'templates/header',
            'despesaFixa/editar_despesaFixa',
            'templates/footer'
        ],$dados);
    }

    //=========================================================================================
    // MÉTODO REPONSÁVEL POR REMOVER DESPESA FIXA/PARCELADA
    //=========================================================================================
    public function removerFP()
    {
        UsuarioSession::deslogado();

        if(isset($_GET['idR'])and is_numeric($_GET['idR']) and intval($_GET['idR']) > 0 and isset($_GET['t']))
        {
            if($_GET['t'] == 'futuras' || $_GET['t'] == 'todas' )
            {
                try {
                    Transaction::open('db');
    
                    //OBTENDO A DESPESA FIXA/PARCELADA
                    $rf = DespesaFixaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and idDesp = {$_GET['idR']}");
                    
                    switch($_GET['t'])
                    {
                        case 'futuras':
                            $resultado = $rf[0]->removeFuturas();
                            break;
                        case 'todas':
                            $resultado = $rf[0]->removeTodas();
                            break;
                    }

                    Transaction::close();  

                    if($resultado)
                    {
                        FlashMessage::set('Despesa Removida com sucesso!','success',"transacoes?s=despesasFixas");
                    }
                    else{
                        FlashMessage::set('Erro ao tentar remover despesa!','error',"transacoes?s=despesasFixas");
                    }
                    
                } catch (\Exception $e) {
                    Transaction::rollback();
                }
            }
            else {
                echo 'Página não encontrada';
                exit;
            }
        }
    }   

}
<?php 

namespace app\controller;

use app\helpers\Validacao;
use app\model\Transaction;
use app\helpers\FlashMessage;
use app\model\entity\Conta as ContaModel;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Categoria as CategoriaModel;
use app\model\entity\ReceitaFixa as ReceitaFixaModel;
use app\model\entity\Transacao as TransacaoModel;
use app\helpers\FormataMoeda;


class Receita extends BaseController
{
    //=========================================================================================
    // Método responsável por cadastrar receita ou receita fixa
    //=========================================================================================
    public function cadastrar()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['receita/cadastrar','Cadastro de receita'],
            'link_caminho_pagina' => [
                ['transacoes','Transações'],
                ['receita/cadastrar','Cadastro de receita']
            ]
        ];

        //LISTAGEM DOS CAMPOS CATEGORIAS E CONTAS
        try {
            Transaction::open('db');

            $dados['listaCategorias'] = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and tipo = 'receita'  AND status_cate = 'ativo'");
            $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND status_conta = 'ativo'");

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }


        //LÓGICA DO FORMULÁRIO  
        if(!empty($_POST) && isset($_POST['valor']))
        {

           // dd($_POST);
            $v = new Validacao;

            $v->setCampo('Valor (R$)')
                ->moeda($_POST['valor']);

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);

            $v->setCampo('Data')
                ->data($_POST['dataReceita'],'Y/m/d');
                
            if($v->validar())
            {
                try {
                    //SE SELECIONAR A DESPESA FIXA
                    if(isset($_POST['receitaFixa']) and $_POST['receitaFixa'] == '1')
                    {

                      //  dd('teste');
                        //Definindo parâmetros da despesa fixa
                        $df                 = new ReceitaFixaModel();
                        $df->valor          = FormataMoeda::moedaParaFloat($_POST['valor']);
                        $df->descricao      = $_POST['descricao'];
                        $df->id_categoria    = $_POST['categoriaReceita'];
                        $df->data_inicio    = $_POST['dataReceita'];
                        $df->data_fim       = 'NULL';
                        $df->status_rec     = 'aberto';
                        $df->id_usuario     = UsuarioSession::get('id');
                        $df->id_conta       = $_POST['ContaReceita'];


                        Transaction::open('db');
                        $resultado = $df->store();
                        Transaction::close();

                        if($resultado)
                        {
                            FlashMessage::set('Receita Fixa Cadastrada com sucesso!','success');
                        }
                        else{
                            FlashMessage::set('Erro ao cadastrar receita!','error');
                        }
                    }

                    //SE SELECIONA A DESPESA PARCELADA
                    else if(isset($_POST['receitaPercelada']) and $_POST['receitaPercelada'] == '1' and isset($_POST['totalParcelas'])){

                        //Definindo parâmetros da despesa fixa
                        $df                 = new ReceitaFixaModel();
                        $df->valor          = FormataMoeda::moedaParaFloat($_POST['valor']);
                        $df->descricao      = $_POST['descricao'];
                        $df->id_categoria   = $_POST['categoriaReceita'];
                        $df->data_inicio    = $_POST['dataReceita'];
                        $df->status_rec     = 'aberto';
                        $df->id_usuario     = UsuarioSession::get('id');
                        $df->id_conta       = $_POST['ContaReceita'];

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
                                    FlashMessage::set('Receita parcelada Cadastrada com sucesso!','success');
                                }
                                else{
                                    FlashMessage::set('Erro ao cadastrar receita!','error');
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
                        $t->data_trans          = $_POST['dataReceita']." ".date('H:i:s');
                        $t->valor               = FormataMoeda::moedaParaFloat($_POST['valor']);
                        $t->descricao           = $_POST['descricao'];
                        $t->id_categoria        = $_POST['categoriaReceita'];
                        $t->tipo                = 'receita';
                        $t->fixo                = 0;
                        /*
                        $t->id_despesaFixa       = 'NULL';
                        $t->id_receitaFixa       = 'NULL';
                        $t->id_transferencia     = 'NULL';*/
                        $t->id_usuario           = UsuarioSession::get('id');
                        $t->id_conta             = $_POST['ContaReceita'];

                        //SE SELECIONAR DESPESA PAGA (PAGA NO MÊS ATUAL)
                        if(isset($_POST['receitaRecebida']) and $_POST['receitaRecebida'] == '1')
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
                            FlashMessage::set('Receita Cadastrada com sucesso!','success');
                        }
                        else{
                            FlashMessage::set('Erro ao cadastrar receita!','error');
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
            'receita/cadastro_receita',
            'templates/footer'
        ],$dados);
    }   

    //=========================================================================================
    // Método responsável por editar receita comum.
    //=========================================================================================
    public function editar()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['receita/editar?id='.$_GET['id'],'Editar receita'],
            'link_caminho_pagina' => [
                ['transacoes','Transações'],
                ['receita/editar?id='.$_GET['id'],'Editar receita']
            ]
        ];

        // VALIDANDO ID INSERIDO NA URL 
        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {

            //LISTAGEM DOS CAMPOS CATEGORIAS E CONTAS E RECEITA
            try {
                Transaction::open('db');

                $dados['listaCategorias'] = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and tipo = 'receita'  AND status_cate = 'ativo'");
                
                $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND status_conta = 'ativo'");
                
                $dados['dadosReceita'] = TransacaoModel::findBy("idTransacao = ".$_GET['id']." and id_usuario =".UsuarioSession::get('id'));   
                
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
                ->data($_POST['dataReceita'],'Y/m/d');
                
            if($v->validar())
            {

                try {
                    Transaction::open('db');

                    if(!CategoriaModel::find($_POST['categoriaReceita']))
                    {
                        FlashMessage::set('Categoria escolhida não existe!','error',"despesa/editar?id={$_GET['id']}");
                    }

                    if(!ContaModel::find($_POST['ContaReceita']))
                    {
                        FlashMessage::set('Conta escolhida não existe!','error',"despesa/editar?id={$_GET['id']}");
                    }

                    $ts = new TransacaoModel($_GET['id']);

                    $ts->data_trans = $_POST['dataReceita']." ".date('H:i:s');
                    $ts->valor = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $ts->descricao = $_POST['descricao'];
                    $ts->id_categoria = $_POST['categoriaReceita'];
                    $ts->id_conta = $_POST['ContaReceita'];

                    if(intval($_POST['receitaRecebida']) == 1)
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
                        FlashMessage::set('Receita alterada com sucesso!','success',"receita/editar?id={$_GET['id']}");    
                    }
                    else{
                        FlashMessage::set('Erro ao alterada receita!','error',"receita/editar?id={$_GET['id']}");
                    }

                } catch (\Exception $e) {
                    Transaction::rollback();
                    echo $e->getMessage();
                }

            } else{
                FlashMessage::set($v->getMsgErros(),'error',"receita/editar?id={$_GET['id']}");
            }
        }

        $this->view([
            'templates/header',
            'receita/editar_receita',
            'templates/footer'
        ],$dados);
    }

    //=========================================================================================
    // Método responsável por remover receita comum
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
                    FlashMessage::set('Receita Removida com sucesso!','success',"transacoes");
                }
                else{
                    FlashMessage::set('Erro ao remover receita!','error',"transacoes");
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
    // MÉTODO REPONSÁVEL POR EFETIVAR RECEITA NORMAL
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
                    FlashMessage::set('Receita efetivada com sucesso!','success',"transacoes");
                }
                else{
                    FlashMessage::set('Erro ao efetivar receita!','error',"transacoes");
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
    // MÉTODO REPONSÁVEL POR EDITAR RECEITA FIXA/PARCELADA
    //=========================================================================================
    public function editarFP()
    {
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get(),
            'nomepagina' => ['receita/editarFP?id='.$_GET['id'].'&t='.$_GET['t'],'Editar receita'],
            'link_caminho_pagina' => [
                ['transacoes','Transações'],
                ['receita/editarFP?id='.$_GET['id'].'&t='.$_GET['t'],'Editar receita']
            ]
        ];

        switch($_GET['t'])
        {
            case 'fixa':
                $dados['titulo'] = 'Editar receita fixa';
                break;
            case 'parcelada':
                $dados['titulo'] = 'Editar receita parcelada';
                break;
        }

        // VALIDANDO ID INSERIDO NA URL 
        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {

            //LISTAGEM DOS CAMPOS CATEGORIAS E CONTAS E RECEITA
            try {
                Transaction::open('db');

                $dados['listaCategorias'] = CategoriaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and tipo = 'receita'  AND status_cate = 'ativo'");
                
                $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id')." AND status_conta = 'ativo'");

                $r = ReceitaFixaModel::findBy('idRec = '.$_GET['id'].' and id_usuario = '.UsuarioSession::get('id'));
                
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

            $v->setCampo('Data')
                ->data($_POST['dataReceita'],'Y/m/d');

            if($v->validar())
            {
                try {
                    Transaction::open('db');

                    if(!CategoriaModel::find($_POST['categoriaReceita']))
                    {
                        FlashMessage::set('Categoria escolhida não existe!','error',"receita/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }

                    if(!ContaModel::find($_POST['ContaReceita']))
                    {
                        FlashMessage::set('Conta escolhida não existe!','error',"receita/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }
    
                    $rf = new ReceitaFixaModel($_GET['id']);
                    $rf->valor          = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $rf->descricao      = $_POST['descricao'];
                    $rf->id_categoria   = (int) $_POST['categoriaReceita'];
                    $rf->id_conta       = (int) $_POST['ContaReceita'];
    
                    if($_POST['receitaRecebida'])
                    {
                        $rf->status_rec = 'fechado';
                    }
                    else{
                        $rf->status_rec = 'aberto';
                    }
    
                    $resultado = $rf->store();
    
                    Transaction::close();
    
                    if($resultado)
                    {
                        FlashMessage::set('Receita alterada com sucesso!','success',"receita/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }
                    else{
                        FlashMessage::set('Erro ao alterar receita!','error',"receita/editarFP?id={$_GET['id']}&t={$_GET['t']}");
                    }
    
                } catch (\Exception $e) {
                    Transaction::rollback();
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error',"receita/editarFP?id={$_GET['id']}&t={$_GET['t']}");
            }
        }

        $this->view([
            'templates/header',
            'receitaFixa/editar_receitaFixa',
            'templates/footer'
        ],$dados);
    }


    //=========================================================================================
    // MÉTODO REPONSÁVEL POR REMOVER RECEITA FIXA/PARCELADA
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
    
                    //OBTENDO A RECEITA FIXA/PARCELADA
                    $rf = ReceitaFixaModel::findBy("id_usuario = ".UsuarioSession::get('id')." and idRec = {$_GET['idR']}");
                    
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
                        FlashMessage::set('Receita Removida com sucesso!','success',"transacoes?s=receitasFixas");
                    }
                    else{
                        FlashMessage::set('Erro ao tentar remover receita!','error',"transacoes?s=receitasFixas");
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
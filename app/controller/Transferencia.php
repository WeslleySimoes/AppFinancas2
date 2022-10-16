<?php 

namespace app\controller;
use app\model\Transaction;
use app\helpers\FlashMessage;
use app\helpers\Validacao;
use app\session\Usuario as UsuarioSession;
use app\model\entity\Conta as ContaModel;
use app\model\entity\Transacao as TransacaoModel;
use app\model\entity\Transferencia as TransferenciaModel;
use app\helpers\FormataMoeda;

class Transferencia extends BaseController
{
    //=========================================================================================
    // Método responsável por cadastrar uma transferência
    //=========================================================================================
    public function cadastrar()
    {
        //Verifica se o usuário está deslogado
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];

        //LISTAGEM DOS CAMPOS CONTAS ORIGEM E DESTINO
        try {
            Transaction::open('db');

            $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        //CADASTRO DE TRANSFERÊNCIA ENTRE CONTAS DO MESMO USUÁRIO
        if(!empty($_POST))
        {
            $v = new Validacao;
            
            $v->setCampo('Valor (R$)')
                ->moeda($_POST['valor']);

            $v->setCampo('Descrição')
                ->min_caracteres($_POST['descricao'])
                ->max_caracteres($_POST['descricao']);
            
            $v->setCampo('Data')
                ->data($_POST['dataTransferencia'],'Y/m/d');
            
            $v->setCampo('Conta origem')
                ->campoNumInteiro($_POST['contaOrigem']);
            
            $v->setCampo('Conta destino')
                ->campoNumInteiro($_POST['contaDestino']);

            $v->setCampo('Conta de origem e Conta de destino')
                ->numerosIguais($_POST['contaOrigem'],$_POST['contaDestino']);
            
            if($v->validar())
            {
                try {       

                    $dataHora = " ".date('H:i:s');

                    $transf = new TransferenciaModel();
                    $transf->data_transf        = $_POST['dataTransferencia'].$dataHora;
                    $transf->valor              = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $transf->descricao          = $_POST['descricao'];
                    $transf->id_conta_origem    = $_POST['contaOrigem'];
                    $transf->id_conta_destino   = $_POST['contaDestino'];
                    $transf->id_usuario         = UsuarioSession::get('id');
                    $transf->status_transf      = 'aberto';

                    $tm = new TransacaoModel();
                    $tm->data_trans         = $_POST['dataTransferencia'].$dataHora ;
                    $tm->valor              = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $tm->descricao          = $_POST['descricao'];
                    $tm->tipo               = 'transferencia';
                    $tm->status_trans       = 'pendente';
                    $tm->id_conta           = $_POST['contaOrigem'];
                    $tm->id_usuario         = UsuarioSession::get('id');
                    $tm->transferencia      = $transf;
                    
                    if(isset($_POST['transCheck']) and $_POST['transCheck'] == '1')
                    {   
                        $transf->status_transf      = 'fechado';
                        $tm->status_trans           = 'fechado';
                    }

                    Transaction::open('db');

                    if(!ContaModel::findBy("idConta =".$_POST['contaOrigem']." and id_usuario = ".UsuarioSession::get('id')))
                    {
                        FlashMessage::set('Conta de origem escolhida não existe!','error');
                    }

                    if(!ContaModel::findBy("idConta =".$_POST['contaDestino']." and id_usuario = ".UsuarioSession::get('id')))
                    {
                        FlashMessage::set('Conta de origem escolhida não existe!','error');
                    }

                    $r = $tm->store();
                   
                    Transaction::close();

                    if($r)
                    {
                        FlashMessage::set('Transferência cadastrada com sucesso!','success');
                    }
                    else{
                        // dd($r);
                        // exit;
                        FlashMessage::set('Erro ao cadastrar transferência!','error');
                    }
                } catch (\Exception $e) {
                    Transaction::rollback();

                    dd($e->getMessage());
                    exit;
                }
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error');
                exit;
            }
        }
        
        $this->view([
            'templates/header',
            'transferencia/cadastro_transferencia',
            'templates/footer'
        ],$dados);
    }

    //=========================================================================================
    // Método responsável por editar uma transferência
    //=========================================================================================
    public function editar()
    {
        //Verifica se o usuário está deslogado
        UsuarioSession::deslogado();

        $dados = [
            'usuario_logado' => UsuarioSession::get('nome'),
            'msg' => FlashMessage::get()
        ];
       

        // VALIDANDO ID INSERIDO NA URL 
        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {
            //LISTAGEM DOS CAMPOS CONTAS ORIGEM E DESTINO
            try {
                Transaction::open('db');

                $dados['listaContas'] = ContaModel::findBy('id_usuario = '.UsuarioSession::get('id'));

                $dados['dadosTransferencia'] = TransacaoModel::findBy('idTransacao = '.$_GET['id'].' and id_usuario = '.UsuarioSession::get('id'));

                // Caso o id não existe e não seja do usuário da sessão atual, será redirecionado para a pagina de não encontrado
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
                ->data($_POST['dataTransferencia'],'Y/m/d');
            
            $v->setCampo('Conta origem')
                ->campoNumInteiro($_POST['contaOrigem']);
            
            $v->setCampo('Conta destino')
                ->campoNumInteiro($_POST['contaDestino']);

            $v->setCampo('Conta de origem e Conta de destino')
                ->numerosIguais($_POST['contaOrigem'],$_POST['contaDestino']);
                
            if($v->validar())
            {
                try {      
                    
                    // if(!ContaModel::findBy("idConta =".$_POST['contaOrigem']." and id_usuario = ".UsuarioSession::get('id')))
                    // {
                    //     FlashMessage::set('Conta de origem escolhida não existe!','error',"transferencia/editar?id={$_GET['id']}");
                    // }
                    
                    // if(!ContaModel::findBy("idConta =".$_POST['contaDestino']." and id_usuario = ".UsuarioSession::get('id')))
                    // {
                    //     FlashMessage::set('Conta de origem escolhida não existe!','error',"transferencia/editar?id={$_GET['id']}");
                    // }
                    
                    Transaction::open('db'); 
                    $tm = new TransacaoModel($_GET['id']);

                    $dataHora = " ".date('H:i:s');

                    $tm->data_trans         = $_POST['dataTransferencia'].$dataHora;
                    $tm->valor              = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $tm->descricao          = $_POST['descricao'];
                    $tm->id_conta           = $_POST['contaOrigem'];
                    $tm->status_trans       = 'pendente';

                    $transf = $tm->getTransferencia();

                    $transf->data_transf        = $_POST['dataTransferencia'].$dataHora;
                    $transf->valor              = FormataMoeda::moedaParaFloat($_POST['valor']);
                    $transf->descricao          = $_POST['descricao'];
                    $transf->id_conta_origem    = intval($_POST['contaOrigem']);
                    $transf->id_conta_destino   = intval($_POST['contaDestino']);
                    $transf->status_transf      = 'aberto';

                    $tm->transferencia          = $transf;

                    if(isset($_POST['transCheck']) and $_POST['transCheck'] == '1')
                    {   
                        $transf->status_transf      = 'fechado';
                        $tm->status_trans           = 'fechado';
                    }
                    
                    
                    $r = $tm->store();
                    
                    Transaction::close();
                    
                    if($r)
                    {
                        FlashMessage::set('Transferência alterada com sucesso!','success',"transferencia/editar?id={$_GET['id']}");
                    }
                    else{
                        FlashMessage::set('Erro ao alterar transferência!','error',"transferencia/editar?id={$_GET['id']}");
                    }
                    
                } catch (\Exception $e) {
                    Transaction::rollback();

                    dd($e->getMessage());
                    exit;
                }                
            }
            else{
                FlashMessage::set($v->getMsgErros(),'error',"transferencia/editar?id={$_GET['id']}");
            }
        }

        $this->view([
            'templates/header',
            'transferencia/editar_transferencia',
            'templates/footer'
        ],$dados);
    }


    //=========================================================================================
    // Método responsável por remover uma transferência
    //=========================================================================================
    public function remover()
    {
        UsuarioSession::deslogado();

        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {
            try {
                Transaction::open('db');

                $ts = new TransacaoModel($_GET['id']);
                $transf = $ts->getTransferencia();

                $r = $ts->delete();
                $rs = $transf->delete();
                
                Transaction::close();

                if($r and $rs)
                {
                    FlashMessage::set('Transferência Removida com sucesso!','success',"transacoes");
                }
                else{
                    FlashMessage::set('Erro ao removertransferência!','error',"transacoes");
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
    // MÉTODO REPONSÁVEL POR EFETIVAR TRANSFERÊNCIA
    //=========================================================================================
    public function efetivar()
    {
        UsuarioSession::deslogado();

        if(isset($_GET['id']) and is_numeric($_GET['id']) and intval($_GET['id']) > 0)
        {
    
            try {
                Transaction::open('db');

                $ts = new TransacaoModel($_GET['id']);
                $transf = $ts->getTransferencia();

                $transf->status_transf = 'fechado';
                $ts->status_trans = 'fechado';

                $ts->transferencia = $transf;

                $r = $ts->store();
                
                Transaction::close();

                if($r)
                {
                    FlashMessage::set('Transferência efetivada com sucesso!','success',"transacoes");
                }
                else{
                    FlashMessage::set('Erro ao efetivar transferência!','error',"transacoes");
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

}
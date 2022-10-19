<?php 

namespace app\controller;

use app\model\entity\DespesaFixa as DespesaFixaModel;
use app\model\entity\Transacao as TransacaoModel;
use app\model\Transaction;
use app\session\Usuario as UsuarioSession;

class Teste extends BaseController
{

    public function index()
    {

        try {
            Transaction::open('db');

            $despesasFixas = DespesaFixaModel::findBy('id_usuario = '.UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        foreach ($despesasFixas as $df)
        {
            //DESPESA FIXA
            if($df->data_fim == '0000-00-00')
            {
                $timeZone = new \DateTimeZone('UTC');

                $dataDF = explode('-',$df->data_inicio);
                $dataDF = date('Y-m').'-'.$dataDF[2];

                $data1 = \DateTime::createFromFormat ('Y-m-d',$df->data_inicio, $timeZone);
                $data2 = \DateTime::createFromFormat ('Y-m-d', $dataDF, $timeZone);

                if ($data2 >= $data1) {


                    // echo 'Fixa<br>';


                    // foreach (geraListaDatas($data1->format('Y-m-d'),$data2->format('Y-m-d')) as $dataGerada) {
                        
                    //     echo $dataGerada.'<br>';
                    // }
   
                    try {
                        Transaction::open('db');

                        $verifica = TransacaoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND id_despesaFixa = {$df->idDesp} AND DATE(data_trans) = DATE('{$dataDF}')");

                        Transaction::close();
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }

                    if(empty($verifica))
                    {
                        try {
                            Transaction::open('db');
    
                            $transacao = new TransacaoModel();
                            $transacao->data_trans = $dataDF." ".date('H:i:s');
                            $transacao->valor = $df->valor;
                            $transacao->descricao = $df->descricao;
                            $transacao->id_categoria = $df->id_categoria;
                            $transacao->tipo = 'despesa';
                            $transacao->status_trans = 'pendente';
                            $transacao->fixo = 1;
                            $transacao->id_despesaFixa = $df->idDesp;
                            $transacao->id_usuario = $df->id_usuario;
                            $transacao->id_conta = $df->id_conta;
                           
                            $transacao->store();

                            Transaction::close();
                        } catch (\Exception $e) {
                            Transaction::rollback();
                        }
                    }
                }
            }
            //DESPESA PARCELADA
            else {
                $timeZone = new \DateTimeZone('UTC');
                
                $dataDFAtual = explode('-',$df->data_inicio);
                $dataDFAtual = date('Y-m').'-'.$dataDFAtual[2];

                $dataInicio = \DateTime::createFromFormat ('Y-m-d',$df->data_inicio, $timeZone);
                $dataFim = \DateTime::createFromFormat ('Y-m-d', $df->data_fim, $timeZone);
                $dataAtual = \DateTime::createFromFormat ('Y-m-d',$dataDFAtual, $timeZone);

                if ($dataAtual >= $dataInicio && $dataAtual <= $dataFim) {


                    // echo 'Parcelada<br>';
                 //dd(geraListaDatas($dataInicio->format('Y-m-d'),$dataFim->format('Y-m-d')),false);


                    try {
                        Transaction::open('db');
                        
                        $verifica = TransacaoModel::findBy("id_usuario = ".UsuarioSession::get('id')." AND id_despesaFixa = {$df->idDesp} AND DATE(data_trans) = DATE('{$dataDFAtual}')");
                        
                        Transaction::close();
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }
                    
                    if(empty($verifica))
                    {
                        try {
                            Transaction::open('db');
    
                            $transacao = new TransacaoModel();
                            $transacao->data_trans = $dataDFAtual." ".date('H:i:s');
                            $transacao->valor = $df->valor;
                            $transacao->descricao = $df->descricao;
                            $transacao->id_categoria = $df->id_categoria;
                            $transacao->tipo = 'despesa';
                            $transacao->status_trans = 'pendente';
                            $transacao->id_despesaFixa = $df->idDesp;
                            $transacao->id_usuario = $df->id_usuario;
                            $transacao->id_conta = $df->id_conta;

                           
                           
                            $transacao->store();

                            Transaction::close();
                        } catch (\Exception $e) {
                            Transaction::rollback();
                        }
                    }
                }
            }
        }
    }
}



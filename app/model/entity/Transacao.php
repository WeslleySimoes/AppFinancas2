<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\model\entity\Conta;
use app\model\entity\Categoria;
use app\model\entity\Transferencia;
use app\model\entity\DespesaFixa as DespesaFixaModel;
use app\model\entity\ReceitaFixa as ReceitaFixaModel;
use app\session\Usuario as UsuarioSession;

class Transacao extends Record
{
    const TABLENAME = 'transacao';
    const TABLE_PK  = 'idTransacao';
    

    public function store()
    {
        if(isset($this->data['transferencia']))
        {
            $resultado = $this->data['transferencia']->store();

            if($resultado)
            {
                if(isset($this->data['transferencia']->idTransferencia))
                {
                    $this->data['id_transferencia'] = $this->data['transferencia']->idTransferencia;

                    return parent::store() or $resultado;
                }
                else{
                    $this->data['id_transferencia'] = $this->data['transferencia']->getLast();
                    return parent::store();
                }
            }else
            {
                return false;
            }
            
        }
        else{    
            return parent::store();
        }
    }

    //obter nome de categoria
    public function getNomeCategoria()
    {
        try {   
            Transaction::open('db');

            $c = Categoria::find($this->data['id_categoria']);
            return $c->nome;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }


    public function getCorCategoria()
    {
        try {   
            Transaction::open('db');

            $c = Categoria::find($this->data['id_categoria']);
            return $c->cor_cate;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }

    //obter nome de categoria
    public function getDescricaoConta()
    {
        try {   
            Transaction::open('db');

            $c = Conta::find($this->data['id_conta']);
            return $c->descricao;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }


    public function getTransferencia()
    {
        try {   
            Transaction::open('db');

            $c = Transferencia::find($this->data['id_transferencia']);
            return $c;

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }

    public static function total(int $id_usuario,$where = null)
    {
        $classe = get_called_class();

        $sql = "SELECT COUNT(*) AS total FROM ".constant("{$classe}::TABLENAME")." WHERE id_usuario =  {$id_usuario} ";

        if($where)
        {
            $sql .= ' AND '.$where;
        }

        //dd($sql);

        if($conn = Transaction::get())
        {
            $result = $conn->query($sql);

            return $result->fetch();
        }
        else{
            throw new \Exception('Não há transação ativa!');
        }
        
    }


    public static function inseriDespesasFixasMesAtual()
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
                
                $finalMes = getDataFinalMesAtual(date('Y-m-d'));
                $diaDF = $dataDF[2] > $finalMes  ?  $finalMes : $dataDF[2] ;

                $dataDF = date('Y-m').'-'.$diaDF;

                $data1 = \DateTime::createFromFormat ('Y-m-d',$df->data_inicio, $timeZone);
                $data2 = \DateTime::createFromFormat ('Y-m-d', $dataDF, $timeZone);

                if ($data2 >= $data1 && $df->status_desp == 'aberto') {


                    // echo 'Fixa<br>';


                    // foreach (geraListaDatas($data1->format('Y-m-d'),$data2->format('Y-m-d')) as $dataGerada) {
                        
                    //     echo $dataGerada.'<br>';
                    // }
   
                    try {
                        Transaction::open('db');

                        $verifica = self::findBy("id_usuario = ".UsuarioSession::get('id')." AND id_despesaFixa = {$df->idDesp} AND DATE(data_trans) = DATE('{$dataDF}')");

                        Transaction::close();
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }

                    if(empty($verifica))
                    {
                        try {
                            Transaction::open('db');
    
                            $transacao = new Transacao();
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

                if ($dataAtual >= $dataInicio && $dataAtual <= $dataFim && $df->status_desp == 'aberto') {


                    // echo 'Parcelada<br>';
                 //dd(geraListaDatas($dataInicio->format('Y-m-d'),$dataFim->format('Y-m-d')),false);


                    try {
                        Transaction::open('db');
                        
                        $verifica = Transacao::findBy("id_usuario = ".UsuarioSession::get('id')." AND id_despesaFixa = {$df->idDesp} AND DATE(data_trans) = DATE('{$dataDFAtual}')");
                        
                        Transaction::close();
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }
                    
                    if(empty($verifica))
                    {
                        try {
                            Transaction::open('db');
    
                            $transacao = new Transacao();
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


    public static function inseriReceitasFixasMesAtual()
    {
        try {
            Transaction::open('db');

            $receitasFixas = ReceitaFixaModel::findBy('id_usuario = '.UsuarioSession::get('id'));

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        foreach ($receitasFixas as $df)
        {
            //DESPESA FIXA
            if($df->data_fim == '0000-00-00' || $df->data_fim == NULL)
            {
                $timeZone = new \DateTimeZone('UTC');

                $dataDF = explode('-',$df->data_inicio);
                
                $finalMes = getDataFinalMesAtual(date('Y-m-d'));
                $diaDF = $dataDF[2] > $finalMes  ?  $finalMes : $dataDF[2] ;

                $dataDF = date('Y-m').'-'.$diaDF;

                $data1 = \DateTime::createFromFormat ('Y-m-d',$df->data_inicio, $timeZone);
                $data2 = \DateTime::createFromFormat ('Y-m-d', $dataDF, $timeZone);

                if ($data2 >= $data1 AND $df->status_rec == 'aberto') {


                    // echo 'Fixa<br>';


                    // foreach (geraListaDatas($data1->format('Y-m-d'),$data2->format('Y-m-d')) as $dataGerada) {
                        
                    //     echo $dataGerada.'<br>';
                    // }
   
                    try {
                        Transaction::open('db');

                        $verifica = self::findBy("id_usuario = ".UsuarioSession::get('id')." AND id_receitaFixa = {$df->idRec} AND DATE(data_trans) = DATE('{$dataDF}')");

                        Transaction::close();
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }

                    if(empty($verifica))
                    {
                        try {
                            Transaction::open('db');
    
                            $transacao = new Transacao();
                            $transacao->data_trans = $dataDF." ".date('H:i:s');
                            $transacao->valor = $df->valor;
                            $transacao->descricao = $df->descricao;
                            $transacao->id_categoria = $df->id_categoria;
                            $transacao->tipo = 'receita';
                            $transacao->status_trans = 'pendente';
                            $transacao->fixo = 1;
                            $transacao->id_receitaFixa = $df->idRec;
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

                if ($dataAtual >= $dataInicio && $dataAtual <= $dataFim && $df->status_rec == 'aberto') {


                    // echo 'Parcelada<br>';
                 //dd(geraListaDatas($dataInicio->format('Y-m-d'),$dataFim->format('Y-m-d')),false);


                    try {
                        Transaction::open('db');
                        
                        $verifica = Transacao::findBy("id_usuario = ".UsuarioSession::get('id')." AND id_receitaFixa = {$df->idRec} AND DATE(data_trans) = DATE('{$dataDFAtual}')");
                        
                        Transaction::close();
                    } catch (\Exception $e) {
                        Transaction::rollback();
                    }
                    
                    if(empty($verifica))
                    {
                        try {
                            Transaction::open('db');
    
                            $transacao = new Transacao();
                            $transacao->data_trans = $dataDFAtual." ".date('H:i:s');
                            $transacao->valor = $df->valor;
                            $transacao->descricao = $df->descricao;
                            $transacao->id_categoria = $df->id_categoria;
                            $transacao->tipo = 'receita';
                            $transacao->status_trans = 'pendente';
                            $transacao->id_receitaFixa = $df->idRec;
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

    // public static findByPg($inicio,$qtd,$where = '')
    // {
    //     "SELECT COUNT(*) AS total FROM ".constant("{$classe}::TABLENAME")." WHERE id_usuario =  {$id_usuario}";
    // }
}


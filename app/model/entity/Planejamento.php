<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\helpers\FormataMoeda;
use app\model\entity\PlanejamentoCate;
use app\session\Usuario as UsuarioSession;

class Planejamento extends Record
{
    const TABLENAME = 'planejamento';
    const TABLE_PK  = 'idPlan';

    public function calcularMetaGasto()
    {
        return $this->valor * ($this->porcentagem/100);
    }

    public function getPlanCategorias()
    {
        try {
            Transaction::open('db');
            
            $r = PlanejamentoCate::findBy("id_planejamento = {$this->data[self::TABLE_PK]}");

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        return $r;
    }

    public function getTotalGasto($data = null)
    {
        $idsCat = [];

        foreach($this->getPlanCategorias() as $categoria)
        {
            $idsCat[] = $categoria->id_categoria;
        }

        $idsCat =  implode(",",$idsCat);
        
        try {
            Transaction::open('db');
            
            if($data)
            {
                $sql = "SELECT SUM(valor) as totalGasto FROM transacao WHERE id_categoria in ({$idsCat}) AND DATE(data_trans) BETWEEN {$data}";
            }else{
                $sql = "SELECT SUM(valor) as totalGasto FROM transacao WHERE id_categoria in ({$idsCat}) AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE())";
            }

            $conn = Transaction::get();

            $result = $conn->query($sql);

            $r =  (float) $result->fetch()['totalGasto'];    
           
            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        $this->data['totalGastoPlan'] = $r;
        return $r;

    }

    public function resultado($t = 'MENSAL')
    {
        if($t == 'MENSAL')
        {
            return $this->calcularMetaGasto() - $this->data['totalGastoPlan'];
        }
        else if($t == 'PESONALIZADO')
        {
            return $this->data['valor'] - $this->data['totalGastoPlan'];
        }
    }

    public function getPorcentagemGasto($troca = true,$t = 'MENSAL')
    {
        if($t == 'MENSAL')
        {
            $porcentagem =  number_format($this->data['totalGastoPlan'] / $this->calcularMetaGasto() * 100,2);
        }
        else if($t == 'PERSONALIZADO')
        {
            $porcentagem =  number_format($this->data['totalGastoPlan'] / $this->data['valor'] * 100,2);
        }

        if($troca)
        {
            $porcentagem =  str_replace(',','',$porcentagem);
        }

        return $porcentagem;

    }

    public function removerTudo()
    {
        $planCate = PlanejamentoCate::findBy('id_planejamento = '.$this->data[self::TABLE_PK]);

        //ALTERANDO ID_RECEITAFIXAS PARA NULL, para não haver conflito
        foreach($planCate as $pc)
        {
            $pc->delete();
        }

        return parent::delete();
    }

    //CADASTRA PLANEJAMENTO MENSAL
    public function cadastrar(array $categorias, array $valores)
    {
        if(count($categorias) != count($valores))
        {
            return false;
        }

        $categorias = array_values($categorias);
        $valores    = array_values($valores);
        
        $resultado = $this->store();

        if($resultado)
        {
            for ($i=0; $i < count($categorias) ; $i++) { 
                
                $pc                  = new PlanejamentoCate();
                $pc->valorMeta       = FormataMoeda::moedaParaFloat($valores[$i]);
                $pc->id_categoria    = (int) $categorias[$i];
                $pc->id_planejamento = $this->getLast();
    
                if(!$pc->store())
                {
                    return false;
                }
            }

            return true;
        }else
        {
            return false;
        }
    }

    public function editarM(array $categorias, array $valores,$arrCate)
    {
        if(count($categorias) != count($valores))
        {
            return false;
        }

        $categorias = array_values($categorias);
        $valores = array_values($valores);

        $conta = 0;
        $contaADD = 0;
        $contaRemove = 0;
        
        $resultado = $this->store();
      
        //NESTE CASO IREMOS ADICIONAR PLANCATEGORIA
        if(count(array_diff($categorias,$arrCate)) > 0)
        {
            foreach(array_diff($categorias,$arrCate) as $chave => $valor)
            {
                $addPlanCate = new PlanejamentoCate();
                $addPlanCate->valorMeta = FormataMoeda::moedaParaFloat($_POST['item'][$chave]);
                $addPlanCate->id_categoria = (int) $valor;
                $addPlanCate->id_planejamento = (int) $_GET['id'];
                
                if($addPlanCate->store())
                {
                    $contaADD++;
                    unset($categorias[$chave]);
                }
            }

        }

        //NESTE CASO IREMOS REMOVER PLANCATEGORIA
        if(count(array_diff($arrCate,$categorias)) > 0)
        {
            foreach (array_diff($arrCate,$categorias) as $item) {
                $removeCategoria = PlanejamentoCate::findBy("id_categoria = {$item} AND id_planejamento = ".$_GET['id']);

                if($removeCategoria[0]->delete())
                {
                    $contaRemove++;
                    unset($categorias[$item]);
                }
            }
        }

        $categorias = array_values($categorias);
        $valores = array_values($valores);

        for ($i=0; $i < count($categorias) ; $i++) { 
        
            $pc                  = PlanejamentoCate::findBy("id_categoria = ".(int) $categorias[$i]." AND id_planejamento = ".$this->data[self::TABLE_PK])[0];

            $pc->valorMeta       = FormataMoeda::moedaParaFloat($valores[$i]);
            $pc->id_categoria    = (int) $categorias[$i];
            $pc->id_planejamento = $this->data[self::TABLE_PK];

            if($pc->store())
            {
                $conta++;
            }
        }



        if($resultado or $conta > 0 or $contaADD > 0 or $contaRemove > 0)
        {
            return true;
        }
        else{
            return false;
        }

    }

    //FAZ COM QUE TODOS OS PLANEJAMENTOS PERSONALIZADO NO QUAL AS DATA JÁ PASSARAM
    public static function alterandoStatus()
    {
        $sql = "UPDATE planejamento SET status_plan = 'expirado' WHERE id_usuario = ".UsuarioSession::get('id')." AND status_plan = 'ativo' AND tipo = 'personalizado' AND DATE(data_fim) < DATE(CURDATE());";

        try {
            Transaction::open('db');

            $conn = Transaction::get();
            
            $result = $conn->exec($sql);

            Transaction::close();

        } catch (\Exception $e) {
            Transaction::rollback();
        }

        return $result;
    }

}
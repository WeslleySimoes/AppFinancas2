<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;
use app\helpers\FormataMoeda;
use app\model\entity\PlanejamentoCate;

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

    public function getTotalGasto()
    {
        $idsCat = [];

        foreach($this->getPlanCategorias() as $categoria)
        {
            $idsCat[] = $categoria->id_categoria;
        }

        $idsCat =  implode(",",$idsCat);
        
        try {
            Transaction::open('db');
            
            $sql = "SELECT SUM(valor) as totalGasto FROM transacao WHERE id_categoria in ({$idsCat}) AND MONTH(data_trans) = MONTH(CURDATE()) AND YEAR(data_trans) = YEAR(CURDATE())";

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

    public function resultado()
    {
        return $this->calcularMetaGasto() - $this->data['totalGastoPlan'];
    }

    public function getPorcentagemGasto($troca = true)
    {
        $porcentagem =  number_format($this->data['totalGastoPlan'] / $this->calcularMetaGasto() * 100,2);

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


    public function cadastrar(array $categorias, array $valores)
    {
        if(count($categorias) != count($valores))
        {
            return false;
        }
        
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
}
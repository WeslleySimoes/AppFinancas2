<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;

class PlanejamentoCate extends Record
{
    const TABLENAME = 'plancategoria';
    const TABLE_PK  = 'idPCat';

    public function getCategoria()
    {
        try {
            Transaction::open('db');
            
            $r = Categoria::findBy("idCategoria = {$this->data['id_categoria']}");

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
        }

        $this->data['categoria_obj'] = $r[0];
        return $r;
    }

    public function resultado()
    {
        return $this->valorMeta - $this->categoria_obj->totalGastoMesAtual;
    }

    public function getPorcentagemGasto($troca = true)
    {
        $porcentagem =   $this->categoria_obj->totalGastoMesAtual / $this->valorMeta * 100 ;
        $porcentagem =   number_format($porcentagem,1);

        if($troca)
        {
            $porcentagem =   str_replace(',','',$porcentagem);
        }

        return $porcentagem;
    }
}
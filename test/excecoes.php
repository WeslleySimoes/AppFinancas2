<?php

class ExemploException extends \Exception{}

class Divisao
{
    public static function calcular(int|float $num1,int|float $num2)
    {       
        if($num1 != 0 && $num2 == 0)
        {
            throw new ExemploException('Não é possível dividir por zero!');
        }

        if($num1 == 0 && $num2 == 0)
        {
            throw new ExemploException('Resultado indefinido!');
        }

        return $num1/$num2;
    }
}


try {
    $dados = [];

    $resultado = Divisao::calcular(2,0);

    echo 'Resultado: ',$resultado;

} catch (\Exception $e) {

    $dados['msg'] = $e->getMessage();
}


echo 'teste: '.$dados['msg'];

// $this->view(['header','principal','footer'],$dados);


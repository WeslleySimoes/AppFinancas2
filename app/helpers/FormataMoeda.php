<?php 

namespace app\helpers;

class FormataMoeda
{
    /*Transforma formato float para moeda, ex: 1500.30 para 1.500,30 */
    public static function formatar(float $valor,$moeda = 'REAL',$casasDecimais = 2)
    {
        switch ($moeda) {
            case 'REAL':
                return  number_format($valor, $casasDecimais, ',', '.');
                break;
            case 'DOLAR':
                return  number_format($valor, $casasDecimais, '.', ',');
                break;
            case 'EURO':
                return  number_format($valor, $casasDecimais, ',', ' ');
                break;
            default:
                throw new \Exception("Nenhuma moeda encontrada!");
        }
    }

    /*Transforma formato moeda para float, ex: 1.500,30 para 1500.30 */
    public static function moedaParaFloat($valor)
    {
        return (float) strtr($valor,['.' => '',',' => '.']);
    }

    //Somar Moedas
    public static function somarMoedas(array $moedas)
    {
        $soma = 0;
        foreach($moedas as $moeda)
        {
            $soma += self::moedaParaFloat($moeda);
        } 

        return $soma;
    }
}
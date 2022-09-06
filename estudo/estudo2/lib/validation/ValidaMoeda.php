<?php 

namespace lib\validation;

/**
 * Classe responsável por manipular valores do tipo moeda
 */
class ValidaMoeda
{
    private function __construct(){}
    private function __clone(){}

    //Verifica se o valor passado por parâmetro está no formato de moeda BRL
    public static function e_moeda($valor):bool
    {
        return preg_match("/^[1-9]\d{0,2}(\.\d{3})*,\d{2}$/",$valor);
    }

    //Transforma o formato moeda para o formato de float
    public static function moedaParaFloat($moeda):float
    {
        return floatval( strtr($moeda,['.' => '', ',' => '.']) );
    }

    //Transforma float para o formato de moeda BRL
    public static function floatParaMoeda($valor):string
    {
        return number_format($valor, 2, ',', '.');
    }        
}


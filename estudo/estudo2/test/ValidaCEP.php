<?php 

class ValidaCEP
{

	public static function validar($cep)
	{
		if(!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) 
		{
			return false;
		}

		return true;
	}

	public static function obterNum($cep)
	{
		return strtr($cep,['-' => '']);
	}

	public static function cepToStr($cep)
	{
		$cepFinal = '';
		
		$conta = 0;
		while($conta < strlen($cep))
		{
			if($conta == 4){
				$cepFinal .= $cep[$conta].'-';	
			}
			elsE{
				$cepFinal .= $cep[$conta];
			}
			
			$conta++;
		}

		return $cepFinal;
	}
}

$cep ="09811-380";

var_dump(ValidaCEP::validar($cep));
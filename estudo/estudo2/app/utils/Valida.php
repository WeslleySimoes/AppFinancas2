<?php 

	namespace app\utils;

	class Valida
	{
		//Método responsável por validar CEP
		public static function cep($cep)
		{
			if(!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
  				return false;
			}

			return true;
		}

		//Método responsável por validar CPF
		public static function cpf($cpf)
		{
			if(!preg_match('/^([0-9]){3}\.([0-9]){3}\.([0-9]){3}-([0-9]){2}$/', $cpf)) {
				return false;
			}
			return true;		
		}
	}
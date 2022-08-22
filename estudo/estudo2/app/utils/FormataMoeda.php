<?php 
	namespace app\utils;

	class FormataMoeda
	{
		public static function formatar($valor,$moeda = 'REAL',$casasDecimais = 2)
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
	}
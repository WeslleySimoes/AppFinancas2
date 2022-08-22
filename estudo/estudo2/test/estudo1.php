<?php 	
	
	$nome = "";
 

	function campoTexto($texto,$minCaracter = 3,$maxCaracter = 60,$espECaracter = false)
	{	
		$texto = trim($texto);

		if(empty($texto))
		{
			throw new Exception("O campo não deve estar em branco!");
		}

		if(!preg_match('/^[a-zA-Z0-9]+/',$texto))
		{
			throw new Exception("O camp não deve conter caracteres especiais!");
		}

		if(!$espECaracter && strpos($texto,' ') != false)
		{
			throw new Exception("Não deve haver espaços entre os caracteres!");
		}

		if(strlen($texto) < $minCaracter || strlen($texto) > $maxCaracter)
		{
			throw new Exception("O campo deve haver de {$minCaracter} a {$maxCaracter} caracteres!");
		}

		return $texto;
	}
	
	try {

		$recebe = campoTexto(texto:$nome,nameCampo:'nome',minCaracter: 5, maxCaracter: 150);

		echo $recebe;
	} catch (\Exception $e) {
		echo $e->getMessage();
	}
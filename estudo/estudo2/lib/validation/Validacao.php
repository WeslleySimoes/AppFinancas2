<?php

namespace lib\validation;

/*
 * Classe responsável por validar campos 
*/
class Validacao
{
	private $msgErros = [];

	public function max_caracteres($string,$max=60)
	{
		if(mb_strlen($string) > $max)
		{
			$this->setMsgErro("O campo não deve conter mais de {$max} caracteres!");
		}

		return $this;
	}

	public function min_caracteres($string,$min=3)
	{
		if(mb_strlen($string) < $min)
		{
			$this->setMsgErro("O campo não deve conter menos de {$min} caracteres!");
		}

		return $this;
	}


	public function campoTexto($texto,$minCaracter = 3,$maxCaracter = 60,$espECaracter = false)
	{
		$texto = trim($texto);

		if(empty($texto))
		{
			$this->setMsgErro('O campo não deve estar em branco');
		}

		if(!preg_match('/^[a-zA-Z0-9]+/',$texto))
		{
			$this->setMsgErro("O campo não deve conter caracteres especiais!");
		}

		if(!$espECaracter && strpos($texto,' ') != false)
		{
			$this->setMsgErro("Não deve haver espaços entre os caracteres!");
		}

		if(strlen($texto) < $minCaracter || strlen($texto) > $maxCaracter)
		{
			$this->setMsgErro("O campo deve haver de {$minCaracter} a {$maxCaracter} caracteres!");
		}

		return $texto;
	}

	/*
	Método responsável por validar CEP, esquemas aceitos:
		00000-000
		00000000
	*/
	public function cep($cep)
	{
		if(!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) 
		{
			$this->setMsgErro('O CEP está incorreto!');
		}
	}		

	//Valida CNPJ, ex: 00.000.000/0000-00
	public function cnpj($cnpj)
	{
		if(!preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/',$cnpj))
		{
			$this->setMsgErro('O CNPJ está incorreto!');
		}
	}

	/* Método responsável por validar CPF, padrão: 000.000.000-00 */
	public function cpf($cpf)
	{
		if(!preg_match('/^([0-9]){3}\.([0-9]){3}\.([0-9]){3}-([0-9]){2}$/', $cpf)) 
		{
			$this->setMsgErro('O CPF está incorreto!');
		}		
	}

	public function email($email,$maxCaracter = 60)
	{
		if(!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email) > $maxCaracter)
		{
			$this->setMsgErro('O E-mail está incorreto!');
		}
	}

	//Valida moeda, ex: 1.500,32
	public function moeda($valor)
	{
	 	if(!preg_match("/^[1-9]\d{0,2}(\.\d{3})*,\d{2}$/",$valor))
	 	{
	 		$this->setMsgErro('O campo monetário está incorreto!');
	 	}
	}		

	//Valida telefone fixo, ex: (11) 1111-1111
	public function telefoneFixo($telefone)
	{
		if(!preg_match('/^\(\d{2}\) \d{4}-\d{4}$/', $telefone))
		{
			$this->setMsgErro('Número de telefone está incorreto!');
		}
	}

	//Valida telefone celular, ex: (11) 91111-1111
	public function celular($celular)
	{
		if(!preg_match('/^\(\d{2}\) 9[6789]\d{3}-\d{4}$/', $celular))
		{
			$this->setMsgErro('Número de telefone celular está incorreto!');
		}
	}

	public function campoNumerico($num)
	{
		if(!is_numeric($num))
		{
			$this->setMsgErro('Campo numérico incorreto!');
		}
	}

	private function setMsgErro($msg)
	{
		$this->msgErros[] = $msg;
	}

	public function validar()
	{
		return empty($this->msgErros);
	}

	public function getMsgErros()
	{
		return $this->msgErros;
	}
}
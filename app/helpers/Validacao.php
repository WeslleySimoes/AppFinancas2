<?php

namespace app\helpers;

/*
 * Classe responsável por validar campos 
*/
class Validacao
{
	private $msgErros = [];
    private $campoAtual;

    public function setCampo($nome)
    {
        $this->campoAtual = "'{$nome}'";
        return $this;
    }

	public function select(string $valor,array $arr)
	{
		if(!in_array($valor,$arr))
		{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}
	}
	public function max_caracteres($string,$max=60)
	{
		if(mb_strlen($string) > $max)
		{
			$this->setMsgErro("O campo {$this->campoAtual} não deve conter mais de {$max} caracteres!");
		}

		return $this;
	}

	public function min_caracteres($string,$min=3)
	{
		if(mb_strlen($string) < $min)
		{
			$this->setMsgErro("O campo {$this->campoAtual} não deve conter menos de {$min} caracteres!");
		}

		return $this;
	}


	public function data($data,$formato = 'd/m/Y')
	{
		$dt = \DateTime::createFromFormat($formato, $data);

		if($dt && $dt->format($formato) !== $data)
		{
			$this->setMsgErro("O campo {$this->campoAtual} não é válido!");

		}
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
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}

        return $this;
	}		

	//Valida CNPJ, ex: 00.000.000/0000-00
	public function cnpj($cnpj)
	{
		if(!preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/',$cnpj))
		{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}

        return $this;
	}

	/* Método responsável por validar CPF, padrão: 000.000.000-00 */
	public function cpf($cpf)
	{
		if(!preg_match('/^([0-9]){3}\.([0-9]){3}\.([0-9]){3}-([0-9]){2}$/', $cpf)) 
		{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}	
        
        return $this;
	}

	public function email($email)
	{
		if(!filter_var($email,FILTER_VALIDATE_EMAIL))
		{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}

        return $this;
	}

	//Valida moeda, ex: 1.500,32
	public function moeda($valor)
	{
	 	if(!preg_match("/^[1-9]\d{0,2}(\.\d{3})*,\d{2}$/",$valor))
	 	{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
	 	}

		 

        return $this;
	}		

	//Valida telefone fixo, ex: (11) 1111-1111
	public function telefoneFixo($telefone)
	{
		if(!preg_match('/^\(\d{2}\) \d{4}-\d{4}$/', $telefone))
		{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}

        return $this;
	}

	//Valida telefone celular, ex: (11) 96111-1111
	public function celular($celular)
	{
		if(!preg_match('/^\(\d{2}\) 9[6789]\d{3}-\d{4}$/', $celular))
		{
			$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
		}

        return $this;
	}

	public function	campoNumInteiro(int $valor)
	{
		if(!is_integer($valor))
		{
			$this->setMsgErro("O campo {$this->campoAtual} deve ser um número inteiro!");
		}
	}

	public function campoNumerico($num)
	{
		if(!is_numeric($num))
		{
			$this->setMsgErro('Campo numérico incorreto!');
		}
	}


	public function numerosIguais(int|float $camp1,int|float $camp2)
	{
		if($camp1 == $camp2)
		{
			$this->setMsgErro("Os campos {$this->campoAtual} não devem ser iguais!");
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

	public function getMsgErros():array
	{
		return $this->msgErros;
	}
}
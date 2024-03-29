<?php

namespace app\helpers;

use app\helpers\FormataMoeda;
use app\utils\FormataMoeda as UtilsFormataMoeda;

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

	public function confirmaSenha($senha,$confSenha)
	{
		if($senha != $confSenha)
		{
			$this->setMsgErro("O campo senha e confirmar senha estão diferentes!");
		}
	}

	public function select(string $valor,array $arr,$df = true)
	{
		if($df)
		{
			if(!in_array($valor,$arr))
			{
				$this->setMsgErro("O campo {$this->campoAtual} está incorreto!");
			}
		}
		else{
			if(in_array($valor,$arr))
			{
				$this->setMsgErro("O campo {$this->campoAtual} está incorreto, poís já existe um mesmo nome cadastrado!");
			}
		}
	}

	public function diffMesesDatas($dataInicio,$dataFim,$max = 2)
	{
		$data1text = $dataInicio;
		$data2text = $dataFim;

		$date1 = new \DateTime($data1text);
		$date2 = new \DateTime($data2text);
		//Repare que inverto a ordem, assim terei a subtração da ultima data pela primeira.
		//Calculando a diferença entre os meses
		$meses = ((int)$date2->format('m') - (int)$date1->format('m'))
		//    e somando com a diferença de anos multiplacado por 12
			+ (((int)$date2->format('y') - (int)$date1->format('y')) * 12);

		if($meses > $max)
		{
			$this->setMsgErro("O campo {$this->campoAtual} deve ter uma diferença entre as datas de no máximo {$max} mes(es)!");
		}
	}


	public function arrayIguais(array $arr1, array $arr2)
	{
		if(count($arr1) != count($arr2))
		{
			$this->setMsgErro("Os campos {$this->campoAtual} devem ter a mesma quantidade!");
		}
	}

	public function max_int_valor($valor,$max)
	{
		if((int) $valor > $max)
		{
			$this->setMsgErro("O campo {$this->campoAtual} não deve ser maior que {$max}!");
		}

		return $this;
	}

	public function min_int_valor($valor,$min)
	{
		if((int) $valor < $min)
		{
			$this->setMsgErro("O campo {$this->campoAtual} não deve ser menor que {$min}!");
		}

		return $this;
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

	function validateDate($date, $format = 'Y-m-d')
	{
		$d = \DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		if(($d && $d->format($format) === $date) == false) 
		{
			$this->setMsgErro("O campo {$this->campoAtual} não é válido!");
		}
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



	public function max_moeda($valor,$max = 99999999.99)
	{
		$valorFloat = FormataMoeda::moedaParaFloat($valor);

		//dd($valorFloat);
		
		if($valorFloat <= 0)
		{
			$this->setMsgErro("O campo {$this->campoAtual} não dever ser igual ou menor que 0!");
		}
		else if($valorFloat > $max)
		{
			$m = FormataMoeda::formatar($max);
			$this->setMsgErro("O campo {$this->campoAtual} não dever ser maior que R$ {$m}!");
		}
		
	}

	public function moedasIguais($moeda1,array|int $moeda2)
	{
		if(is_array($moeda1))
		{
			$moeda1 = FormataMoeda::somarMoedas($moeda1);
		}
		else{
			$moeda1 = FormataMoeda::moedaParaFloat($moeda1);
		}

		if(is_array($moeda2))
		{
			$moeda2 = FormataMoeda::somarMoedas($moeda2);
		}
		else{
			$moeda2 = FormataMoeda::moedaParaFloat($moeda2);
		}

		if($moeda1 < $moeda2 || $moeda1 > $moeda2)
		{
			$this->setMsgErro("O total dos campos {$this->campoAtual} devem ser iguais!");
		}
		
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

		return $this;
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

	public function compararData(string $data1, string $data2,$regra = 'maior')
	{	
		$timeZone = new \DateTimeZone('UTC');

		/** Assumido que $dataEntrada e $dataSaida estao em formato dia/mes/ano */
		$data1 = \DateTime::createFromFormat ('Y-m-d', $data1, $timeZone);
		$data2 = \DateTime::createFromFormat ('Y-m-d', $data2, $timeZone);

		if($regra == 'maior')
		{
			if ($data1 == $data2) {
				$campo = explode('e',$this->campoAtual);
				$this->setMsgErro("Os campos {$campo[0]} e {$campo[1]} não devem ser iguais!");
			}else if($data1 > $data2)
			{
				$campo = explode('e',$this->campoAtual);
				$this->setMsgErro("O campo {$campo[0]} deve ser menor que o campo {$campo[1]}!");
			}
		}
		if($regra == 'menor')
		{
			if ($data1 == $data2) {
				$campo = explode('e',$this->campoAtual);
				$this->setMsgErro("Os campos {$campo[0]} e {$campo[1]} não devem ser iguais!");
			}else if($data1 < $data2)
			{
				$campo = explode('e',$this->campoAtual);
				$this->setMsgErro("O campo {$campo[0]} deve ser maior que o campo {$campo[1]}!");
			}
		}
	}

	public function is_hex($hex_code) 
	{
		if(str_contains($hex_code,'#'))
		{
			$hex_code = explode('#',$hex_code)[1];
		}
		
		$resultado = @preg_match("/^[a-f0-9]{2,}$/i", $hex_code) && !(strlen($hex_code) & 1);

		if(!$resultado){
			$this->setMsgErro("O campo {$this->campoAtual} deve está incorreto!");
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
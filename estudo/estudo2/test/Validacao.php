<?php 
	/*
	Links uteis:

	Retorno o regex de e-mail mais novo: https://emailregex.com/

	https://pt.stackoverflow.com/questions/166484/validar-n%C3%BAmero-de-telefone-com-nono-d%C3%ADgito-opcional

	https://guilhermesteves.dev/tutoriais/regex-uteis-para-o-seu-dia-a-dia/

	*/

	/*
	 * Classe responsável por validar campos 
	*/
	class Validacao
	{
		private $msgErros = [];
		
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
			if(preg_match('/^\(\d{2}\)\d{4}-\d{4}$/',$telefone))
			{
				$this->setMsgErro('Número de telefone fixo está incorreto!');
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

	function flashMessage($msg,$tipo = 'danger')
	{
		if(is_array($msg) && !empty($msg) && count($msg) > 1)
		{
			echo "<div class='card {$tipo}'> <ul>";

			foreach($msg as $mensagem)
			{	
				echo "<li>{$mensagem}</li>";
			}
			echo "</ul> </div>";

			return;
		}

		echo "<div class='card {$tipo}'>".(is_array($msg) ? $msg[0] : $msg)."</div>";
		
	}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Validacao</title>
</head>
<style type="text/css">

	.card{border: 1px solid;font-weight: bold; margin: 10px 0; padding: 10px;}
	.card ul{margin: 0; padding-left: 20px;}
	.danger{border-color: red; background-color: #F8D7DA; color: #8B3F46;}
	.success{border-color: green; background-color: #D4EDDA;color: #2C693A;}
	.warning{border-color: orange; background-color: #FFF3CD; color: #856404;}
	.info{border-color: blue; background-color: #D1ECF1; color: #246672;}

</style>
<body>
	<?php 

	$cep = "09811-380";
	$cpf = "432.813.558-97";
	$username = "weslley";
	$moeda = "100,00";
	$email = 'weslley@teste.com';
	$telefoneFixo = '(11) 8765-4321';
	$telefoneCelular = '(21) 98765-4321';
	$campoQuantidade = '123';
	$cnpj = '65.173.203/0001-25';

	$validar = new Validacao;

	$validar->campoTexto(texto: $username);
	$validar->cep($cep);
	$validar->cpf($cpf);
	$validar->moeda($moeda);
	$validar->email($email);
	$validar->telefoneFixo($telefoneFixo);
	$validar->celular($telefoneCelular);
	$validar->campoNumerico($campoQuantidade);
	$validar->cnpj($cnpj);


	if($validar->validar())
	{
		echo 'Tudo okay para cadastrar ou alterar!';

	} else {

		flashMessage($validar->getMsgErros(),'success');
	}
/*
	flashMessage('Cadastro realizado com sucesso!','info');
	flashMessage('Cadastro realizado com sucesso!','success');
	flashMessage('Cadastro realizado com sucesso!','danger');*/

	?>
</body>
</html>
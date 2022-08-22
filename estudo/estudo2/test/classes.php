<?php 

	class Validacao
	{
		public static $contagem = 0;
		public static $msgErros = [];

		protected function setMsgErro($msg)
		{
			self::$msgErros[] = $msg;
		}

		public static function getMsgErros()
		{
			return self::$msgErros;
		}

		public static function validar()
		{
			return empty(self::$msgErros);
		}
	}

	class ValidaMoeda extends Validacao
	{
		public function __construct()
		{
			self::$contagem += 1;
		}

		public function inteiro($valor)
		{
			if(!is_integer($valor))
			{
				$this->setMsgErro("Valor passado por parâmetro não é inteiro");
			}
		}
	}

	class ValidaTexto extends Validacao
	{
		public function __construct()
		{
			self::$contagem += 1;
		}
	}

	$vm = new ValidaMoeda;
	$vt = new ValidaTexto;


	$vm->inteiro(1);

	echo Validacao::$contagem,'<br>';
	var_dump(Validacao::getMsgErros());
	var_dump(Validacao::validar());
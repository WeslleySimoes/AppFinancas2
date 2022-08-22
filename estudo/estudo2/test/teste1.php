<?php 

	class Validacao
	{
		private $dados;
		private $msgErros;

		public function __construct(array $dados)
		{
			$this->dados = $dados;
		}

		public function numero($valor)
		{
			return;
		}

		public function validar()
		{
			foreach(array_keys($this->dados) as $dado)
			{
				var_dump(in_array($dado,get_class_methods($this)));
				echo '<br>';
			}
		}
	}

	$validacao = (new Validacao([
		'numero' => 'adsdasd',
		'email'  => 'weslley@teste.com'
	]))->validar();
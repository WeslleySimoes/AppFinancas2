<?php 

class Rota
{
	private $path 		= '/'.NAME_BASE_DIR;
	private $rotas  	= array();
	private $caminho   	= '';
	private $namespace	= 'app\\controller\\';

	public function __construct()
	{
		$this->setCaminho();
	}

	public function add($url,$acao)
	{
		$this->rotas[$url] = $acao;
	}

	private function setCaminho()
	{
		$this->caminho = str_replace($this->path,'',$_SERVER['REQUEST_URI']);
		$this->caminho = explode('?',$this->caminho)[0];
	}

	public function executar()
	{
		if(in_array($this->caminho,array_keys($this->rotas)))
		{
			$quebraRota = explode('@',$this->rotas[$this->caminho]);

			$classe = $this->namespace.ucfirst($quebraRota[0]);
			$metodo = $quebraRota[1];

			if(class_exists($classe))
			{
				$c = new $classe();

				if(method_exists($c,$metodo))
				{
					$c->$metodo();
					exit;
				}
			}
		}
		/*
		$erro = $this->nameSpace.'Erro';
		$erro = new $erro;
		$erro->index();*/
		http_response_code(404);
		echo '<h1> Página não encontrada!</h1>';
	
	}		
}
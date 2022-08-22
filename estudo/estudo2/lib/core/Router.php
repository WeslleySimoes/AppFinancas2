<?php 

namespace lib\core;

class Router
{
	private $path 		= '/estudo2';
	private $rotas  	= array();
	private $caminho   	= '';
	private $nameSpace	= 'app\\controller\\';

	public function __construct(array $rotas)
	{
		$this->rotas = $rotas;
		$this->setCaminho();
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

			$classe = $this->nameSpace.ucfirst($quebraRota[0]);
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
		
		$erro = $this->nameSpace.'Erro';
		$erro = new $erro;
		$erro->index();
	
	}		
}
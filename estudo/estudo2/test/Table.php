<?php 

	class Tabela
	{
		private $atributos = [];
		public $_nodeID = 0;
		public $trs = [];

		public function openTr()
		{
			$this->trs[] = [];

			return $this;
		}

		public function closeTr()
		{
			$this->_nodeID++;
		}

		public function addTh($valor)
		{
			$this->trs[$this->_nodeID][] = $valor;
			return $this;
		}

		public function addTd($valor)
		{
			$this->trs[$this->_nodeID][] = $valor;
			return $this;
		}

		public function renderizarTrs():string
		{
			$junta = '';

			foreach($this->trs as $tr)
			{
				$junta .= '<tr>';
				foreach($tr as $th)
				{
					$junta .= '<th>'.$th.'</th>';
				}
				$junta .= '</tr>';
			}

			return $junta;
		}


		public function setAtributo($nomeAtributo,$valor):void
		{
			$this->atributos[$nomeAtributo] = $valor;
		}

		public function getAtributos():array
		{	
			return $this->atributos;
		}

		private function renderizaAtributos():string
		{
			$juntaAtributos = '';

			foreach(array_keys($this->atributos) as $chave)
			{
				$juntaAtributos .= $chave.' = "'.$this->atributos[$chave].'" ';
			}

			return $juntaAtributos;
		}

		public function getTabela():string
		{
			return "<table ".$this->renderizaAtributos().">tabela</table>";
		}
	
	}

	$tabela = new Tabela;	

	$tabela->setAtributo('class','minhaTabela minhaTabela2');
	$tabela->setAtributo('id','TabelaPrincipal');
	$tabela->setAtributo('style','border: 1px solid black;');


	$tabela->openTr()
		->addTh('Pessoa1')
		->addTh('Pessoa2')
		->addTh('Pessoa3')
	->closeTr();

	$tabela->openTr()
		->addTh('Pessoa1')
		->addTh('Pessoa2')
		->addTh('Pessoa3')
	->closeTr();

	echo '<table>'.$tabela->renderizarTrs().'</table>';
/*
	echo '<pre>';
	var_dump($tabela->trs);
	echo '</pre>';*/
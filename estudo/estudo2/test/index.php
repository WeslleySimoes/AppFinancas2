<?php
	/*
	$arrConfig = parse_ini_file('db.ini',true)["config-conexao-DB"];

	echo '<pre>';
	var_dump($arrConfig);
	echo '</pre>';*/


	abstract class Record
	{
		protected $data  = array();

		public function __set($prop,$value)
		{
			$this->data[$prop] = $value;
		}

		public function __get($prop)
		{
			return $this->data[$prop];
		}

		public function store(){
			echo 'Ação base<br>';
		}
		public function __toString()
		{
			return json_encode($this->data);
		}
	}	

	class Cliente extends Record{}

	class Caneta extends Record{}

	class ItemVenda{} 
	/*
		id
		id_venda
		id_produto
		qtd
		total
	*/

	class Venda extends Record
	{
		private $cliente;
		private $itens = array();

		public function setCliente(Cliente $cliente)
		{
			$this->cliente = $cliente;
			$this->id_cliente = $this->cliente->id;
		}

		public function getCliente()
		{
			$cliente = Cliente::find($this->id_cliente);
			return $cliente;
		}

		public function addItem($item)
		{
			$this->itens[] = $item;
		}

		public function getItens()
		{
			$item = new Repository('ItemVenda');
		}

		public function store()
		{
			//Cadastra a venda
			parent::store();
			
			echo '<hr>';
			//Ação adicional
			foreach($this->itens as $item)
			{
				$item->id_venda = $this->id;
				echo $item.' - ';
				$item->store();
				echo '<hr>';
			}
		}
	}	

	$cliente = new Cliente();
	$cliente->id  	= 1;
	$cliente->nome 	= 'Marcos';

	$c1 = new Caneta();
	$c1->id    = 1;
	$c1->marca = 'BIC';


	$c2 = new Caneta();
	$c2->id    = 2;
	$c2->marca = 'Castell';

	$c3 = new Caneta();
	$c3->id    = 3;
	$c3->marca = 'BrasilC';

	$c4 = new Caneta();
	$c4->id    = 4;
	$c4->marca = 'RussiaC';

	$v = new Venda;
	$v->id = 1;

	$v->addItem($c1);
	$v->addItem($c2);
	$v->addItem($c3);
	$v->addItem($c4);

	$v->store();
<?php 
	namespace app\controller;

	use lib\control\Controller;
	use lib\database\Db;
	use lib\widgets\Alerta;

	use app\model\Artigos;
	//use app\utils\{FormataMoeda,Requisicao,Valida};
	use app\session\Usuario as UsuarioSession;
	use app\model\{Anotacao as AnotacaoModel};

	class Home extends Controller
	{
		public function index()
		{	
		/*	$senha = 'Th@985jk3a#155678';

			$hashGerado = password_hash($senha, PASSWORD_DEFAULT);

			echo $hashGerado."<br><br>";

			$senhaInserida = 'minhaSenha';

			if(password_verify($senhaInserida,$hashGerado))
			{
				echo "Senha correta";
			}
			else{
				echo "Senha incorreta";
			}
			
			$numero = 1530.98;

			echo "Valor convertido: ",FormataMoeda::formatar($numero,'DOLAR');
*/
			/*Informações que serão retornadas ao usuário*/
			$dados = [
				'titulo' 	=> 'Página home',
				'artigos' 	=> Artigos::getAll()
			];

			$this->view([
				'template/header',
				'home',
				'template/footer'
			],$dados);
		
		}

		public function sobre()
		{
			UsuarioSession::setProp([
				'id' 	=> 1,
				'nome' 	=> 'José'
			]);

			$this->view([
				'template/header',
				'sobre',
				'template/footer'
			],['titulo' => 'Sobre']);


		/*	$cep = '09811380';
			$cpf = '67883712020';

			var_dump(Valida::cep($cep));
			var_dump(Valida::cpf($cpf));

			if(isset($_POST['valor']))
			{	
				echo (double) $_POST['valor'].'<br>';

				$v = strtr($_POST['valor'],array('.' => '',',' => '.'));
				$v = number_format($v,2,'.','');

				echo $v,'<br>';

				$g = 345.66666;
				$g = number_format($g,2,'.','');


				echo $g;
				var_dump(is_numeric($g));
			}


			$fmt = new \NumberFormatter('de_DE',\NumberFormatter::CURRENCY);


			$this->view(['sobre']);
			*/
		}

		public function session()
		{	
			//Verifica se o usuário está logado
			UsuarioSession::logado();

			//Dados que serão acessados através da camada view
			$dados = array(
				'titulo' 		=> 'Página Sessão',
				'usuarioLogado' => UsuarioSession::getProp('nome')
			);

			try
			{
				$conn = Db::getConnection();

				$anotacao = new AnotacaoModel($conn);

				//Adicionando anotações para ser acessado na view->session.php
				$dados['anotacoes'] = $anotacao->getAll();

			} catch(\Exception $e) {
				echo $e->getMessage();
			}

			$this->view([
				'template/header',
				'session',
				'template/footer'
			],$dados);
		
		}

		public function teste()
		{	
			try {

				Alerta::set('danger','Testando esta mensagem!');		
		
				$this->view(
				[
					'teste',
					'teste2'
				],
				[
					'tag_title' => 'Página inicial',
					'alerta' => Alerta::get()
				]);

			} catch (\Exception $e) {
				echo $e->getMessage();
			}


		}
	}
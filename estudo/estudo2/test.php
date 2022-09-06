<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<style type="text/css">
	*{
		padding: 0;
		margin: 0;
		box-sizing: border-box;
	}

	li{list-style: none;}

	.menu-lateral{
		position: fixed;
		top: 0;
		left: 0;
		width: 250px;
		min-height: 100vh;
		background-color: #3C4A62;
		color:  white;
	}

	.conteudo{
		min-height: 100vh;
		margin-left: 250px;
		background-color: #E9EAED;
		padding: 10px;
	}

	.info-dinheiro,.linha2{
		display: flex;
	}

	.linha2,.linha3{margin: 10px 0; min-height:300px ;}

	.info-dinheiro div,.linha2 div{
		flex-grow: 1;
		/*border: 1px solid black;*/
		background-color: white;
		padding: 10px;
	}

	.info-dinheiro div:first-child{
		margin-right: 10px;
	}

	.info-dinheiro div:last-child{
		margin-left: 10px;
	}

	.linha2 div:first-child{
		margin-right: 10px;
	}

	.linha3{/*border: 1px solid black; */background-color: white;}

	.linha3 div{padding: 10px;}

</style>
<body>
	<div class="menu-lateral">Menu Lateral</div>
	<div class="conteudo">
		<div class="info-dinheiro">
			<div>Saldo</div>
			<div>Ganhos</div>
			<div>Perdas</div>
		</div>
		<div class="linha2">
			<div>Grafico1</div>
			<div>Grafico2</div>
		</div>
		<div class="linha3">
			<div>
				<form action="<?= $_SERVER['REQUEST_URI']?>" method="GET">
					<label for="pesquisa">Pesquisar:</label>
					<input type="text" name="pesquisa" id="pesquisa" placeholder="Pesquisar...">
					<button type="submit">Enviar</button>
				</form>

				<!-- SISTEMA DE PAGINAÇÃO COM BUSCA -->
				<?php if(isset($_GET['pesquisa']) and !empty($_GET['pesquisa'])):?>
					<?= "SELECT * FROM pessoa WHERE nome = '".$_GET['pesquisa']."'" ?>
				<ul>
					<li><a href="./test.php?pesquisa=<?= $_GET['pesquisa']?>&pagina=1">1</a></li>
					<li><a href="./test.php?pesquisa=<?= $_GET['pesquisa']?>&pagina=2">2</a></li>
					<li><a href="./test.php?pesquisa=<?= $_GET['pesquisa']?>&pagina=3">3</a></li>
					<li><a href="./test.php?pesquisa=<?= $_GET['pesquisa']?>&pagina=4">4</a></li>
				</ul>
				<?php else: ?>
				<?= "SELECT * FROM pessoa" ?>
				<ul>
					<li><a href="./test.php?pagina=1">1</a></li>
					<li><a href="./test.php?pagina=2">2</a></li>
					<li><a href="./test.php?pagina=3">3</a></li>
					<li><a href="./test.php?pagina=4">4</a></li>
				</ul>					
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>
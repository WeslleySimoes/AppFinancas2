<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Página de valicao</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= ASSET_CSS_URL?>/style.css">
</head>
<body>
	<div class="wrapper">
		<h1>Formulário</h1>
		<div class="container">
			<form action="<?=HOME_URL?>/teste/index" method="POST">
				<label>CEP: </label>
				<input type="text" class="cep"  name="cep" ><br>
				<label>Salário: R$ </label>
				<input type="text" class="money" name="moeda" ><br>	
				<label>Telefone:</label>
				<input type="text" class="phone" name="telefoneFixo"><br>
				<label>Celular:</label>
				<input type="text" class="celular" name="celular"><br>
				<button type="submit">Enviar</button>
			</form>
			<?= $mensagem ?>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
			    $('.cep').mask('00000-000');
			    $('.money').mask('000.000.000.000.000,00', {reverse: true});
			    $('.phone').mask('(00) 0000-0000');
		    	$('.celular').mask('(00) 00000-0000');
			});
		</script>

		<script type="text/javascript" src="<?=ASSET_JS_URL?>/jquery.mask.min.js"></script>
	</div>
</body>
</html>
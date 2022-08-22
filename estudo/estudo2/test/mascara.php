<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Máscara</title>
	<script src="jquery.js" type="text/javascript"></script>
	<script src="jquery.maskedinput.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

	<label>DATA</label>
	<input type="text" class="date"><br>

	<label>HORAS</label>
	<input type="text" class="time"><br>

	<label>CEP</label>
	<input type="text" class="cep"><br>

	<label>TELEFONE</label>
	<input type="text" class="phone"><br>

	<label>CELULAR</label>
	<input type="text" class="celular"><br>

	<label>CPF</label>
	<input type="text" class="cpf"><br>

	<label>DINHEIRO</label>
	<input type="text" class="money"><br>

	<br>
	<table border="1"> 
	  <tr>
	    <th>Nome</th>
	    <th>Telefone</th>
	    <th>CPF</th>
	  </tr>
	  <tr>
	    <td>Alfreds Futterkiste</td>
	    <td class="phone">1111111111</td>
	    <td class="cpf">11111111111</td>
	  </tr>
	  <tr>
	    <td>Alfreds Futterkiste</td>
	    <td class="phone">1111111111</td>
	    <td class="cpf">222.222.222-22</td>
	  </tr>
	</table>
	
	 
	<script type="text/javascript">
		$(document).ready(function(){
		    $('.date').mask('00/00/0000');
		    $('.time').mask('00:00:00');
		    $('.cep').mask('00000-000');
		    $('.phone').mask('(00) 0000-0000');
		    $('.celular').mask('(00) 00000-0000');
		    $('.cpf').mask('000.000.000-00');
		    $('.money').mask('000.000.000.000.000,00', {reverse: true});
		});
	</script>

	<script type="text/javascript" src="jquery.mask.min.js"></script>
</body>
</html>
<?php 

	$celular = "11111111111";

	function FormatarCelular($celular)
	{
		$ddd = substr($celular,0,2);
		$numPart1 = substr($celular,3,5);
		$numPart2 = substr($celular,7);
		
		return  '('.$ddd.') '.$numPart1.'-'.$numPart2;
	}

	echo FormatarCelular($celular);
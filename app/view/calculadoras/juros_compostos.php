<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Juros Compostos</title>
	</head>

	<style type="text/css">
		*{
			box-sizing: border-box;
		}
		#conteudo-principal{
			border: 1px solid black;
			padding: 0px;
		}

		#conteudo-principal h1{
			border-bottom: 1px solid black;
			margin: 0;
			padding: 5px;
			background-color: lightgreen;
			font-size: 28px;
		}

		#resultado{
			
			font-weight: bold;
		}

		.inserir-valor{
			font-weight: bold;
		}

		.inserir-valor input[type="text"],.inserir-valor input[type="number"]{
			display: block;
			width: 100%;
			height: 25px;
			padding: 20px 0 20px 5px;
			border: 0.5px solid grey;
            font-size: 1.02em;
		}

		.inserir-valor button{
			/* border: 1px solid black; */
            padding: 5px 20px;
			font-weight: bold;
			font-size: 16px;
		}

        .inserir-valor p {margin: 10px 0 2px 0;}
	</style>
	<body>

    <div class="container-form">
        <h3 class="title-form">Calculadora de Juros compostos</h3>
        <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
        <div class="inserir-valor">
            <p>Capital (R$): </p>
            <input type="text" name="capital" id="capital" class="money" value="0,00">
            <p>Juros mensal (%): </p>
            <input type="number" name="juros" id="juros" value="0">
            <p>Tempo (meses): </p>
            <input type="number" name="tempo" id="meses" value="0">
            <br>
            <button id="calcular" class="botao-link botao-link-edit">Calcular</button>
            <button id="limpar" class="botao-link botao-link-edit">Limpar</button>
        </div>
        <table id="resultado"></table>
    </div>
    <script type="text/javascript">
        function moneyToFloat(money)
        {
            if(money == '0,00' || money == 'R$ 0,00')
            {
                return 0;
            }

            let l = money.replace(/[^0-9,-]+/g,"");
            l = l.replace(',','.');
            

            return parseFloat(l);
        }

		window.onload = function(){

			var calcular = document.getElementById("calcular");
			var limpar = document.getElementById("limpar");

			var resultado = document.getElementById("resultado");

			var capital = document.getElementById("capital");
			var juros = document.getElementById("juros");
			var tempo = document.getElementById("meses");

			calcular.onclick = function(){

				  //Instanciando o objeto
				var formatter = new Intl.NumberFormat('pt-BR', {
					style: 'currency',
					currency: 'BRL',
					minimumFractionDigits: 2,
				});



				resultado.innerHTML = "";

                let capitalFloat = moneyToFloat(capital.value);
				
				var montante = "";
				var final = (capitalFloat*(Math.pow(1+(juros.value/100),tempo.value))).toFixed(2);

				for (var i = 0; i <= tempo.value; i++) {

					let TotalCalc = (capitalFloat*(Math.pow(1+(juros.value/100),i))).toFixed(2);	
					TotalCalc = formatter.format(TotalCalc);

					montante += "<tr><td>"+(i)+"</td><td style='width: 50%;'>"+(TotalCalc) + "</td></tr>";

					if (i == tempo.value) {
						montante += "<tr><td>Rendimento Líquido</td><td>"+formatter.format((final-capitalFloat).toFixed(2))+"</td></tr>";
					}
				}

				if(resultado.innerHTML.length == 0)
				{
					resultado.style.marginTop = "15px";
					resultado.innerHTML += "<tr style='background-color:#0476B9;color:white;'><th>Meses</th><th>Juros acumulado</th></tr>";
					resultado.innerHTML += montante;
				}
			}

			limpar.onclick = function(){
				resultado.innerHTML = "";
				capital.value = "0,00";
				juros.value = 0,00;
				tempo.value = "0";

			}
		}
	</script>
    <script type="text/javascript">
		$(document).ready(function(){
		    $('.date').mask('00/00/0000');
		    $('.time').mask('00:00:00');
		    $('.cep').mask('00000-000');
		    $('.phone').mask('(00) 0000-0000');
		    $('.celular').mask('(00) 00000-0000');
		    $('.cpf').mask('000.000.000-00');
		    $('.money').mask('000.000.000.000.000,00', {reverse: true});
            $('.porcent').mask('000.000,00', {reverse: true});
		});
	</script>

	<script type="text/javascript" src="<?= ASSET_JS_URL ?>/jquery.mask.min.js"></script>
	</body>
</html>
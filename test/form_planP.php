<?php 

    if(!empty($_POST))
    {
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        *{
            padding: 0;
            box-sizing: border-box;
        }
        .form-planejamento{
            width: 600px;
            margin: 0 auto;
            border: 1px solid black;
            padding: 10px;
        }

        #check-categorias{
            border: 1px solid black;
            width: 100%;
            height: 300px;
            overflow-y: scroll;
            padding: 10px;
            margin: 10px 0;
        }

        input{
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="form-planejamento">
        <h1 style="text-align: center;">Planejamento Personalizado</h1>
        <form action="" method="POST">
            
            <label for="">Valor (R$):</label><br>
            <input type="text" name="renda" id="valorRenda"  style="width: 100%;" class="money" value="0,00" required ><br>

            <label for="descricao" style="display:inline-block;margin-top: 10px;">Descrição:</label><br>
            <input type="text" name="descricao" id="valorRenda" style="width: 100%;" required><br>

            <label for="" style="display:inline-block; width: 100%; margin-top: 10px;">Data início:</label>
            <input type="date" name="dataInicio" id="" style="width: 100%; margin-bottom: 10px;" required>
            <label for="" style="width: 100%; margin-bottom: 10px;">Data final:</label>
            <input type="date" name="dataFinal" id="" style="width: 100%; margin-bottom: 10px;" required>

            <label for="">Insira uma meta para cada categoria:</label>
            <div id="check-categorias">
                <?php for($i = 0; $i <= 10; $i++): ?>
                    <div style="padding: 10px 0;">

                        <input type="checkbox" name="categoria[<?= $i?>]"  value="<?= $i+3 ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled'); somaTotalCategoria();}else{document.getElementById('campoCate<?= $i ?>').setAttribute('disabled', 'disabled'); somaTotalCategoria();}">

                        <label for="">Teste <?= $i ?>: R$ </label>

                        <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]"  style="min-width: 72%;" value="0.00" class="formValorCate money" disabled required >
                    </div>
                <?php endfor; ?>
            </div>
            <div style="margin-bottom: 10px;" id="totalCategorias"><b style="color:red;">Valor restante: R$ 0,00</b></div>
            <button type="submit" id="btnCadastrarPlan" disabled>Cadastrar</button>
        </form>
    </div>

    <script src="jquery.mask.min.js"></script>
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
    <script>
        const valorRenda        = document.querySelector("#valorRenda");
        const btnCadastrarPlan  = document.querySelector("#btnCadastrarPlan");
        
        //Calcula total de categorias
        const formValorCate       = document.querySelectorAll("input.formValorCate");
        const totalCategorias     = document.querySelector('div#totalCategorias');

        function moneyToFloat(money)
        {
            if(money == '0,00' || money == 'R$ 0,00')
            {
                return 0;
            }

            let m = money.replace('.','');
            m = m.replace(',','.');
            m = m.replace('R$','');

            return parseFloat(m);
        }

        //##################### CAMPO VALOR RENDA #####################
        valorRenda.addEventListener("focusout",() => {

            if(valorRenda.value == '' || valorRenda.value == '0')
            {
                valorRenda.value = '0,00';
            }

            //Instanciando o objeto
            var formatter = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2,
            });

            totalCategorias.value = valorRenda.value;

            somaTotalCategoria();

        });


        for (let index = 0; index < formValorCate.length; index++) 
        {
            formValorCate[index].addEventListener("focusout",() => {

                if(formValorCate[index].value == '' ||  formValorCate[index].value == '0' )
                {
                    formValorCate[index].value ='0,00';
                }
                else{
                    formValorCate[index].value =  formValorCate[index].value;
                }

                let soma = 0;

                for (let index = 0; index < formValorCate.length; index++) 
                {
                    if(!formValorCate[index].disabled)
                    {
                        soma += moneyToFloat(formValorCate[index].value);
                    }
                }
                
                //Instanciando o objeto
                var formatter = new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                    minimumFractionDigits: 2,
                });

                totalCategorias.innerHTML = 'Valor restante: '+formatter.format(moneyToFloat(valorRenda.value) - soma);


                console.log(valorRenda.value);
                console.log(moneyToFloat(valorRenda.value));
                console.log(soma);

                if(soma > moneyToFloat(valorRenda.value) || soma < moneyToFloat(valorRenda.value || soma == 0))
                {
                    totalCategorias.innerHTML = '<b style="color:red;">'+ totalCategorias.innerHTML + '</b>';
                    btnCadastrarPlan.disabled = true;
                }
                else{
                    totalCategorias.innerHTML = '<b style="color:green;">'+ totalCategorias.innerHTML + '</b>';
                    btnCadastrarPlan.disabled = false;
                }
            });
            
        }

        function somaTotalCategoria()
        {
            let soma = 0;

            for (let index = 0; index < formValorCate.length; index++) 
            {
                if(!formValorCate[index].disabled)
                {
                    soma += moneyToFloat(formValorCate[index].value);
                }
            }
            
            //Instanciando o objeto
            var formatter = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2,
            });

            //SOMA TOTAL DAS CATEGORIAS
            totalCategorias.innerHTML =  'Valor restante: '+formatter.format(moneyToFloat(valorRenda.value) - soma);

            if(soma > moneyToFloat(valorRenda.value) || soma < moneyToFloat(valorRenda.value) || soma == 0)
            {
                totalCategorias.innerHTML = '<b style="color:red;">'+ totalCategorias.innerHTML + '</b>';
                btnCadastrarPlan.disabled = true;
            }
            else{
                totalCategorias.innerHTML = '<b style="color:green;">'+ totalCategorias.innerHTML + '</b>';
                btnCadastrarPlan.disabled = false;
            }
        }
    </script>
</body>
</html>
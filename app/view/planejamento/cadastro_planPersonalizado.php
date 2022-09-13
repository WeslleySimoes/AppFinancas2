<div class="container-form">
    <h3 class="title-form">Cadastro de planejamento Personalizado</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <div>
    <form action="<?= HOME_URL ?>/planejamento/cadastrarPP" method="POST">
        
        <label for="">Valor (R$):</label>
        <input type="text" name="renda" id="valorRenda"  style="width: 100%;" class="money" value="0,00" required >

        <label for="descricao" style="display:inline-block;margin-top: 10px;">Descrição:</label>
        <input type="text" name="descricao" id="valorRenda" style="width: 100%;" required>

        <label for="" style="display:inline-block;margin-bottom: 10px;">Data início:</label>
        <input type="date" name="dataInicio" required style="display:inline-block;margin-bottom: 10px; border: 1px solid #ccc;">
        <label for="" style="display:inline-block;margin-bottom: 10px;">Data final:</label>
        <input type="date" name="dataFinal" required style="display:inline-block;margin-bottom: 10px; border: 1px solid #ccc;">

        <label for="" margin-bottom: 10px;>Insira uma meta para cada categoria:</label>
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

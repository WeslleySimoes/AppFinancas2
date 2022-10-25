<div class="container-form">
    <h3 class="title-form">Editar planejamento Personalizado</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <div>
    <form action="<?= HOME_URL ?>/planejamento/editarPP?id=<?= $_GET['id'] ?>" method="POST">     

        <label for="">Valor (R$):</label>
        <input type="text" name="renda" id="valorRenda"  style="width: 100%;" class="money" value="<?= formatoMoeda($planejamento_atual->valor) ?>" required >

        <label for="descricao" style="display:inline-block;margin-top: 10px;">Descrição:</label>
        <input type="text" name="descricao" id="valorRenda" style="width: 100%;" required value="<?= $planejamento_atual->descricao ?>">

        <label for="" style="display:inline-block;margin-bottom: 10px;">Data início:</label>
        <input type="date" name="dataInicio" required style="display:inline-block;margin-bottom: 10px; border: 1px solid #ccc;" value="<?= $planejamento_atual->data_inicio ?>">
        <label for="" style="display:inline-block;margin-bottom: 10px;">Data final:</label>
        <input type="date" name="dataFinal" required style="display:inline-block;margin-bottom: 10px; border: 1px solid #ccc;" value="<?= $planejamento_atual->data_fim ?>">

        <label for="" margin-bottom: 10px;>Insira uma meta para cada categoria:</label>
        <div id="check-categorias">

            <div style="padding: 10px 0;">

            <?php $i = 0; foreach($categoriasDesp as $cd): ?>

<?php $verifica = false ?>

<?php foreach($planejamento_atual->getPlanCategorias() as $planCat): ?>
    <?php $planCat->getCategoria() ?>
    <?php if($planCat->categoria_obj->idCategoria === $cd->idCategoria): ?>

        <input type="checkbox" name="categoria[<?= $i ?>]"  value="<?= $cd->idCategoria ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled'); somaTotalCategoria();}else{document.getElementById('campoCate<?= $i ?>').setAttribute('disabled', 'disabled'); somaTotalCategoria();}" checked>

        <label for=""> <?= $cd->nome ?>: R$ </label>

        <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]"  style="min-width: 72%;" value="<?= formatoMoeda($planCat->valorMeta) ?>" class="formValorCate money" required >


    <?php $i++; $verifica = true; break; endif; ?>
<?php endforeach; ?>

<?php if(!$verifica): ?>
    <input type="checkbox" name="categoria[<?= $i?>]"  value="<?= $cd->idCategoria ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled'); somaTotalCategoria();}else{document.getElementById('campoCate<?= $i ?>').setAttribute('disabled', 'disabled'); somaTotalCategoria();}">

    <label for=""> <?= $cd->nome ?>: R$ </label>

    <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]"  style="min-width: 72%;" value="0.00" class="formValorCate money" disabled required >
<?php $i++; endif;  ?>

<?php endforeach ?>
            </div>

        </div>
        <div style="margin-bottom: 10px;" id="totalCategorias"><b style="color:green;">Valor restante: R$ 0,00</b></div>
        <button type="submit" id="btnCadastrarPlan" class="botao-link botao-link-edit2"  style="font-size: 1.02em">Editar</button>
    </form>
    </div>
    <?= $msg ?>
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

        let l = money.replace(/[^0-9,-]+/g,"");
        l = l.replace(',','.');
        

        return parseFloat(l);
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

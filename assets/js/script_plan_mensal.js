const metaGasto         = document.querySelector("#metaGasto");
const valorRenda        = document.querySelector("#valorRenda");
const porcentMeta       = document.querySelector("#porcentMeta");
const btnCadastrarPlan  = document.querySelector("#btnCadastrarPlan");

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

    console.log(moneyToFloat(valorRenda.value));

   

    //Instanciando o objeto
    var formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2,
    });

    let total = moneyToFloat(valorRenda.value)*(parseFloat(porcentMeta.value)/100);

    metaGasto.value = formatter.format(total.toFixed(2));

    somaTotalCategoria();

});
//############################################################
//##################### CAMPO PORCENTAGEM #####################
porcentMeta.addEventListener("focusout",() => {

    if(porcentMeta.value == '')
    {
        porcentMeta.value = 0;
    }

     //Instanciando o objeto
     var formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2,
    });

    if(moneyToFloat(valorRenda.value) != NaN)
    {
        let total = moneyToFloat(valorRenda.value)*(parseFloat(porcentMeta.value)/100);


        metaGasto.value = formatter.format(total.toFixed(2));
    }

    somaTotalCategoria();

});
//############################################################

//Calcula total de categorias
const formValorCate       = document.querySelectorAll("input.formValorCate");
const totalCategorias     = document.querySelector('div#totalCategorias');

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

        totalCategorias.innerHTML = 'Valor restante: '+formatter.format(moneyToFloat(metaGasto.value) - soma);


        console.log(metaGasto.value);
        console.log(moneyToFloat(metaGasto.value));
        console.log(soma);

        if(soma > moneyToFloat(metaGasto.value) || soma < moneyToFloat(metaGasto.value || soma == 0))
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
    totalCategorias.innerHTML =  'Valor restante: '+formatter.format(moneyToFloat(metaGasto.value) - soma);

    if(soma > moneyToFloat(metaGasto.value) || soma < moneyToFloat(metaGasto.value) || soma == 0)
    {
        totalCategorias.innerHTML = '<b style="color:red;">'+ totalCategorias.innerHTML + '</b>';
        btnCadastrarPlan.disabled = true;
    }
    else{
        totalCategorias.innerHTML = '<b style="color:green;">'+ totalCategorias.innerHTML + '</b>';
        btnCadastrarPlan.disabled = false;
    }
}
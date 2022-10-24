<?php 
// function obterValorMoeda($moeda = 'dolar')
// {
//     $moedaAtual = 'USD-BRL';

//     switch($moeda)
//     {
//         case 'dolar':
//             $moedaAtual = 'USD-BRL';
//             break;
//         case 'euro':
//             $moedaAtual = 'EUR-BRL';
//             break;
//         case 'libra':
//             $moedaAtual = 'GBP-BRL';
//             break;
//     }

//     $ch = curl_init();
//     $timeout = 0;
//     curl_setopt($ch, CURLOPT_URL, "https://economia.awesomeapi.com.br/json/last/{$moedaAtual}");
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//     $conteudo = curl_exec ($ch);
//     curl_close($ch);

//     switch($moeda)
//     {
//         case 'dolar':
//             $conteudo =  json_decode($conteudo)->USDBRL->ask;
//             break;
//         case 'euro':
//             $conteudo =  json_decode($conteudo)->EURBRL->ask;
//             break;
//         case 'libra':
//             $conteudo =  json_decode($conteudo)->GBPBRL->ask;
//             break;
//     }


//     return $conteudo;
// }

?>

<div class="container-form">
    <h3 class="title-form">Conversor de moeda</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <label for="valor">Real (R$):</label>
    <input type="text" class="money" name="valor" id="valorReal"  required value="0,00">

    <label for="moeda">Moeda:</label>
    <select name="moeda" id="moedaAconverter">
        <option value="dolar" selected>Dolar</option>
        <option value="euro">Euro</option>
        <option value="libra">Libra</option>
    </select>     

    <label for="valor">Valor Convertido:</label>
    <input type="text" name="valor" id="valorConvertido"  required value="0,00">
    
    <button class="botao-link botao-link-edit" style="font-size: 1.1em;" id="btn-converter">Converter</button > 

    <div id="msgConversor"></div>

</div>

<script>
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

    let valorDolar = 0;
    let valorEURO  = 0;
    let valorLIBRA = 0;

    $.getJSON('https://economia.awesomeapi.com.br/json/last/USD-BRL',function (data)
    {
        valorDolar = data.USDBRL.ask;
    });

    $.getJSON('https://economia.awesomeapi.com.br/json/last/EUR-BRL',function (data)
    {
        valorEURO = data.EURBRL.ask;
    });

    $.getJSON('https://economia.awesomeapi.com.br/json/last/GBP-BRL',function (data)
    {
        valorLIBRA = data.GBPBRL.ask;
    });

    const valorReal = document.querySelector("#valorReal");
    const moedaAconverter = document.querySelector("#moedaAconverter");
    const valorConvertido = document.querySelector("#valorConvertido");
    const btnConverter    = document.querySelector("#btn-converter");


    btnConverter.onclick = () => 
    {
        if(moneyToFloat(valorReal.value) > 0)
        {
            msgConversor.innerHTML = '';
            if(moedaAconverter.value == 'dolar')
            {
                let calcularConversao = moneyToFloat(valorReal.value) / valorDolar;
                valorConvertido.value = (calcularConversao.toFixed(2).toLocaleString("en-US", {minimumFractionDigits: 2}));
    
            }else if(moedaAconverter.value == 'euro')
            {
                let calcularConversao = moneyToFloat(valorReal.value) / valorEURO;
                valorConvertido.value = (calcularConversao.toFixed(2).toLocaleString("es-ES", {minimumFractionDigits: 2}));
    
    
            }else if(moedaAconverter.value == 'libra')
            {
                let calcularConversao = moneyToFloat(valorReal.value) / valorLIBRA;
                valorConvertido.value = (calcularConversao.toFixed(2).toLocaleString("en-GB", {minimumFractionDigits: 2}));
            }
        }
        else{
            msgConversor.innerHTML = "<div class='alert error-alert'><div>Valor em 'Real (R$)' deve ser maior que zero</div></div>";
        }
    }
</script>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .item-perso
        {
            border-radius: 15px;
            border: 1px solid black;
            width: 320px;
            height: 200px;
            padding: 10px;
            margin: 50px;
        }
        .plan-perso-header{
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="item-perso">
        <div class="plan-perso-header">
            <p style="font-size: 1.2em;"><b>Reforma da casa</b></p>
            <div>
                <a href="#editar" style="display:inline-block; margin-right: 5px;font-size:1.15em;"><i class="fa fa-pencil" aria-hidden="true" ></i></a>
                <a href="#remover" style="display:inline-block; margin-right: 5px;font-size:1.15em;"><i class="fa fa-trash" aria-hidden="true"></i></a>
                <a href="#detalhes"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
            </div>
        </div>
        <p style="font-size: 0.85em;color:grey;font-weight:bold;margin-top:10px;">30/09/22 até 30/01/23</p>
        <div style="margin-top: 15px;">
            <span style="display:inline-block; text-align: center;">R$ 5.000,00 <br> Meta</span>
            <b style="display: inline-bloc;margin: 0 5px;">-</b>
            <span style="display:inline-block; text-align: center;color:red;">R$ 5.000,00 <br> Valor Gasto</span>
            <b style="display: inline-bloc;margin: 0 5px;">=</b>
            <span style="display:inline-block; text-align: center;color:green;">R$ 5.000,00 <br> Restam</span>
        </div>
        <div style="margin-top: 15px;">
            <progress id="file" value="56" max="100" style="width: 100%;"></progress>
            <div style="text-align: right;">56%</div>
        </div>
        <p style="margin-top: 5px;">Expira em: 90 dias</p>
    </div>
    
</body>
</html>
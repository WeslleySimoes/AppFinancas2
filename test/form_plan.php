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
    <style>
        *{
            padding: 0;
            box-sizing: border-box;
        }
        .form-planejamento{
            width: 400px;
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
    </style>
</head>
<body>
    <div class="form-planejamento">
        <h1>Planejamento mensal</h1>
        <form action="form_plan.php" method="POST">

            <label for="">Renda (R$):</label><br>
            <input type="text" name="renda"  style="width: 100%;"><br><br>

            <label for="">Meta de gasto (R$):</label>
            <input type="text" name="metaGasto"  style="border: none; color: red;" value="1200" disabled><br><br>
            <input type="range" id="vol" name="vol" min="100" max="1200" style="width: 100%;"><br><br>  

            <label for="">Insira um meta para cada categoria:</label>
            <div id="check-categorias">
                <?php for($i = 0; $i <= 10; $i++): ?>
                    <div style="padding: 10px 0;">
                        <input type="checkbox" name="categoria[<?= $i?>]" value="<?= $i+3 ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled');}">
                        <label for="">Teste <?= $i ?>:</label>
                        <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]" style="min-width: 72%;" disabled required>
                    </div>
                <?php endfor; ?>
            </div>
            <div style="margin-bottom: 10px;">Total: R$ 00,00</div>
            <button type="submit">Cadastrar</button>
        </form>
    </div>


    <script>
        
    </script>
</body>
</html>
<?php


    if(!empty($_GET))
    {

        echo $_GET['data'];
        $data = DateTime::createFromFormat('Y-m', $_GET['data']);
        
        if(!$data || $data->format('Y-m') != $_GET['data']){
            echo '<br><br> não é data';
        }
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="form_date.php" method="GET" id="form_data">
        <input id="bday-month" name="data" type="month" name="bday-month" value="<?= date('Y-m') ?>" onchange="handler();">
    </form>

    <script>
        const formData = document.querySelector('#form_data');

        function handler()
        {
            formData.submit();
        }
    </script>
</body>
</html>
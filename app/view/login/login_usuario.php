<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= ASSET_URL ?>/favicons/dinheiro.png">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Roboto:ital,wght@1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= ASSET_CSS_URL ?>/style.css">
<link rel="stylesheet" href="<?= ASSET_URL ?>/font-awesome-4.7.0/css/font-awesome.min.css">

</head>
<style>
    *{padding: 0; margin: 0; box-sizing: border-box; font-family: 'Lato', sans-serif;}

    .container-login{
        width: 100%;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #121E2D;
    }

    .conteudo-login{
        border: 1px solid #0484cf;
        width: 500px;
        /* height: 600px; */
        padding: 20px;
        background-color: white;
        border-radius: 25px;

    }

    #form-cadastro label,input[type="text"],input[type="email"],input[type="password"]{
        width: 100%;
        font-size: 1.1em;
        margin: 3px 0;
    }

    #btn-cadastro-usuario{
        width: 100%;
        padding: 10px;
        font-size: 1.2em;
        background-color: #00a400;
        font-weight: bold;
        border:none;
        color: white;
        letter-spacing: 1px;
    }

    #btn-cadastro-usuario:hover{
        background-color: #019101;
    }

    #form-cadastro input{
        padding: 10px 0 10px 5px;
        border: 1px solid #ccc;

    }

    #form-cadastro div{
        margin: 10px 0;
    }
</style>
<body>
    <div class="container-login">
        <div class="conteudo-login">
            <h1 style="text-align: center; color: #121E2D;">Login</h1>
            <form action="<?= HOME_URL ?>/" method="POST" id="form-cadastro">

                <div>
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" value="weslley@teste.com" required>
                </div>
                

                <div>
                    <label for="senha">Senha:</label>
                    <div class="tooltip" style="margin: 0;"> 
                           <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <span class="tooltiptext">
                            A sua senha deve conter 8 caracteres!
                        </span>
                    </div>
                    <input type="password" name="senha" id="senhaUsuario" value="123456ac" required>
                </div>

                <div style="display: flex; justify-content:space-between;">
                    <div>
                        <input type="checkbox" id="visuSenha">
                        <label for="visualizarSenha">Visualizar senha</label>
                    </div>
    
                    <div>
                        <a href="<?= HOME_URL ?>/alterarSenha" style="text-decoration: none; color: blue;">Esqueceu a senha?</a>
                    </div>
                </div>

                <button type="submit" id="btn-cadastro-usuario">Entrar</button>
            </form>
            <div style="margin-top: 20px;">
                 <a href="<?= HOME_URL?>/cadastro" style="text-decoration: none; text-align: center;display: block; font-size: 1.1em; color: blue;">Criar nova conta</a>
                 
            </div>
            <?= $msg ?>
        </div>
    </div>

    <script>
        const visuSenha = document.querySelector('#visuSenha');
        const senhaUsuario = document.querySelector('#senhaUsuario');


        visuSenha.onclick = () => {
            if(visuSenha.checked)
            {
                senhaUsuario.type = 'text';
               
            }
            else{
                senhaUsuario.type = 'password';

            }
        }


    </script>

    
</script>
        <script>
        const btnAlertClose = document.querySelectorAll('.btn-alert-close');

        for (let index = 0; index < btnAlertClose.length; index++) {

            btnAlertClose[index].onclick = () => {
                
                var parent = btnAlertClose[index].parentNode;
        
                parent.style.display = 'none';
            }        
        }
    </script>
</body>
</html>
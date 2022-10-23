<h3 class="title-form">Configurações</h3>

<style>

    #links-tab{
        display: flex;
        margin: 20px 0;
    }
    #links-tab a{
        display: block; 
        margin-right: 10px;
        text-decoration: none;
        font-size: 1.1em;
        color: black;
        border: 1px solid grey;
        padding: 5px 15px;
        background-color: lightgrey;
    }

    #links-tab a:hover{
       background-color: grey;
       border: 1px solid lightgrey;
    }
</style>


<div id="links-tab">
    <a href="<?= HOME_URL ?>/configuracoes">Meu cadastro</a>
    <a href="<?= HOME_URL ?>/configuracoes/alterarEmail">Alterar E-mail</a>
    <a href="<?= HOME_URL ?>/configuracoes/alterarSenha">Alterar Senha</a>
</div>


<style>
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
    padding: 10px 0px;
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
    padding: 10px 0 10px 5px !important;
    border: 1px solid #ccc;

}

#form-cadastro div{
    margin: 20px 0;
}
</style>
<div class="container-form">
    <h3 class="title-form">Alteração de senha</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/configuracoes/alterarSenha" method="POST" id="form-cadastro">
                <div>
                    <label for="senha">Senha atual:</label>
                    <input type="password" name="senhaAtual" id="senhaUsuarioAtual" required>
                </div>

                <div>
                    <label for="senha">Nova senha:</label>
                    <input type="password" name="senha" id="senhaUsuario" required>
                </div>
                
                <div>
                    <label for="confirmarSenha">Confirmar nova senha:</label>
                    <input type="password" name="confirmarSenha" id="confSenhaUsuario" required>
                </div>

                <div>
                    <input type="checkbox" id="visuSenha">
                    <label for="visualizarSenha">Visualizar senha:</label>
                </div>

                <button type="submit" id="btn-cadastro-usuario">Alterar</button>
    </form>
    <?= $msg ?>
</div>
<script>
        const visuSenha = document.querySelector('#visuSenha');
        const senhaUsuario = document.querySelector('#senhaUsuario');
        const confSenhaUsuario = document.querySelector('#confSenhaUsuario');
        const senhaUsuarioAtual = document.querySelector("#senhaUsuarioAtual");


        visuSenha.onclick = () => {
            if(visuSenha.checked)
            {
                senhaUsuario.type = 'text';
                confSenhaUsuario.type = "text";
                senhaUsuarioAtual.type= 'text';
            }
            else{
                senhaUsuario.type = 'password';
                confSenhaUsuario.type = 'password';
                senhaUsuarioAtual.type =  'password';
            }
        }


    </script>

    <script>
      var browserName = (function (agent) {        switch (true) {
            case agent.indexOf("edge") > -1: return "MS Edge";
            case agent.indexOf("edg/") > -1: return "Edge ( chromium based)";
            case agent.indexOf("opr") > -1 && !!window.opr: return "Opera";
            case agent.indexOf("chrome") > -1 && !!window.chrome: return "Chrome";
            case agent.indexOf("trident") > -1: return "MS IE";
            case agent.indexOf("firefox") > -1: return "Mozilla Firefox";
            case agent.indexOf("safari") > -1: return "Safari";
            default: return "other";
        }
    })(window.navigator.userAgent.toLowerCase());

    console.log(browserName);
    console.log(window.navigator.userAgent);
    </script>
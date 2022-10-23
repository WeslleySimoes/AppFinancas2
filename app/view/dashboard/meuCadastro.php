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
</style>


<div id="links-tab">
    <a href="<?= HOME_URL ?>/configuracoes">Meu cadastro</a>
    <a href="<?= HOME_URL ?>/configuracoes/alterarEmail">Alterar E-mail</a>
    <a href="<?= HOME_URL ?>/configuracoes/alterarSenha">Alterar Senha</a>
</div>
<div class="container-form">
    <h3 class="title-form">Meu cadastro</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/configuracoes" method="POST">
       
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required value="<?= $dadosUsuario->nome ?>">

        <label for="sobrenome">Sobrenome:</label>
        <input type="text" name="sobrenome" required  value="<?= $dadosUsuario->sobrenome ?>">

        <label for="dataNasc">Data Nascimento:</label>
        <input type="date" name="dataNasc" required style="border: 1px solid #ccc; margin-top: 6px; margin-bottom: 16px;" value="<?= $dadosUsuario->data_nasc ?>">

        <label for="sexo">Sexo:</label>
        <select name="sexo">
          <option value="masculino" <?= $dadosUsuario->sexo == 'masculino' ? 'selected' : '' ?>>Masculino</option>
          <option value="feminino" <?= $dadosUsuario->sexo == 'feminino' ? 'selected' : '' ?>>Feminino</option>
        </select>        
        <button type="submit" id="btn-cadastro-usuario">Alterar</button>
    </form>
    <?= $msg ?>
</div>
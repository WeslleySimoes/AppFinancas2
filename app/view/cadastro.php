<p style="margin-bottom: 20px;">
    <a href="#">Dashboard</a> / <a href="#">Biblioteca</a> / <a href="#">Dados</a>
</p>

<?= $id ?? ''?>

<div class="formularioUsuario">
    <h3>Cadastro de usuário</h3>
    <div class="containerForm">
       <form action="#" method="post" id="formCadastroUsuário">
           <label for="nome">Nome:</label>
           <input type="text" name="nome" id="nome" require>
           <label for="email">E-mail:</label>
           <input type="email" name="email" id="email" require>
           <label for="senha">Senha:</label>
           <input type="password" name="senha" id="senha">
           <button class="btn-success">Enviar</button>
       </form>
    </div>
</div>

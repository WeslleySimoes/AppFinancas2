<div class="container-form">
    <h3 class="title-form">Cadastro de categoria</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/categorias/cadastrar" method="POST">

        <label for="nomeCategoria">Nome da categoria:</label>
        <input type="text" name="nomeCategoria">

        <label for="tipoCategoria">Tipo de categoria:</label>
        <select name="tipoCategoria">
          <option value="despesa" selected>Despesa</option>
          <option value="receita">Receita</option>
        </select>        
        <button class="btn-success">Cadastrar</button>
    </form>
    <?= $msg ?>
</div>
<div class="container-form">
    <h3 class="title-form">Editar categoria</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/categorias/editar?id=<?= $_GET['id'] ?>" method="POST">

        <label for="nomeCategoria">Nome da categoria:</label>
        <input type="text" name="nomeCategoria" value="<?= $get_categoria->nome ?>">

        <label for="tipoCategoria">Tipo de categoria:</label>
        <select name="tipoCategoria">

          <option value="despesa" <?= $get_categoria->tipo == 'despesa' ? 'selected' : '' ?>>Despesa</option>
          <option value="receita"  <?= $get_categoria->tipo == 'receita' ? 'selected' : '' ?>>Receita</option>

        </select>        
        <div style="margin: 5px 0 10px 0;">
            <label for="body" style="display:block; margin: 5px 0;">Cor da categoria: </label>
            <input type="color" name="corCate" value="<?=   $get_categoria->cor_cate?>" style="display: block; width: 50px; height: 50px; border:none;">
        </div>

        <button  class="botao-link botao-link-edit" style="font-size: 1.1em;">Cadastrar</button>
    </form>
    <?= $msg ?>
</div>
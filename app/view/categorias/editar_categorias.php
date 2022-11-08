<div class="container-form">
    <h3 class="title-form">Editar categoria</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/categorias/editar?id=<?= $_GET['id'] ?>" method="POST">

        <label for="nomeCategoria">Nome da categoria:</label>
        <input type="text" name="nomeCategoria" value="<?= $get_categoria->nome ?>">
       
        <div style="margin: 5px 0 10px 0;">
            <label for="body" style="display:block; margin: 5px 0;">Cor da categoria: </label>
            <input type="color" name="corCate" value="<?=   $get_categoria->cor_cate?>" style="display: block; width: 50px; height: 50px; border:none;">
        </div>

        <button  class="botao-link botao-link-edit" style="font-size: 1.1em;">Alterar</button>
    </form>
    <?= $msg ?>
</div>
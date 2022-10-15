<div class="container-form">
    <h3 class="title-form">Editar de conta</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/contas/editar?id=<?= $_GET['id'] ?>" method="POST">
     
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" value="<?= $dados_conta->descricao ?>" required>

        <label for="instituicao">Instituição:</label>
        <input type="text" name="instituicao" value="<?= $dados_conta->instituicao_fin ?>" required>

        <label for="tipoConta">Tipo de conta:</label>
        <select name="tipoConta">

          <option value="Corrente" <?= $dados_conta->tipo_conta == 'Corrente' ? 'selected' : ''?>>Corrente</option>
          <option value="Poupança" <?= $dados_conta->tipo_conta == 'Poupança' ? 'selected' : ''?>>Poupança</option>
          <option value="Dinheiro" <?= $dados_conta->tipo_conta == 'Dinheiro' ? 'selected' : ''?>>Dinheiro</option>
          <option value="Outros" <?= $dados_conta->tipo_conta == '' ? 'selected' : ''?>>Outros</option>


        </select>        
        <button class="btn-success">Alterar</button>
    </form>
    <?= $msg ?>
</div>
<div class="container-form">
    <h3 class="title-form">Cadastro de conta</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/contas/cadastrar" method="POST">
        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor">

        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao">

        <label for="instituicao">Instituição:</label>
        <input type="text" name="instituicao">

        <label for="tipoConta">Tipo de conta:</label>
        <select name="tipoConta">
          <option value="Corrente" selected>Corrente</option>
          <option value="Poupança">Poupança</option>
          <option value="Dinheiro">Dinheiro</option>
          <option value="Outro">Outro</option>
        </select>        
        <button class="btn-success">Cadastrar</button>
    </form>
    <?= $msg ?>
</div>
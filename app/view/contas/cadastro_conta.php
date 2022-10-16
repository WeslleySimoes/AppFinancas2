<div class="container-form">
    <h3 class="title-form">Cadastro de conta</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/contas/cadastrar" method="POST">
        <label for="valor">Valor inicial (R$):</label>
        <input type="text" class="money" name="valor"  required>

        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" required>

        <label for="instituicao">Instituição:</label>
        <input type="text" name="instituicao" required>

        <label for="tipoConta">Tipo de conta:</label>
        <select name="tipoConta">
          <option value="Corrente" selected>Corrente</option>
          <option value="Poupança">Poupança</option>
          <option value="Dinheiro">Dinheiro</option>
          <option value="Outros">Outro</option>
        </select>        
        <button class="botao-link botao-link-edit" style="font-size: 1.1em;">Cadastrar</button>
    </form>
    <?= $msg ?>
</div>
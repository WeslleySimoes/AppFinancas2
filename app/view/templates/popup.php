
<div id="fundo-escuro-popup" class="conteudo-pop"> 
    <div class="conteudo-pop">
        <div id="conteudo-popup-form">
            <h1 style="margin-bottom: 10px;">Deseja mesmo remover esta receita?</h1>


            <?php if(isset($_GET['s']) and $_GET['s'] == 'receitasFixas'): ?>
                <form action="<?= HOME_URL ?>/receita/removerFP?id=" method="GET">
            <?php elseif(isset($_GET['s']) and $_GET['s'] == 'despesasFixas'): ?>
                <form action="<?= HOME_URL ?>/despesa/removerFP?id=" method="GET">
            <?php endif ?>
            
                <p>Por favor selecione uma das opções abaixo:</p>
                <br>
                <input type="hidden" name="idR" value="1" id="idR">
                
                <input type="radio" id="html" name="t" value="futuras" checked>
                <label for="html">Remover somente receitas Futuras</label><br>

                <input type="radio" id="css" name="t" value="todas">
                <label for="css">Remover todas as receitas</label><br>
                
                <br>
                
                <button class="fechar-popup">Remover</button>
                <span class="fechar-popup">Cancelar</span>
            </form>
        </div>
    </div>
</div>


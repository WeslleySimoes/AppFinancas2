
<div id="fundo-escuro-popup" class="conteudo-pop"> 
    <div class="conteudo-pop">
        <div id="conteudo-popup-form" style="height: auto;">
            <h1 style="margin-bottom: 10px;">Deseja mesmo remover esta <?= $_GET['s'] == 'receitasFixas'  ? 'receita' : 'despesa' ?></h1>


            <?php if(isset($_GET['s']) and $_GET['s'] == 'receitasFixas'): ?>
                <form action="<?= HOME_URL ?>/receita/removerFP" method="GET">
            <?php elseif(isset($_GET['s']) and $_GET['s'] == 'despesasFixas'): ?>
                <form action="<?= HOME_URL ?>/despesa/removerFP" method="GET">
            <?php endif ?>
            
                <p>Por favor selecione uma das opções abaixo:</p>
                <br>
                <input type="hidden" name="idR" value="1" id="idR">
                
                <input type="radio" id="html" name="t" value="futuras" checked>
                <label for="html">Remover somente <?= $_GET['s'] == 'receitasFixas'  ? 'receitas' : 'despesas' ?> Futuras</label><br>

                <input type="radio" id="css" name="t" value="todas">
                <label for="css">Remover todas as <?= $_GET['s'] == 'receitasFixas'  ? 'receitas' : 'despesas' ?></label><br>
                
                <br>
                
                <button class="fechar-popup dasdasas btn-dp-fix-p">Remover</button>
                <span class="fechar-popup  btn-dp-fix-p" >Cancelar</span>
            </form>
        </div>
    </div>
</div>


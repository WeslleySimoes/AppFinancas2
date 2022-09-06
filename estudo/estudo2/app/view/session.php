<div class="container">
    <h1>Sejam Bem Vindo, <?= $usuarioLogado ?>!</h1>
    
    <?php if(!empty($anotacoes)):?>
        <?php foreach($anotacoes as $anotacao): ?>
    
            <!-- Modifique abaixo desta linha -->
            <article>
                <h2><?= $anotacao->titulo ?></h2>
                <p><?= $anotacao->conteudo ?></p>
            </article>
            <hr>
            <!-- Modifique acima desta linha -->
    
        <?php endforeach; ?>
    <?php else: ?>
    
        <!-- Modifique abaixo desta linha -->
        <p>Nenhuma anotação encontrada!</p>
        <!-- Modifique acima desta linha -->
    
    <?php endif; ?>
</div>

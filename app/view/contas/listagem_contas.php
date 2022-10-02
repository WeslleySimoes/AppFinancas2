<!-- <h1 style="font-size: 1.5rem!important;">Listagem contas</h1> -->
<h3 style="margin-bottom: 20px; color: #263D52;">Contas</h3>

<a href="<?= HOME_URL?>/contas/cadastrar" class="btn btn-primary" >Nova Conta</a>

<style>
    .contaUsuario{
        padding: 10px 0;
    }
</style>

<div class="row">

<?php if(!empty($contas_usuario)):?>
    <?php foreach($contas_usuario as $conta): ?>
        <div class="col-sm-6" style="margin-top: 20px;">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $conta->instituicao_fin ?></h5>
                <p class="card-text">
                    <p>
                    Montante: R$ <?= formatoMoeda($conta->montante) ?><br>
                    Descrição: <?= $conta->descricao ?><br>
                    Tipo de Conta: <?= $conta->tipo_conta ?><br>
                    </p>
                </p>
                <a href="<?= HOME_URL?>/contas/editar?id=<?= $conta->idConta ?>" class="btn btn-primary">Editar</a>
                <a href="<?= HOME_URL?>/contas/remover?id=<?= $conta->idConta ?>" class="btn btn-danger">Remover</a>
            </div>
            </div>
        </div>
        <?php endforeach; ?>
<?php else: ?>
    <p>Nenhuma conta Cadastrada!</p>
<?php endif; ?>
</div>

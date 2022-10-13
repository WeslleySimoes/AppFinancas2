<!-- <h1 style="font-size: 1.5rem!important;">Listagem contas</h1> -->
<h3 style="margin-bottom: 20px; color: #263D52;">Contas</h3>

<a href="<?= HOME_URL?>/contas/cadastrar" class="btn btn-primary" >Nova Conta</a>

<style>
    .contaUsuario{
        padding: 10px 0;
    }
    .card{
        /* border: 1px solid black;  */
        padding: 20px; 
        width: calc(33.33% - 20px);
        margin: 20px 20px 0 0;
        border: 1px solid lightgrey;
        border-radius: 15px;
        -webkit-box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38); 
        box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38);
    }

    .contas-content{
        display: flex;
        flex-wrap: wrap;
    }
</style>

<div class="contas-content">
<?php if(!empty($contas_usuario)):?>
    <?php foreach($contas_usuario as $conta): ?>
        <div class="card">
            <div class="card-body">
                <h1 style="margin-bottom: 5px;font-size: 1.1em"><?= $conta->instituicao_fin ?></h1>
                <hr>
                <div style="margin-top: 10px;">
                    <div style="display: flex; justify-content:space-between;">
                        <div> <b>Saldo atual: </b></div>
                        <div>R$ <?= formatoMoeda($conta->montante) ?></div>
                    </div>
                    <div style="display: flex; justify-content:space-between;">
                        <div> <b>Descrição: </b></div>
                        <div> <?= $conta->descricao ?></div>
                    </div>
                    <div style="display: flex; justify-content:space-between;">
                        <div>  <b>Tipo:</b> </div>
                        <div> <?= $conta->tipo_conta ?></div>
                    </div>
                </div>
                <div  style="margin-top: 10px;">
                    <a href="<?= HOME_URL?>/contas/editar?id=<?= $conta->idConta ?>" class="btn btn-primary">Editar</a>
                    <a href="<?= HOME_URL?>/transacoes?conta=<?= $conta->idConta ?>" class="btn btn-primary">Transações</a>
                    <a href="<?= HOME_URL?>/contas/remover?id=<?= $conta->idConta ?>" class="btn btn-danger">Arquivar</a>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
<?php else: ?>
    <p>Nenhuma conta Cadastrada!</p>
<?php endif; ?>
</div>

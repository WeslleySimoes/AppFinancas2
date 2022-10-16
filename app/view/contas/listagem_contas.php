
<?= $msg ?>

<!-- <h1 style="font-size: 1.5rem!important;">Listagem contas</h1> -->
<h3 style="margin-bottom: 20px; color: #263D52;">
    <?php 
        if(isset($_GET['status']))
        {
            if($_GET['status'] == 'arquivado')
            {
                echo 'Contas arquivadas';
            }
            else{
                echo 'Contas ativas';
            }
        }
        else{
            echo 'Contas ativas';
        }
        ?>
</h3>


<div>

    <a href="<?= HOME_URL?>/contas/cadastrar" class="dropbtn" style="text-decoration: none;">Nova Conta</a>

    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn">Status</button>
        <div id="myDropdown" class="dropdown-content">
        <a href="<?= HOME_URL ?>/contas/listar<?= isset($_GET['data']) ? '?data='.$_GET['data'] : '' ?>">Ativas</a>
        <a href="<?= HOME_URL ?>/contas/listar?status=arquivado<?= isset($_GET['data']) ? '&data='.$_GET['data'] : '' ?>">Arquivadas</a>
      </div>
    </div>
</div>
<div style="display: flex; justify-content: center; margin-bottom: 20px;">
    <form action="<?= HOME_URL ?>/contas/listar" id="form_data" method="GET">
        <input type="month" name="data" onchange="handler();" value="<?= !isset($_GET['data']) ? date('Y-m') :  date($_GET['data']) ?>"  style="border: 2px solid #0476B9; padding: 10px 5px; font-size: 0.9em; border-radius: 10px; font-weight: bold; color: #0476B9;">
    </form>
</div>
<style>
    .contaUsuario{
        padding: 10px 0;
    }
    .card{
        /* border: 1px solid black;  */
        padding: 20px; 
        width: calc(50% - 20px);
        margin: 20px 20px 0 0;
        border: 1px solid #0476B9;
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

    <?php if(isset($_GET['status']) and $_GET['status'] == 'arquivado'): ?>
        <?php foreach($contas_usuario as $conta): ?>
            <?php $saldoContaAtual = $conta->getSaldoAtual($data_filtro_mes ?? null); ?>
            <div class="card">
                <div class="card-body">
                    <h1 style="margin-bottom: 5px;font-size: 1.1em"><?= $conta->instituicao_fin ?></h1>
                    <hr>
                    <div style="margin-top: 10px;">
                        <div style="display: flex; justify-content:space-between;">
                            <div> <b>Saldo atual: </b></div>
                            <div style="color:<?= $saldoContaAtual > 0 ? 'green' : 'red' ?>; font-weight: bold;">R$ <?= formatoMoeda($saldoContaAtual) ?></div>
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
                        <a href="<?= HOME_URL?>/transacoes?conta=<?= $conta->idConta ?>" class="botao-link botao-link-transacoes">Transações</a>
                        <a href="<?= HOME_URL?>/contas/desarquivar?id=<?= $conta->idConta ?>" class="botao-link botao-link-arquivar" onclick="return confirm('Tem certeza que deseja desarquivar esta conta?');">Desarquivar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach($contas_usuario as $conta): ?>
            <?php $saldoContaAtual = $conta->getSaldoAtual($data_filtro_mes ?? null); ?>
            <div class="card">
                <div class="card-body">
                    <h1 style="margin-bottom: 5px;font-size: 1.1em"><?= $conta->instituicao_fin ?></h1>
                    <hr>
                    <div style="margin: 10px 0;">
                        <div style="display: flex; justify-content:space-between;">
                            <div> <b>Saldo atual: </b></div>
                            <div style="color:<?= $saldoContaAtual > 0 ? 'green' : 'red' ?>; font-weight: bold;">R$ <?= formatoMoeda($saldoContaAtual) ?></div>
                        </div>
                        <div style="display: flex; justify-content:space-between;">
                            <div> <b>Descrição: </b></div>
                            <div> <?= $conta->descricao?></div>
                        </div>
                        <div style="display: flex; justify-content:space-between;">
                            <div>  <b>Tipo:</b> </div>
                            <div> <?= $conta->tipo_conta ?></div>
                        </div>
                    </div>
                    <hr>
                    <div  style="margin-top: 15px;">
                            
                        <a href="<?= HOME_URL?>/contas/editar?id=<?= $conta->idConta ?>" class="botao-link botao-link-edit" style="display:inline-block; margin-top: 5px;">Editar</a>
                        <a href="<?= HOME_URL?>/transacoes?conta=<?= $conta->idConta ?>" class="botao-link botao-link-transacoes" style="display:inline-block; margin-top: 5px;">Transações</a>
                        <a href="<?= HOME_URL?>/contas/arquivar?id=<?= $conta->idConta ?>" onclick="return confirm('Tem certeza que deseja arquivar esta conta?');" class="botao-link botao-link-arquivar" style="display:inline-block; margin-top: 5px;">Arquivar</a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <p>Nenhuma conta encontrada!</p>
<?php endif; ?>
</div>


<script>
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    const formData = document.querySelector('#form_data');

    function handler()
    {
        formData.submit();
    }
</script>
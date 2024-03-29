<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= ASSET_URL ?>/favicons/dinheiro.png">
    <title>Finanças Pessoais</title>
    <link rel="stylesheet" href="<?= ASSET_CSS_URL ?>/style_dashboard.css">
    <link rel="stylesheet" href="<?= ASSET_CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= ASSET_URL ?>/font-awesome-4.7.0/css/font-awesome.min.css">

<!-- 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;1,300;1,400&display=swap" rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Roboto:ital,wght@1,500&display=swap" rel="stylesheet">


    <!-- <script src="https://cdnjs.com/libraries/Chart.js"></script> -->
    <script src="<?= ASSET_JS_URL ?>/jquery.min.js"></script>
    <script src="<?= ASSET_JS_URL ?>/chart.js/package/dist/chart.min.js"></script>
    <script src="<?= ASSET_JS_URL ?>/chartjs-plugin-datalabels@2.0.0.js"></script>
    
<body>
    <!-- MENU LATERAL -->
    <div id="menuLateral">
        <a href="<?= HOME_URL.'/' ?>" id="logo_empresa">
            <i class="fa fa-usd" aria-hidden="true"></i>
            <b class="link_texto_menu_lateral">FINANÇAS PESSOAIS</b>
        </a>
        <ul id="menu_links_lateral">
            <li> 
                <a href="<?= HOME_URL.'/' ?>" class="link-menu-lateral">
                    <i class="fa fa-home" aria-hidden="true"></i> 
                    Dashboard
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL.'/contas/listar' ?>" class="link-menu-lateral">
                    <i class="fa fa-university" aria-hidden="true"></i> 
                    Contas
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL.'/transacoes' ?>" class="link-menu-lateral">
                <i class="fa fa-credit-card" aria-hidden="true"></i>
                    Transações
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL.'/categorias' ?>" class="link-menu-lateral">
                    <i class="fa fa-tag" aria-hidden="true"></i>
                    Categorias
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL.'/relatorios' ?>" class="link-menu-lateral">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i> 
                    Relatórios
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL.'/planejamento' ?>" class="link-menu-lateral">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                    Planejamento
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL ?>/calculadoras" class="link-menu-lateral">
                <i class="fa fa-calculator" aria-hidden="true"></i>
                    Calculadoras
                </a>
            </li> 
            <li> 
                <a href="<?= HOME_URL ?>/configuracoes" class="link-menu-lateral">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    Configurações
                </a>
            </li> 
        </ul>
    </div>
    <!-- FIM DO MENU LATERAL -->


    <!-- MENU SUPERIOR -->
    <div id="menuSuperior">
        <button id="btnMenuToggle"><i class="fa fa-bars" aria-hidden="true"></i></button>
        <ul id="menuUsuario">
            <li><i class="fa fa-user" aria-hidden="true"></i>&nbsp;
            <?= ucfirst($usuario_logado) ?> 
                <ul>
                    <li><a href="<?= HOME_URL ?>/configuracoes"><i class="fa fa-cog" aria-hidden="true"></i> Configurações</a></li>
                    <li><a href="<?= HOME_URL.'/deslogar' ?>"><i class="fa fa-sign-out" aria-hidden="true"></i> Sair</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="exemplo">   
    
        <a href="<?= HOME_URL.'/'.$nomepagina[0] ?>" class='exemplo-titulo-pag'><?= $nomepagina[1] ?></a>
        <div>
            <?php $totalLinkCaminhoPagina = count($link_caminho_pagina);$contaItemPagAtual = 0; foreach( $link_caminho_pagina as $linkPagAtual ): ?>

                <a href="<?= HOME_URL.'/'.$linkPagAtual[0] ?>" class='exemplo-link-pag'> <?= $linkPagAtual[1]  ?></a>

                <?php $contaItemPagAtual++ ?>

                <?php if($contaItemPagAtual != $totalLinkCaminhoPagina ): ?>
                    /
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>
    <!-- <div style="margin: 45px 0 0 200px; background-color:#EAEDEA; position:fixed; top:0; width: 100%; padding: 5px 20px;">
        Dashboard
    </div> -->
    <!-- FIM DO MENU SUPERIOR -->


    <!-- CONTEUDO DA DASHBOARD -->
    <div class="conteudo">
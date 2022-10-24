<style>
    #links-calc a{
        display: flex;
        text-decoration: none;
        border: 1px solid #0476B9;
        height: 50px;
        justify-content: center;
        align-items: center;
        font-size: 1.2em;
        color:white;
        margin: 20px 0;
        background-color: #0476B9;
    }

    #links-calc a:hover{
        background-color: #1d83c0;
        border: 1px solid  #1d83c0;

    }
</style>

<h3>Lista de calculadoras</h3>

<div id="links-calc">
    <a href="<?= HOME_URL ?>/calculadoras?t=jurosCompostos">Calculadora de juros compostos</a>
    <a href="<?= HOME_URL ?>/calculadoras?t=jurosSimples">Calculadora de juros simples</a>
    <a href="<?= HOME_URL ?>/calculadoras?t=converterMoeda">Conversor de Moedas</a>
</div>

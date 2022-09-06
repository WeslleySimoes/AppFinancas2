<!-- INÍCIO - TITULO DA PÁGINA -->
<h3 style="margin-bottom: 20px; color: #263D52;">
    <?php if(isset($_GET['p']) == 'personalizado'): ?>
        Planejamento Personalizado
    <?php else:  ?>
        Planejamento Mensal
    <?php endif; ?>
</h3>
<!-- FIM - TITULO DA PÁGINA -->

<!-- INÍCIO - LINKS DA PÁGINA -->
<div class="dropdown">
    <button onclick="myFunction()" class="dropbtn"> <span style='font-size:16px;'>&#9660;</span>Planejamento mensal</button>
    <div id="myDropdown" class="dropdown-content">
    <a href="<?= HOME_URL ?>/planejamento">Planejamento mensal</a>
    <a href="<?= HOME_URL ?>/planejamento?p=personalizado">Planejamento personalizado</a>
</div>
<!-- FIM - LINKS DA PÁGINA -->

<!-- INÍCIO - MOSTRA OS PLANEJAMENTOS PERSONALIZADOS -->
<?php if(isset($_GET['p']) == 'personalizado'): ?>
    <p style="margin: 20px 0;"><b>Personalizado</b></p>

<!-- FIM - MOSTRA OS PLANEJAMENTOS PERSONALIZADOS  -->
<!-- --------------------------------------------- -->
<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL   -->
<?php else:  ?>


    <p style="margin: 20px 0;"><b>Mensal</b></p>


<?php endif; ?>
<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL  -->


<!-- INÍCIO - SCRIPT JAVASCRIPT -->
<script>
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
</script>
<!-- FIM - SCRIPT JAVASCRIPT -->
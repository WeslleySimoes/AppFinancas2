</div>
    <!-- FIM DO CONTEUDO DA DASHBOARD -->

    <!-- JAVASCRIPT -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <script>
        const btnToggleMenu = document.getElementById('btnMenuToggle');
        const menuLateral   = document.getElementById('menuLateral');
        const menuSuperior  = document.getElementById('menuSuperior');
        const conteudo      = document.querySelector(".conteudo");

        function resetarClasses(valor){
            if(valor == true)
            {
                menuLateral.classList.remove('moverMenuLateral');
                menuSuperior.classList.remove('marginLeftMS');
                conteudo.classList.remove('marginLeftMS');
                menuSuperior.classList.remove('larguraMenuSuperior');
            }
            else{
                menuLateral.classList.remove('moverMenuLateral2');
                menuSuperior.classList.remove('marginLeftMS2');
                conteudo.classList.remove('marginLeftMS2');
                menuSuperior.classList.remove('larguraMenuSuperior2');
            }
        }

        btnToggleMenu.onclick = () => {

            if(window.innerWidth >= 700)
            {
                resetarClasses(false);
                menuLateral.classList.toggle('moverMenuLateral');
                menuSuperior.classList.toggle('marginLeftMS');
                conteudo.classList.toggle('marginLeftMS');
                menuSuperior.classList.toggle('larguraMenuSuperior');
            }
            else{
                resetarClasses(true);
                menuLateral.classList.toggle('moverMenuLateral2');
                menuSuperior.classList.toggle('marginLeftMS2');
                conteudo.classList.toggle('marginLeftMS2');
                menuSuperior.classList.toggle('larguraMenuSuperior2');
            }
        }
    </script>
    <!-- FIM DO JAVASCRIPT -->


    <script type="text/javascript">
		$(document).ready(function(){
		    $('.date').mask('00/00/0000');
		    $('.time').mask('00:00:00');
		    $('.cep').mask('00000-000');
		    $('.phone').mask('(00) 0000-0000');
		    $('.celular').mask('(00) 00000-0000');
		    $('.cpf').mask('000.000.000-00');
		    $('.money').mask('000.000.000.000.000,00', {reverse: true});
		});
	</script>

	<script type="text/javascript" src="<?= ASSET_JS_URL ?>/jquery.mask.min.js"></script>


    <script>
        const btnAlertClose = document.querySelectorAll('.btn-alert-close');

        for (let index = 0; index < btnAlertClose.length; index++) {

            btnAlertClose[index].onclick = () => {
                
                var parent = btnAlertClose[index].parentNode;
        
                parent.style.display = 'none';
            }        
        }
    </script>
</body>
</html>
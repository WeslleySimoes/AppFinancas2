<div class="container">
    <h1>Página Sobre</h1>

    <article>
    	<h2>Titulo</h2>
    	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    	<a href="#">Leia mais...</a>
    </article>

    <form action="<?= HOME_URL ?>/sobre" method="GET">
    	<label for="search">
    		<input type="text" name="search" id="Search">
    	</label><br>
    	<button type="submit">Enviar</button>
    </form>

    <form action="<?= HOME_URL ?>/sobre" method="POST">  
    Valor em R$: <input type="text" name="valor" placeholder="00,00" onKeyPress="return(moeda(this,'.',',',event))">  <button type="submit">Enviar</button>
    </form> 
</div>


<script type="text/javascript" src="<?= ASSET_JS_URL ?>/script.js"></script>
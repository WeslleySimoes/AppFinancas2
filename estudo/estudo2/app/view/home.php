<main>
	<div class="container">

		<h1>Minhas viagens!</h1>

		<?php if(!empty($artigos)): ?>
			<?php foreach ($artigos as $artigo): ?>

				<article>
					<h2><?= $artigo['titulo_artigo'] ?></h2>
					<p><?= $artigo['conteudo_artigo'] ?></p>

					<a href="./sobre">Sobre</a>
				</article>
				<hr>

			<?php endforeach;?>
		<?php else: ?>
			
			<span>Nenhum artigo encontrado!</span>

		<?php endif; ?>

	</div>
</main>

<a href="./session">Area de Membros</a>

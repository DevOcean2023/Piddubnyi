<section class="site-map">
	<div class="inner">
		<?php dov_the( 'title', 'site-map__title' ); ?>
		<?php
		dov_the(
			'menu',
			array( 'bam_block_name' => 'menu-site-map' )
		);
		?>
	</div>
</section>

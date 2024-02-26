<section class="trusted">
	<div class="inner">
		<?php dov_the( 'title', 'trusted__title' ); ?>
		<?php while ( dov_loop( 'items', '<div class="marquee" data-gap="88">' ) ) : ?>
			<div class="trusted__item">
				<?php while ( dov_wrap( 'link' ) ) : ?>
					<?php dov_the( 'image', '193x0' ); ?>
				<?php endwhile; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>

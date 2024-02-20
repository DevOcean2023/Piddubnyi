<section class="default-pages">
	<div class="inner">
		<?php if ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php the_content(); ?>
		<?php endif; ?>
	</div>
</section>

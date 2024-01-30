<section class="default-page">
	<div class="inner">
		<?php if ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php DOV_BAM_Content::the_content( 'default-page' ); ?>
		<?php endif; ?>
	</div>
</section>

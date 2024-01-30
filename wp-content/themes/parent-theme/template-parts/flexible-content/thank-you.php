<section class="thank-you">
	<div class="inner">
		<?php if ( dov_has( 'title', 'text' ) ) : ?>
			<div class="thank-you__wrapper">
				<?php dov_the( 'title', 'thank-you__title' ); ?>
				<?php dov_the( 'text' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( dov_has( 'social_links_enabled', 'dov_social_links' ) ) : ?>
			<div class="thank-you__wrapper-social-links">
				<?php dov_the( 'social_links_title', 'thank-you__subtitle' ); ?>
				<?php get_template_part( 'template-parts/social-links', null, array( 'class' => 'social-links_color' ) ); ?>
			</div>
		<?php endif; ?>
		<?php while ( dov_loop( 'buttons', '<div class="thank-you__wrapper-buttons">' ) ) : ?>
			<?php dov_the( 'button', 'thank-you__button' ); ?>
		<?php endwhile; ?>
	</div>
</section>

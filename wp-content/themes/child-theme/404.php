<?php get_header(); ?>
<section class="section-404">
	<div class="inner">
		<div class="content-holder">
			<?php dov_the( 'dov_404_image', '<figure class="img-holder">' ); ?>
			<?php dov_the( 'dov_404_title'); ?>
			<?php dov_the( 'dov_404_text', '<div class="text-holder">' ); ?>
			<?php dov_the( 'dov_404_button' ); ?>

			<?php while ( dov_loop( 'dov_404_buttons', '<div class="buttons-holder">' ) ) : ?>
				<?php dov_the( 'button', 'btn', false ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>

<?php get_header(); ?>
<?php while ( dov_loop( 'dov_preloader', '<section class="preloader"><div class="inner">%s</div></section>' ) ) : ?>
	<?php dov_the( 'logo_devocean', '110x0' ); ?>
	<?php dov_the( 'title', 'title' ); ?>
	<?php dov_the( 'text' ); ?>
	<lottie-player src="lottie/animation.json" background="transparent" speed="1" loop
				   autoplay></lottie-player>
	<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
	<?php dov_the( 'logo_client_company', '175x0' ); ?>
	<?php dov_the( 'text_under_logo', '<p class="text">' ); ?>
<?php endwhile; ?>
<?php
get_footer();

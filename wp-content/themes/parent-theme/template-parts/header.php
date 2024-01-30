<header class="<?php echo esc_attr( dov_get_the_header_classes() ); ?>">
	<div class="page-header__wrapper">
		<?php get_template_part( 'template-parts/header-content' ); ?>
		<button class="open-mobile-menu-button" aria-controls="mobile-menu" aria-expanded="false">
			<span class='open-mobile-menu-button__item open-mobile-menu-button__item_style-1'></span>
			<span class='open-mobile-menu-button__item open-mobile-menu-button__item_style-2'></span>
			<span class='open-mobile-menu-button__item open-mobile-menu-button__item_style-3'></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'theme' ); ?></span>
		</button>
	</div>
</header>

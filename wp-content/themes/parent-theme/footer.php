<?php
echo '</main>';
$preloader_active = get_field( 'dov_preloader_active', 'options' );
if ( ! $preloader_active || is_user_logged_in() ) {
	get_template_part( 'template-parts/footer' );
	get_template_part( 'template-parts/mobile-menu' );
}
wp_footer();

echo '</body></html>';

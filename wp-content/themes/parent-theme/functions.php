<?php
if ( ! class_exists( 'DOV_Theme' ) ) {
	locate_template( 'classes/base/class-dov-theme-base.php', true );
	locate_template( 'classes/class-dov-theme.php', true );
	DOV_Theme::init();
}

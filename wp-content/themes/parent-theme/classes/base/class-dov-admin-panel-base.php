<?php

class DOV_Admin_Panel_Base {
	public static function init() : void {
		add_action(
			'admin_menu',
			array( static::class, 'hide_admin_pages' ),
			999,
			0
		);
		add_action(
			'admin_head',
			array( static::class, 'hide_customize' )
		);
	}

	public static function hide_admin_pages() : void {
		if ( apply_filters( 'dov_hide_comments', true ) ) {
			remove_menu_page( 'edit-comments.php' );
			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		}
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
		remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
		remove_submenu_page( 'tools.php', 'tools.php' );
		remove_submenu_page( 'options-general.php', 'options-media.php' );
	}

	public static function hide_customize() : void {
		echo '<style>.hide-if-no-customize { display: none !important; }</style>';
	}
}

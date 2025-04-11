<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

class DOV_Mega_Menu_Walker_Main extends DOV_Walker_Nav_Menu {
	private bool|WP_Post $mega_menu_item = false;
	private string $mega_menu_content    = '';

	/** @noinspection PhpUndefinedFieldInspection */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
		if ( $depth > 0 ) {
			$image_id = get_field( 'image', $data_object, false );

			if ( $image_id ) {
				$image     = wp_get_attachment_image(
					$image_id,
					'32x0',
					false,
					array(
						'class'   => 'menu-header-main__img',
						'loading' => 'lazy',
					),
				);
				$set_image = static function ( $title ) use ( $image ) {
					return '<span>' . $image . '</span>' . $title;
				};
			}
		}

		if ( isset( $set_image ) ) {
			add_filter( 'nav_menu_item_title', $set_image );
		}
		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );
		if ( isset( $set_image ) ) {
			remove_filter( 'nav_menu_item_title', $set_image );
		}
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ): void {
		if ( ! $this->mega_menu_item ) {
			parent::end_el( $output, $data_object, $depth, $args );
		}
	}
}

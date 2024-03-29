<?php

class DOV_ACF_Add_Field_Helper_Base {
	public static function init() : void {
		add_action( 'save_post', array( static::class, 'save' ), 5, 2 );
		add_action( 'acf/field_group/admin_enqueue_scripts', array( static::class, 'enqueue' ) );
	}

	public static function save( int $post_id, WP_Post $post ) : int {
		if (
			empty( $post->post_title ) ||
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
			'acf-field-group' !== $post->post_type ||
			wp_is_post_revision( $post_id )
		) {
			return $post_id;
		}

		if ( 0 === strncmp( $post->post_title, 'FC:', 3 ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification
			$_POST['post_title'] = static::format_label( $post->post_title );
		}

		$acf_fields = $_POST['acf_fields'] ?? ''; // phpcs:ignore WordPress.Security.NonceVerification

		if ( $acf_fields ) {
			foreach ( (array) $acf_fields as $i => $field ) {
				if ( ! empty( $field['label'] ) ) {
					$_POST['acf_fields'][ $i ]['label'] = static::format_label( $field['label'] );
				}
				if ( ! empty( $field['name'] ) ) {
					$_POST['acf_fields'][ $i ]['name'] = static::format_name( $field['name'] );
				}
				if ( ! empty( $field['layouts'] ) ) {
					foreach ( (array) $field['layouts'] as $i2 => $layout ) {
						if ( ! empty( $layout['label'] ) ) {
							$_POST['acf_fields'][ $i ]['layouts'][ $i2 ]['label'] =
								static::format_label( $layout['label'] );
						}
						if ( ! empty( $layout['name'] ) ) {
							$_POST['acf_fields'][ $i ]['layouts'][ $i2 ]['name'] =
								static::format_name( $layout['name'] );
						}
					}
				}
			}
		}

		return $post_id;
	}

	public static function format_label( ?string $label ) : string {
		return str_replace(
			array( ' On ' ),
			array( ' on ' ),
			ucwords(
				str_replace(
					array( '.php', 'php', '_', '--', '-' ),
					array( '', '', ' ', ' & ', ' ' ),
					$label
				)
			)
		);
	}

	public static function format_name( ?string $name ) : string {
		return mb_strtolower(
			str_replace(
				array( '.php', 'php', ' ' ),
				array( '', '', '_' ),
				$name
			)
		);
	}

	public static function enqueue() : void {
		wp_enqueue_script(
			'dov-acf-add-field-helper',
			DOV_File::get_parent_url( 'js/dov-acf-add-field-helper.js' ),
			array(),
			DOV_Theme::get_version(),
			true
		);
	}
}

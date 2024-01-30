<?php

class DOV_Enqueue_Scripts_Base extends DOV_Enqueue {
	protected static array $blocks         = array();
	protected static array $module_handles = array();

	public static function init() : void {
		parent::init();

		add_filter(
			'script_loader_tag',
			array( static::class, 'insert_attributes' ),
			10,
			2
		);
		add_filter(
			'wp_inline_script_attributes',
			array( static::class, 'insert_inline_attributes' )
		);
		add_filter(
			'script_loader_tag',
			array( static::class, 'insert_module_type' ),
			10,
			2
		);
		add_action(
			'wp_enqueue_scripts',
			array( static::class, 'jquery_to_footer' )
		);
		add_action(
			'wp_footer',
			array( static::class, 'jquery_top_footer' ),
			PHP_INT_MIN
		);
		add_filter(
			'gform_get_form_filter',
			array( static::class, 'remove_type_javascript_from_gf' )
		);
	}

	public static function insert_attributes( string $tag, string $handle ) : string {
		if ( isset( static::$attributes[ $handle ] ) ) {
			foreach ( static::$attributes[ $handle ] as $attribute => $value ) {
				$tag = str_replace( ' src', ' ' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '" src', $tag );
			}
		}

		return $tag;
	}

	public static function insert_inline_attributes( array $attributes ) : array {
		if ( isset( $attributes['id'], static::$attributes[ $attributes['id'] ] ) ) {
			return array_merge( $attributes, static::$attributes[ $attributes['id'] ] );
		}

		return $attributes;
	}

	public static function enqueue_blocks( array $blocks = array() ) : void {
		static::$blocks = array_merge( static::$blocks, $blocks );
	}

	public static function enqueue_base() : void {
		$handle = static::enqueue_file( 'main.js' );
		if ( $handle ) {
			DOV_Defer_Scripts::add( $handle );
			wp_localize_script(
				$handle,
				'theme',
				apply_filters(
					'dov_enqueue_get_theme_object',
					array(
						'url'       => DOV_File::get_assets_url(),
						'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
						'ajaxNonce' => wp_create_nonce( 'ajax-nonce' ),
					)
				)
			);

			wp_localize_script(
				$handle,
				'theme_i18n',
				apply_filters(
					'dov_enqueue_get_theme_i18n_object',
					array(
						'more' => __( 'View More', 'theme' ),
						'less' => __( 'View Less', 'theme' ),
					)
				)
			);
		}

		if ( DOV_Is::flex_page() ) {
			$need_scripts = array();
			while ( have_rows( 'content' ) ) {
				the_row();
				$layout = get_row_layout();

				if ( isset( static::$blocks[ $layout ] ) ) {
					foreach ( static::$blocks[ $layout ] as $file_name_or_key => $file_name_or_deps ) {
						if ( is_string( $file_name_or_key ) ) {
							$file_name = $file_name_or_key;
							$deps      = $file_name_or_deps;
						} else {
							$file_name = $file_name_or_deps;
							$deps      = array();
						}

						$need_scripts[ $file_name ] = $deps;
					}
				}
			}

			foreach ( $need_scripts as $file_name => $deps ) {
				self::enqueue_module( $file_name, $deps );
			}
		}

		if ( DOV_Theme::never() ) { // The condition is never fulfilled, only for IDE
			?>
			<script>
				window.theme = { url: '', ajaxUrl: '', ajaxNonce: '' };
				window.theme_i18n = { more: '', less: '' };
			</script>
			<?php
		}
	}

	public static function enqueue_module( $file_name, $deps ) : void {
		$path = DOV_File::get_assets_path( 'js/modules/' . $file_name );

		if ( DOV_Filesystem::exists( $path ) ) {
			$handle  = static::get_handle( $file_name );
			$version = static::get_version( $path );
			$url     = DOV_File::get_assets_url( 'js/modules/' . $file_name );

			static::$module_handles[ $handle ] = true;

			wp_enqueue_script(
				$handle,
				$url,
				$deps,
				$version,
				true
			);
		}
	}

	public static function insert_module_type( string $tag, string $handle ) : string {
		if ( isset( static::$module_handles[ $handle ] ) && static::$module_handles[ $handle ] ) {
			return str_replace( ' src', ' type="module" src', $tag );
		}

		return $tag;
	}

	public static function get_handle( string $file_name, string $prefix = 'theme', string $suffix = '.js' ) : string {
		return $prefix . '-' . basename( $file_name, $suffix );
	}

	public static function jquery_to_footer() : void {
		$file    = 'js/jquery.js';
		$path    = DOV_File::get_assets_path( $file );
		$version = static::get_version( $path );
		wp_deregister_script( 'jquery-core' );
		wp_deregister_script( 'jquery' );
		wp_register_script(
			'jquery-core',
			DOV_File::get_assets_url( $file ),
			array(),
			$version,
			true
		);
		wp_register_script(
			'jquery',
			false,
			array( 'jquery-core' ),
			$version,
			true
		);
	}

	public static function jquery_top_footer() : void {
		if ( wp_scripts()->query( 'jquery', 'enqueued' ) ) {
			wp_scripts()->do_items( 'jquery-core' );
		}
	}

	public static function remove_type_javascript_from_gf( string $form_string ) : string {
		return str_replace( ' type=\'text/javascript\'', '', $form_string );
	}

	public static function enqueue_file( string $file_name, array $args = array() ) : string {
		$data = static::get_enqueue_data( $file_name, $args );
		if ( $data['exists'] ) {
			wp_enqueue_script(
				$data['handle'],
				$data['url'],
				$data['deps'],
				$data['version'],
				$args['in_footer'] ?? true
			);

			return $data['handle'];
		}

		return '';
	}
}


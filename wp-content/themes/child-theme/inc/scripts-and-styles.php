<?php
// Scripts
DOV_Defer_Scripts::add( 'jquery-core' );

DOV_Defer_Scripts::add( 'woo' );

//DOV_Enqueue_Scripts::enqueue_file( 'dov.js', array( 'deps' => array( 'jquery-core' ) ) );

function theme_scripts() {
	wp_enqueue_script(
		'dov',
		get_stylesheet_directory_uri() . '/assets/js/dov.js',
		array( 'jquery' ),
		false,
		false
	);
}

add_action( 'wp_enqueue_scripts', 'theme_scripts' );

DOV_Enqueue_Scripts::enqueue_blocks(
	array(
		'form-login' => array(
			'main.js' => array(
				'jquery',
			),
		),
	)
);

DOV_Enqueue_Scripts::enqueue_blocks(
	array(
		'faq' => array(
			'faq-search.js',
			'tabs.js',
			'accordion.js' => array(
				'jquery',
			),
		),
	)
);

// Styles
DOV_Enqueue_Styles::enqueue_blocks(
	array(
		'site-map'   => array(
			'menu-site-map.css',
		),
		'faq'        => array(
			'forms.css',
			'accordion.css',
		),
		'form-login' => array(
			'login-section.css',
		),
		'my-account' => array(
			'my-account.css',
		),
	),
	static function ( $css ) {
		return $css;
	}
);

// Preload
DOV_Preload::set_images(
	static function ( array $images ): array {
		if ( is_front_page() ) {
			$attachment_id = (int) get_post_meta( get_the_ID(), 'content_0_background', true );
			if ( $attachment_id ) {
				$images[] = array( 'id' => $attachment_id );
			}
		}

		return $images;
	}
);

DOV_Preload::set_scripts(
	static function ( array $scripts ): array {
		/** @noinspection ClassConstantCanBeUsedInspection */
		if (
			function_exists( 'cky_disable_banner' ) &&
			class_exists( 'CookieYes\Lite\Admin\Modules\Settings\Includes\Settings' ) &&
			false === cky_disable_banner()
		) {
			global $cky_loader;

			$settings = new CookieYes\Lite\Admin\Modules\Settings\Includes\Settings();
			if ( $settings->is_connected() ) {
				$scripts[] = array(
					'version' => false,
					'src'     => $settings->get_script_url(),
				);
			} elseif ( isset( $cky_loader ) ) {
				$cookie_front_path = WP_PLUGIN_DIR . '/cookie-law-info/lite/frontend/';
				$suffix            = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				if ( file_exists( $cookie_front_path . 'js/script' . $suffix . '.js' ) ) {
					$scripts[] = array(
						'version' => $cky_loader->get_version(),
						'src'     => plugin_dir_url( $cookie_front_path . 'class-frontend.php' ) . 'js/script' . $suffix . '.js',
					);
				}
			}
		}

		return $scripts;
	}
);
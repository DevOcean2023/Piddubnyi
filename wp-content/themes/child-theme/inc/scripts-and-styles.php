<?php
// Scripts
DOV_Defer_Scripts::add( 'jquery-core' );

DOV_Defer_Scripts::add( 'tooltipster' );
DOV_Defer_Scripts::add( 'frontend.filters' );

DOV_Defer_Scripts::add( 'woo' );

//DOV_Enqueue_Scripts::enqueue_file( 'dov.js', array( 'deps' => array( 'jquery-core' ) ) );

function theme_scripts() {
	wp_enqueue_script(
		'marque',
		'https://cdn.jsdelivr.net/jquery.marquee/1.4.0/jquery.marquee.min.js',
		array( 'jquery' ),
		'1.1',
		false
	);
	wp_enqueue_script(
		'input-mask',
		get_stylesheet_directory_uri() . '/assets/js/inputmask.min.js',
		array(),
		false,
		false
	);
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

DOV_Enqueue_Scripts::enqueue_blocks(
	array(
		'services' => array(
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
		'site-map'      => array(
			'menu-site-map.css',
		),
		'faq'           => array(
			'forms.css',
			'faq.css',
		),
		'form-login'    => array(
			'login-section.css',
		),
		'my-account'    => array(
			'my-account.css',
		),
		'banner-pages'  => array(
			'banner-pages.css',
		),
		'contact-info'  => array(
			'contact-info.css',
		),
		'contact-form'  => array(
			'contact-form.css',
			'gravity-forms.css',
		),
		'brands'        => array(
			'brands.css',
		),
		'home-banner'   => array(
			'home-banner.css',
		),
		'home-reviews'  => array(
			'home-reviews.css',
		),
		'about-us'      => array(
			'about-us.css',
		),
		'our-team'      => array(
			'our-team.css',
		),
		'delivery-info' => array(
			'delivery-info.css',
		),
		'rules-info'    => array(
			'rules-info.css',
		),
		'products-top'  => array(
			'home-products.css',
		),
		'products-sale' => array(
			'home-products.css',
		),
		'trusted'       => array(
			'trusted.css',
		),
		'number'        => array(
			'number.css',
		),
		'home-about'    => array(
			'home-about.css',
		),
		'services'      => array(
			'services.css',
		),
	),
	static function ( $css ) {
		if ( is_404() ) {
			$css .= DOV_Enqueue_Styles::get_css( 'section-404.css' );
		}

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

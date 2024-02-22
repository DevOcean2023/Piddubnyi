<?php

class DOV_Enqueue_Styles_Base extends DOV_Enqueue {
	public const ASYNC_HANDLE = 'theme-async-styles';

	protected static ?Closure $callback = null;

	protected static array $blocks        = array();
	protected static array $async_handles = array();
	protected static array $lazy_handles  = array();

	public static function init() : void {
		parent::init();

		add_action(
			'wp_head',
			array( static::class, 'the_styles' ),
			7
		);
		add_action(
			'wp_head',
			array( static::class, 'the_lazy_styles' ),
			11
		);
		add_filter(
			'style_loader_tag',
			array( static::class, 'replace_media' ),
			10,
			2
		);
		add_filter(
			'style_loader_tag',
			array( static::class, 'insert_attributes' ),
			10,
			2
		);
	}

	public static function insert_attributes( string $tag, string $handle ) : string {
		if ( isset( static::$attributes[ $handle ] ) ) {
			foreach ( static::$attributes[ $handle ] as $attribute => $value ) {
				$tag = str_replace( ' href', ' ' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '" href', $tag );
			}
		}

		return $tag;
	}

	public static function replace_media( string $tag, string $handle ) : string {
		if ( isset( static::$lazy_handles[ $handle ] ) ) {
			$tag = preg_replace( "~ media=(?P<quote>['\"])(.+)(?P=quote)~", ' media=$1print$1 data-media=$1$2$1', $tag );
		}

		return $tag;
	}

	public static function enqueue_base() : void {
		$is_search = is_search();
		if ( $is_search || ! DOV_Is::flex_page() ) {
			static::enqueue_file( 'blog-and-defaults-pages.css' );
		}

		if ( DOV_Is::flex_page() ) {
			static::enqueue_file(
				'footer.css',
				array(
					'strategy' => 'lazy',
					'selector' => '.page-footer',
				)
			);
		} else {
			static::enqueue_file(
				'footer.css',
				array(
					'strategy' => 'async',
				)
			);
		}

		static::enqueue_file(
			'second.css',
			array(
				'strategy' => 'async',
			)
		);
	}

	public static function the_styles() : void {
		$css = static::get_css( 'main.css' );
		if ( DOV_Is::breadcrumb_enabled() ) {
			$css .= static::get_css( 'breadcrumb.css' );
		}

		if ( DOV_Is::flex_page() ) {
			$need_styles     = array();
			$need_visibility = array();
			$need_lazy       = array();
			$index           = 0;
			$inline_index    = apply_filters( 'dov_get_style_max_inline_index', wp_is_mobile() ? 1 : 2 );
			$visibility_css  = DOV_Page_Additional_Data::get_data( get_the_ID(), 'contentVisibilityCSS' );

			while ( have_rows( 'content' ) && ++$index ) {
				the_row();
				$layout = get_row_layout();

				if ( $index <= $inline_index || ! isset( $visibility_css[ $layout ] ) || DOV_Is::additional_data_request() ) {
					if ( isset( static::$blocks[ $layout ] ) ) {
						foreach ( static::$blocks[ $layout ] as $style ) {
							$need_styles[] = str_replace( '.css', '', $style );
						}
					}

					$need_styles[] = $layout;
				} else {
					$need_visibility[] = $layout;
					if ( ! isset( $need_lazy[ $layout ] ) ) {
						$need_lazy[ $layout ] = array();
					}

					$need_lazy[ $layout ][] = $layout;
					if ( isset( static::$blocks[ $layout ] ) ) {
						foreach ( static::$blocks[ $layout ] as $style ) {
							$need_lazy[ str_replace( '.css', '', $style ) ][] = $layout;
						}
					}
				}
			}

			$need_styles     = array_unique( $need_styles );
			$need_visibility = array_diff( array_unique( $need_visibility ), $need_styles );

			if ( $need_visibility && ! DOV_Is::additional_data_request() ) {
				foreach ( $need_visibility as $layout ) {
					$css .= $visibility_css[ $layout ] ?? '';
				}
			}

			foreach ( $need_styles as $layout ) {
				$css .= static::get_css( $layout . '.css' );
			}

			foreach ( $need_lazy as $style => $layouts ) {
				foreach ( array_diff( array_unique( $layouts ), $need_styles ) as $layout ) {
					static::enqueue_file(
						$style . '.css',
						array(
							'strategy' => 'lazy',
							'selector' => '[data-visibility="' . $layout . '"]',
						)
					);
				}
			}
		}

		if ( is_callable( static::$callback ) ) {
			$css = call_user_func( static::$callback, $css );
		}

		if ( $css ) {
			// todo: We need to figure out how to escape the CSS output. It seems to be sufficient to make sure it doesn't include a closing tag, and perhaps all external URLs should be replaced.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<style>' . $css . '</style>';
		}
	}

	public static function enqueue_blocks( array $blocks = array(), Closure $callback = null ) : void {
		if ( $callback ) {
			static::$callback = $callback;
		}

		static::$blocks = array_merge( static::$blocks, $blocks );
	}

	public static function get_css( string $style ) : string {
		$url  = DOV_File::get_assets_url();
		$path = DOV_File::get_assets_path( 'css/' . $style );

		if ( ! DOV_Filesystem::exists( $path ) ) {
			return '';
		}

		return str_replace(
			'url(../',
			'url(' . $url,
			DOV_Filesystem::get_file_contents( $path )
		);
	}

	public static function enqueue_lazy( string $handle, string $selector ) : void {
		if ( ! isset( static::$lazy_handles[ $handle ] ) ) {
			static::$lazy_handles[ $handle ] = array();
		}

		static::$lazy_handles[ $handle ][] = $selector;
	}

	public static function enqueue_file( string $file_name, array $args = array() ) : string {
		$data = static::get_enqueue_data( $file_name, $args );
		if ( $data['exists'] ) {
			$media = $args['media'] ?? 'all';
			if ( isset( $args['strategy'] ) ) {
				if ( 'lazy' === $args['strategy'] ) {
					static::enqueue_lazy( $data['handle'], $args['selector'] );
				} elseif ( 'async' === $args['strategy'] ) {
					static::$async_handles[ $data['handle'] ] = $data['handle'];
					static::add_attribute( $data['handle'], 'onload', 'this.media=\'' . esc_js( $media ) . '\'' );
				}

				$media = 'print';
			}

			wp_enqueue_style(
				$data['handle'],
				$data['url'],
				$data['deps'],
				$data['version'],
				$media
			);

			return $data['handle'];
		}

		return '';
	}

	public static function the_lazy_styles() : void {
		if ( empty( static::$lazy_handles ) ) {
			return;
		}

		static::add_attribute( static::ASYNC_HANDLE, 'data-handles', static::$lazy_handles, 'inline' );

		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NoExplicitVersion
		wp_register_script( static::ASYNC_HANDLE, false, array(), false, true );
		wp_add_inline_script(
			static::ASYNC_HANDLE,
			DOV_Minify_Scripts::minify(
				<<<'JS'
					const data      = JSON.parse(document.currentScript.dataset.handles);
					const selectors = new Set();
					const styles    = new Set();

					for ( const handle in data ) {
						data[ handle ].join( ',' ).split( ',' ).forEach( selector => {
							selectors.add( selector );
							styles.add( { handle, selector } );
						} );
					}

					const selector = [...selectors].join( ',' );
					const elements = [...document.querySelectorAll( selector )];
					const observer = new IntersectionObserver( function  (entries, observer ) {
						entries.forEach( entry => {
							if ( entry.isIntersecting ) {
								const doneHandles = new Set();
								const doneSelectors = new Set();

								styles.forEach( ( value ) => {
									if ( entry.target.matches( value.selector ) ) {
										doneHandles.add( value.handle );
										doneSelectors.add( value.selector );
										styles.delete( value )
									}
								});

								for ( const handle of doneHandles ) {
									const link = document.getElementById( `${handle}-css` );
									if ( link ) {
									    link.media='all';
									}
								}

								for ( const selector of doneSelectors ) {
								    elements.forEach( ( element, index ) => {
									    if ( element.matches( selector ) ) {
										    observer.unobserve( element );
										    elements.splice( index, 1 );
									    }
								    } );
								}
							}
						});
					}, {
						root: null,
						rootMargin: '200px',
						threshold: 0.1
					});

					elements.forEach( element => observer.observe( element ) );
				JS
			)
		);
		wp_enqueue_script( self::ASYNC_HANDLE );
	}
}

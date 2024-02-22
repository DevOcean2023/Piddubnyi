<?php

class DOV_Preload_Base {
	protected static array $images  = array();
	protected static array $fonts   = array();
	protected static array $styles  = array();
	protected static array $scripts = array();

	public static function init() : void {
		add_action(
			'send_headers',
			array( static::class, 'send_headers' )
		);
	}

	public static function send_headers() : void {
		if ( DOV_Is::post_request() || DOV_Is::ajax() ) {
			return;
		}

		foreach ( static::$images as $image ) {
			if ( $image['id'] ) {
				$size  = $image['size'] ?? '375x0';
				$width = explode( 'x', $size )[0] ?? '';
				$src   = wp_get_attachment_image_url( $image['id'], $size );
				if ( $width && preg_match( '/(.*' . $width . 'x\d+)(\..+)/', $src, $matches ) ) {
					$src_2x = $matches[1] . '@2x' . $matches[2];
					header(
						sprintf(
							'Link: <%s>; rel=preload; as=image; fetchpriority=high; media=resolution > 1.5dppx;',
							esc_url( $src_2x )
						),
						false
					);
				}

				header(
					sprintf(
						'Link: <%s>; rel=preload; as=image; fetchpriority=high; media=resolution <= 1.5dppx;',
						esc_url( $src )
					),
					false
				);
			} else {
				header(
					sprintf(
						'Link: <%s>; rel=preload; as=image; fetchpriority=high; media=%s;',
						esc_url( $image['src'] ),
						esc_url( $image['media'] ?? 'all' )
					),
					false
				);
			}
		}

		foreach ( static::$fonts as $font ) {
			header(
				sprintf(
					'Link: <%s>; rel=preload; as=font; type=%s; crossorigin;',
					esc_url( $font['src'] ),
					esc_attr( 'font/' . $font['type'] )
				),
				false
			);
		}

		foreach ( static::$styles as $style ) {
			header(
				sprintf(
					'Link: <%s>; rel=preload; as=style;',
					esc_url( add_query_arg( 'ver', $style['version'], $style['src'] ) )
				),
				false
			);
		}

		foreach ( static::$scripts as $script ) {
			header(
				sprintf(
					'Link: <%s>; rel=preload; as=script;',
					esc_url( add_query_arg( 'ver', $script['version'], $script['src'] ) )
				),
				false
			);
		}
	}

	public static function set_images( Closure $callback ) : void {
		if ( is_callable( $callback ) ) {
			static::$images = $callback( static::$images );
		}
	}

	public static function set_fonts( Closure $callback ) : void {
		if ( is_callable( $callback ) ) {
			static::$fonts = $callback( static::$fonts );
		}
	}

	public static function set_styles( Closure $callback ) : void {
		if ( is_callable( $callback ) ) {
			static::$styles = $callback( static::$styles );
		}
	}

	public static function set_scripts( Closure $callback ) : void {
		if ( is_callable( $callback ) ) {
			static::$scripts = $callback( static::$scripts );
		}
	}
}

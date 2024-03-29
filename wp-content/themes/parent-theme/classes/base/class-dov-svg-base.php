<?php

class DOV_SVG_Base {
	public static function init() : void {
		add_filter(
			'upload_mimes',
			array( static::class, 'add' )
		);
		add_filter(
			'wp_check_filetype_and_ext',
			array( static::class, 'check' ),
			10,
			5
		);
		add_filter(
			'wp_prepare_attachment_for_js',
			array( static::class, 'show' )
		);
		add_filter(
			'wp_get_attachment_image_src',
			array( static::class, 'set_sizes' ),
			10,
			2
		);
		DOV_TinyMCE::add_editor_styles(
			'img[src$=".svg"]',
			'
			background: #eee;
			box-shadow: inset 0 0 15px rgba(0,0,0,.1), inset 0 0 0 1px rgba(0,0,0,.05);
			min-width: 24px;
			'
		);
	}

	public static function add( array $mimes ) : array {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}

	/** @noinspection PhpUnusedParameterInspection */
	public static function check( array $data, $file, $filename, $mimes, string $real_mime ) : array {
		if ( in_array( $real_mime, array( 'image/svg', 'image/svg+xml' ), true ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				$data['ext']  = 'svg';
				$data['type'] = 'image/svg+xml';
			} else {
				$data['type'] = false;
				$data['ext']  = false;
			}
		}

		return $data;
	}

	public static function show( array $response ) : array {
		if ( 'image/svg+xml' === $response['mime'] ) {
			$response['sizes'] = array(
				'full' => array(
					'url' => $response['url'],
				),
			);
		}

		return $response;
	}


	public static function set_sizes( $image, $attachment_id ) {
		if ( 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
			$path = get_attached_file( $attachment_id );
			if ( file_exists( $path ) ) {
				try {
					$svg        = new SimpleXMLElement( DOV_Filesystem::get_file_contents( $path ) );
					$width      = 0;
					$height     = 0;
					$attributes = $svg->attributes();
					$view_box   = $attributes?->viewBox;

					if ( isset( $attributes->width, $attributes->height ) ) {
						$width  = $attributes?->width;
						$height = $attributes?->height;
					} elseif ( $view_box ) {
						$sizes = explode( ' ', $view_box );
						if ( isset( $sizes[2], $sizes[3] ) ) {
							// This is not a valid error, it is not short array.
							// phpcs:ignore Generic.Arrays.DisallowShortArraySyntax.Found
							[ , , $width, $height ] = $sizes;
						}
					}

					$image[1] = (int) $width;
					$image[2] = (int) $height;
				} catch ( Exception $e ) {
					DOV_Log::write( new WP_Error( 'svg', $e->getMessage(), array( 'path' => $path ) ) );
				}
			}
		}

		return $image;
	}
}

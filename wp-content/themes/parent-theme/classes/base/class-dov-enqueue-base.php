<?php

class DOV_Enqueue_Base {
	protected static array $attributes = array();

	public static function init() : void {
		add_action(
			'wp_enqueue_scripts',
			array( static::class, 'enqueue_base' )
		);
	}

	public static function add_attribute( string $handle, string $name, $value, $type = '' ) : void {
		if ( $type ) {
			if ( 'inline' === $type || 'after' === $type ) {
				$handle .= '-js-after';
			} elseif ( 'before' === $type ) {
				$handle .= '-js-before';
			}
		}

		if ( ! isset( static::$attributes[ $handle ] ) ) {
			static::$attributes[ $handle ] = array();
		}

		if ( is_array( $value ) || is_object( $value ) ) {
			$value = wp_json_encode( $value );
		}

		static::$attributes[ $handle ][ $name ] = $value;
	}

	public static function get_enqueue_data( string $file_name, array $args = array() ) : array {
		$data    = explode( '.', $file_name );
		$handle  = sprintf( 'theme-%s', $data[0] );
		$type    = $data[1];
		$file    = sprintf( '%s/%s', $type, $file_name );
		$path    = DOV_File::get_assets_path( $file );
		$url     = DOV_File::get_assets_url( $file );
		$exists  = file_exists( $path );
		$version = $exists ? static::get_version( $path ) : '';

		if ( isset( $args['deps'] ) ) {
			$deps = is_array( $args['deps'] ) ? $args['deps'] : array( $args['deps'] );
		} else {
			$deps = array();
		}

		return array(
			'file_name' => $file_name,
			'handle'    => $handle,
			'type'      => $type,
			'path'      => $path,
			'url'       => $url,
			'exists'    => $exists,
			'version'   => $version,
			'deps'      => $deps,
		);
	}

	public static function get_version( string $path ) : string {
		return (string) filemtime( $path );
	}
}

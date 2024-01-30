<?php

class DOV_Page_Additional_Data_Base {
	public const HANDLE         = 'theme-admin-additional-data';
	public const REQUEST_KEY    = 'additional-data';
	public const META_KEY       = '_theme-additional-data';
	public const REST_NAMESPACE = 'theme/v1';
	public const REST_ROUTE     = 'additional-data/';

	public static function init() : void {
		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);
		add_action(
			'admin_enqueue_scripts',
			array( static::class, 'enqueue_content_visibility' )
		);
	}

	public static function registration_rest_routes() : void {
		register_rest_route(
			static::REST_NAMESPACE,
			static::REST_ROUTE,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( static::class, 'save_data' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'postId' => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
					'data'   => array(
						'type' => 'object',
					),
				),
			)
		);
	}

	public static function save_data( WP_REST_Request $request ) : WP_REST_Response {
		update_post_meta(
			$request->get_param( 'postId' ),
			static::META_KEY,
			$request->get_param( 'data' )
		);

		return new WP_REST_Response( array( 'message' => 'Data saved.' ) );
	}

	public static function get_data( int $post_id, string $key ) : string|array {
		$value = get_post_meta( $post_id, static::META_KEY, true );

		return $value[ $key ] ?? '';
	}

	public static function enqueue_content_visibility() : void {
		global $post;

		if ( 'page' === get_current_screen()?->id && DOV_Is::flex_page( $post ) && DOV_Is::get_request( 'message' ) ) {
			$name = 'js/dov-page-additional-data.js';
			$url  = DOV_File::get_parent_url( $name );
			$path = DOV_File::get_parent_path( $name );

			DOV_Enqueue_Scripts::add_attribute( static::HANDLE, 'data-page-url', get_permalink( $post ) );
			DOV_Enqueue_Scripts::add_attribute( static::HANDLE, 'data-request-key', static::get_request_key() );
			DOV_Enqueue_Scripts::add_attribute( static::HANDLE, 'data-rest-path', static::REST_NAMESPACE . '/' . static::REST_ROUTE );

			wp_enqueue_script(
				static::HANDLE,
				$url,
				array( 'wp-api-fetch' ),
				DOV_Enqueue_Scripts::get_version( $path ),
				array( 'in_footer' => false )
			);
		}
	}

	public static function get_request_key() : string {
		return static::REQUEST_KEY;
	}
}

<?php

class DOV_Mail_Base {
	public static string $last_error = '';

	public static function mail( string $to, string $subject, string $message, array $attachments = array() ) : bool {
		static::before_mail();

		$site_url       = home_url( '/' );
		$headers        = apply_filters( 'dov_mail_headers', '' );
		$search_replace = apply_filters(
			'dov_mail_replace',
			array(
				'href="/'     => 'href="' . $site_url,
				'src="/'      => 'src="' . $site_url,
				'%site_url%/' => '%site_url%',
				'%site_url%'  => $site_url,
			)
		);
		$search         = array_keys( $search_replace );
		$replace        = array_values( $search_replace );
		$message        = str_replace( $search, $replace, $message );
		$subject        = str_replace( $search, $replace, $subject );

		$send = wp_mail( $to, $subject, $message, $headers, $attachments );

		static::after_mail();

		return $send;
	}

	public static function before_mail() : void {
		add_action( 'wp_mail_failed', array( __CLASS__, 'mail_failed' ) );
		add_filter( 'wp_mail_from', array( __CLASS__, 'mail_from' ) );
		add_filter( 'wp_mail_from_name', array( __CLASS__, 'from_name' ) );
		add_filter( 'wp_mail_content_type', array( __CLASS__, 'content_type_html' ) );
	}

	public static function after_mail() : void {
		remove_action( 'wp_mail_failed', array( __CLASS__, 'mail_failed' ) );
		remove_filter( 'wp_mail_from', array( __CLASS__, 'mail_from' ) );
		remove_filter( 'wp_mail_from_name', array( __CLASS__, 'from_name' ) );
		remove_filter( 'wp_mail_content_type', array( __CLASS__, 'content_type_html' ) );
	}

	public static function mail_from() : string {
		return preg_replace(
			array( '/^https?:\/\/(www.)?/', '/\/$/' ),
			array( 'info@', '' ),
			home_url()
		);
	}

	public static function from_name() : string {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	public static function content_type_html() : string {
		return 'text/html';
	}

	public static function mail_failed( WP_Error $error ) : void {
		static::$last_error = $error->has_errors() ? $error->get_error_message() : '';
		DOV_Log::write( $error );
	}
}

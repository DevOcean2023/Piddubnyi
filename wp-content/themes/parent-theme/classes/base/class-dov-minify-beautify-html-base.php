<?php

class DOV_Minify_Beautify_HTML_Base {
	public static function init() : void {
		add_action(
			'get_header',
			static function () {
				if ( DOV_Is::frontend() ) {
					ob_start( array( static::class, 'beautify' ) );
				}
			}
		);
	}

	public static function beautify( string $html ) : string {
		if ( DOV_Is::local() ) {
			$beautify = new DOV_Beautify_Html(
				array(
					'indent_inner_html' => true,
					'indent_char'       => "\t",
					'indent_size'       => 1,
					'preserve_newlines' => true,
					'unformatted'       => array( 'code', 'pre' ),
				)
			);

			return preg_replace(
				array( '/="([^"]+) "/' ),
				array( '="$1"' ),
				$beautify->beautify( $html )
			);
		}

		return $html;
	}
}

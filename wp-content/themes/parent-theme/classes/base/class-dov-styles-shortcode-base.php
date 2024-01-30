<?php

class DOV_Styles_Shortcode_Base {
	public static function init() : void {
		add_filter(
			'acf_the_content',
			array( static::class, 'replace' ),
			1
		);
	}

	public static function replace( string $content ) : string {
		// Replace inline styles
		$content = preg_replace(
			'~(?=[^\r\n])(\[styles\s+type="([^"]+)"])((?:(?!\[/?styles).)*)(\[/styles])~s',
			'<span class="$2">$3</span>',
			$content
		);

		// Replace wrapper styles
		return preg_replace(
			'~(\[styles\s+type="([^"]+)"]\s*)((?:(?!\[/?styles).)*)(\[/styles])~s',
			'<p class="$2">$3</p>',
			$content
		);
	}
}

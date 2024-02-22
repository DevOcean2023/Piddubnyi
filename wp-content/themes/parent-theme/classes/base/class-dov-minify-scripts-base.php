<?php

use MatthiasMullie\Minify\JS;

class DOV_Minify_Scripts_Base {
	public static function minify( string $inline_script ) : string {
		if ( ! class_exists( JS::class ) ) {
			return $inline_script;
		}

		return preg_replace(
			array(
				'/(\.\w+\([^()]+\))([^.,;:<>=+|&}?)!\[\]])/',
				'/(?<!else|{|}|;) if/',
				//'/}(?!catch|else|[|{},;:()[\]])/',
				'/(=(?!new|function|typeof|void)[[:word:]]+) /',
			),
			array( '$1;$2', ';$0', /*'$0;',*/ '$1; ' ),
			( new JS( $inline_script ) )->minify()
		);
	}
}

import localFonts from '@sumotto/gulp-local-fonts';
import { src } from 'gulp';
import { relative } from 'path';
import { minify as minifyCss } from 'csso';
import { minifySync as minifyJs } from 'terser-sync/lib';
import { appDest, getLastRun, wwwDest } from '../helpers';
import { Transform } from 'stream';
import {
	FONTS_PATH,
} from '../constants';

export function getFontPaths() {
	return FONTS_PATH;
}

export function fonts() {
	return src( getFontPaths(), {
		since: getLastRun( 'fonts' ),
	} )
		.pipe(
			localFonts( {
				cssTransform: ( { css } ) => minifyCss( css ).css,
				jsTransform: ( { js } ) =>
					minifyJs( js, { module: true } ).code,
			} )
		)
		.pipe( appDest( 'assets/fonts' ) )
		.pipe( wwwDest( 'assets/fonts' ) );
}

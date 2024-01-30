import { series, src } from 'gulp';
import { appDest, getLastRun, wwwDest } from '../helpers';

export function getImagePaths( prefix ) {
	return [
		`${ prefix }/images/**/*`,
		`!${ prefix }/images/svg-load/*`,
		`!${ prefix }/images/svg-load`,
	];
}

export function imagesDefaults() {
	return src( getImagePaths( 'base/defaults' ), {
		// since: getLastRun( 'imagesDefaults' ),
	} )
		.pipe( appDest( 'assets/images' ) )
		.pipe( wwwDest( 'assets/images' ) );
}

export function imagesProject() {
	return src( getImagePaths( 'src' ), {
		// since: getLastRun( 'imagesProject' ),
	} )
		.pipe( appDest( 'assets/images' ) )
		.pipe( wwwDest( 'assets/images' ) );
}

export function images( cb ) {

	return series( imagesDefaults, imagesProject )( cb );
}

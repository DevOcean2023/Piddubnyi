import { parallel, src } from 'gulp';
import gulpIndex from 'gulp-index';
import gulpExtname from 'gulp-extname';
import { appDest, getLastRun, setLastRun, wwwDest } from '../helpers';
import {
	paniniDefaults,
	paniniDefaultsRefresh,
} from '../panini/panini-defaults';
import { paniniProject, paniniProjectRefresh } from '../panini/panini-project';
import {
	APP_PATH,
	CACHE_DIR,
	MARKUP_DIST_PATH,
	PRODUCTION,
	WATCH_PAGES_DEPENDENCIES,
} from '../constants';
import htmlModification from '../html-modification';
import { relative, resolve, sep } from 'path';
import { globSync } from 'glob';
import {
	existsSync,
	mkdirSync,
	readFileSync,
	statSync,
	writeFileSync,
} from 'fs';

export function paniniOptions( root ) {
	return {
		root,
		layouts: [
			'base/defaults/layouts/',
			'src/layouts/',
		],
		partials: [
			'base/defaults/partials/',
			'src/partials/',
		],
		data: 'src/data/',
		helpers: [
			'base/defaults/helpers/',
			'src/helpers/',
		],
	};
}

function getFileCachePath( path ) {
	const cacheKey = relative( APP_PATH, path ).replaceAll( sep, '-' );

	return resolve(
		CACHE_DIR,
		'templates',
		cacheKey + ( PRODUCTION ? '-PRD' : '-DEV' ) + '.json'
	);
}

function getFileCacheInfo( path ) {
	const cachePath = getFileCachePath( path );
	if ( existsSync( cachePath ) ) {
		return JSON.parse( readFileSync( cachePath ).toString() );
	}

	return false;
}

function getMTime( dependencyPath ) {
	return Math.floor(
		new Date( statSync( dependencyPath ).mtime.toString() ).getTime() / 1000
	);
}

function checkModifiedDependencies( cacheKey, pagesPath ) {
	let dependenciesInfo = getFileCacheInfo(
		APP_PATH + '/' + cacheKey + '.dependencies'
	);

	const lastRun = getLastRun( cacheKey );
	const pagePaths = globSync( pagesPath );
	const dependencyPaths = globSync( WATCH_PAGES_DEPENDENCIES );
	const pagesHasChanges =
		pagePaths.filter( ( pagePath ) => getMTime( pagePath ) !== lastRun )
			.length > 0;
	const dependenciesHasChanges =
		dependenciesInfo === false ||
		dependenciesInfo.filter(
			( dependencyInfo ) =>
				! existsSync( dependencyInfo.path ) ||
				getMTime( dependencyInfo.path ) !== dependencyInfo.mtime
		).length > 0 ||
		dependenciesInfo.length !== dependencyPaths.length;

	if ( ! pagesHasChanges && ! dependenciesHasChanges ) {
		return lastRun;
	}

	dependenciesInfo = [];
	dependencyPaths.forEach( function ( dependencyPath ) {
		dependenciesInfo.push( {
			path: dependencyPath,
			mtime: getMTime( dependencyPath ),
		} );
	} );

	const cachePath = getFileCachePath(
		APP_PATH + '/' + cacheKey + '.dependencies'
	);

	mkdirSync( cachePath.split( sep ).slice( 0, -1 ).join( sep ), {
		recursive: true,
	} );

	writeFileSync( cachePath, JSON.stringify( dependenciesInfo ) );

	setLastRun( cacheKey );

	return 0;
}

export function pagesDefaults() {
	const pagesGlob = 'base/defaults/pages/**/*.hbs';
	return src( pagesGlob, {
		since: checkModifiedDependencies( 'pagesDefaults', pagesGlob ),
	} )
		.pipe( paniniDefaults( paniniOptions( 'base/defaults/' ) ) )
		.pipe( gulpExtname() )
		.pipe( htmlModification() )
		.pipe( appDest( 'defaults' ) )
		.pipe( wwwDest( 'defaults' ) );
}

export function pagesProject() {
	const pagesGlob = 'src/pages/**/*.hbs';
	return src( pagesGlob, {
		since: checkModifiedDependencies( 'pagesProject', pagesGlob ),
	} )
		.pipe( paniniProject( paniniOptions( 'src/pages/' ) ) )
		.pipe( gulpExtname() )
		.pipe( htmlModification() )
		.pipe( appDest() )
		.pipe( wwwDest() );
}

export function resetPages( done ) {
	paniniDefaultsRefresh();
	paniniProjectRefresh();

	done();
}

export function addIndex() {
	return src( `${ MARKUP_DIST_PATH }/**/*.html` )
		.pipe(
			gulpIndex( {
				relativePath: '../www/',
				'title-template': () => '',
				'item-template': ( filepath, filename ) => {
					let className = 'index__item';
					if ( filepath ) {
						className += ' sub-dir';
					} else {
						className += ' root-dir';
					}

					if ( 'index.html' === filename ) {
						className += ' index';
					}

					let href;
					if ( filepath ) {
						href = filepath + '/' + filename;
					} else {
						href = filename;
					}

					return `
						<li class="${ className }">
							<a
								class="index__item-link"
								href="${ href }">
								${ filename }
							</a>
						</li>
					`;
				},
				'append-to-output': () => `
					<script>
						const rootItems = document.querySelectorAll('.root-dir');
						const rootSection = document.createElement('section');
						const rootTitle = document.createElement('h2');
						const rootList = document.createElement('ul');
						const incorrectTitles = document.querySelectorAll('ul h2');

						rootTitle.innerText = 'project';

						rootSection.appendChild(rootTitle);
						rootSection.appendChild(rootList);

						rootItems.forEach(function(rootItem) {
							rootItem.closest('section').style.display = 'none';

							if (!rootItem.classList.contains('index')) {
								rootList.appendChild(rootItem);
							}
						});

						incorrectTitles.forEach(function(incorrectTitle) {
							incorrectTitle
								.closest('section')
								.insertBefore(incorrectTitle, incorrectTitle.closest('ul'));
						});

						document.body.insertBefore(rootSection, document.body.querySelector('section'));
					</script>
					</body>
				`,
			} )
		)
		.pipe( wwwDest() );
}

export function pages( done ) {

	return parallel( pagesDefaults, pagesProject )( done );
}

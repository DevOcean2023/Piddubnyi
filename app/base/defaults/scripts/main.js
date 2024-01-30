import domReady from './libs/dom-ready';
import liveDom from './libs/live-dom';

import './hash-reset';
import './links';

// todo: menu is processed into Web Components.
import './menus';

// todo: sticky-header is processed into Web Components.
import './sticky-header';

domReady( () => {
	liveDom(
		'[href^="#popup-"],' +
			'[href^="https://www.youtube.com/watch?v="],' +
			'[href^="https://vimeo.com/video/"],' +
			'[data-popup-id]'
	).onceInit( () => import( '../../../src/scripts/popups' ) );

	// todo: map is processed into Web Components.
	liveDom( '[data-map]' ).onceInit( () =>
		import( '../../../src/scripts/maps' )
	);

	// todo: slider is processed into Web Components.
	liveDom( '[data-slider]' ).onceInit( () =>
		import( '../../../src/scripts/sliders' )
	);

	// todo: [data-optimisation-gf-form-id] is processed into Web Components.
	liveDom( '.form, [data-optimisation-gf-form-id], form' ).onceInit( () =>
		import( './forms' )
	);

	// todo: card is processed into Web Components.
	liveDom( '.accessibility-card' ).onceInit( () => import( './cards' ) );

	// todo: accordion is processed into Web Components.
	liveDom( '.accordion' ).onceInit( () => import( './accordions' ) );

	import( 'instant.page' );
} );

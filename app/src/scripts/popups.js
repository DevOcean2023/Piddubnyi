// noinspection JSIgnoredPromiseFromCall

import { popup } from '../../base/defaults/scripts/libs/popup';

popup( {
	linkSelector: '[href^="#popup-"], [data-popup-id]',
} );

popup( {
	linkSelector: [
		'[href^="https://www.youtube.com/watch?v="]',
		'[href^="https://vimeo.com/video/"]',
		'[href^="https://player.vimeo.com/video/"]',
	].join(),
	type: 'video',
} );

import { PopupIframe } from './popup-iframe';

export class PopupVideo extends PopupIframe {
	constructor( link, isModal ) {
		super( link, isModal, 'video' );
	}
}

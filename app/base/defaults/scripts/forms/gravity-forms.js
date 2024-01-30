import { toggleFocus } from '../libs/toggle-focus';
import liveDom from '../libs/live-dom';
import TomSelect from '../npm-proxy/tom-select';
import './gravity-forms-load-optimisation';

toggleFocus( '.gfield' );
toggleFocus( 'span', '.ginput_complex' );
toggleFocus( '.ginput_product_price_wrapper' );

liveDom( 'select.gfield_select' ).init( function () {
	new TomSelect( this, {
		controlInput: null,
	} );
} );

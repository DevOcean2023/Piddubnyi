import liveDom from './libs/live-dom';

liveDom( 'textarea,input' ).onceInit( () =>
	import( './libs/toggle-focus' ).then( ( { toggleFocus } ) =>
		toggleFocus( '.form__item' )
	)
);
liveDom( '[data-optimisation-gf-form-id], .gfield' ).onceInit( () =>
	import( './forms/gravity-forms' )
);

liveDom( 'textarea,input' ).onceInit( () =>
	import( './forms/disabled-visual-focus' )
);

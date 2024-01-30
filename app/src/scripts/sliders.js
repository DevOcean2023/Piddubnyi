import { tinySlider } from '../../base/defaults/scripts/libs/tiny-slider';

tinySlider( '[data-slider="demo-simple"]', {
	items: 1,
	gutter: 30,
	autoplay: true,
	autoplayTimeout: 3000,
	autoplayButtonOutput: false,
	speed: 1500,
	controls: true,
	controlsText: [ '<span>&#10229;</span>', '<span>&#10230;</span>' ],
	navAsThumbnails: true,
	navPosition: 'bottom',
	swipeAngle: false,
	displayCounter: true,
	responsive: {
		350: {
			items: 2,
		},
		568: {
			items: 3,
		},
	},
} );

tinySlider(
	'[data-slider="demo-filters"]',
	{
		items: 1,
		gutter: 30,
		autoplay: true,
		autoplayTimeout: 3000,
		autoplayButtonOutput: false,
		speed: 1500,
		controls: true,
		controlsText: [ '<span>&#10229;</span>', '<span>&#10230;</span>' ],
		navPosition: 'bottom',
		swipeAngle: false,
		responsive: {
			350: {
				items: 2,
			},
			568: {
				items: 3,
			},
		},
		filter( { item, slider } ) {
			return item.dataset.filter?.includes( slider.getData() );
		},
	},
	( slider ) => {
		slider
			.getElement()
			.closest( 'section' )
			.querySelectorAll( 'button[data-filter]' )
			?.forEach( function ( button ) {
				button.addEventListener( 'click', function () {
					slider.setData( this.dataset.filter ).filter();
				} );
			} );
	}
);

export default function accessibilityCard(
	cardNode,
	titleLinkSelector = '.title a'
) {
	let down;
	let up;
	const link = cardNode.querySelector( titleLinkSelector );

	cardNode.style.cursor = 'pointer';

	cardNode.onmousedown = ( event ) => {
		if ( event.button === 0 ) {
			down = +new Date();
		}
	};

	cardNode.onmouseup = ( event ) => {
		if ( event.button === 0 ) {
			up = +new Date();
			if ( up - down < 200 ) {
				link.click();
			}
		}
	};
}

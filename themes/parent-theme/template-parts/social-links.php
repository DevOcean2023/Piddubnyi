<?php
/**
 * @var array{
 *     selector: string,
 *     class: string,
 *     size: string
 * } $args
 */

$size     = $args['size'] ?? '50x50';
$selector = $args['selector'] ?? 'dov_social_links';
$class    = $args['class'] ?? '';

while ( dov_loop( $selector, '<div class="social-links ' . esc_attr( $class ) . '">' ) ) {
	while ( dov_wrap( 'link', 'social-links__link' ) ) {
		dov_the( 'icon_color', $size, 'social-links__image social-links__image_color', array( 'aria-hidden' => 'true' ) );
		dov_the( 'icon_outline', $size, 'social-links__image social-links__image_outline', array( 'aria-hidden' => 'true' ) );

		$text = get_sub_field( 'link' )['title'] ?? '';
		if ( $text ) {
			printf(
				'<span class="social-links__text">%s</span>',
				esc_html( $text )
			);
		}
	}
}

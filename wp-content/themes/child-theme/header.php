<?php
/** @noinspection HtmlRequiredTitleElement */

echo '<!DOCTYPE html>';

echo wp_kses(
	'<html ' . get_language_attributes() . '>',
	array(
		'html' => array(
			'dir'    => true,
			'lang'   => true,
			'style'  => true,
			'class'  => true,
			'id'     => true,
			'data-*' => true,
		),
	)
);

echo '<head>';
wp_head();
echo '<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">';
echo '<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">';
echo '</head>';

echo '<body class="' . esc_attr( implode( ' ', get_body_class() ) ) . '">';
echo '<a class="screen-reader-text" href="#page-main">' . esc_html__( 'Skip to content', 'theme' ) . '</a>';

wp_body_open();
$preloader_active = get_field( 'dov_preloader_active', 'options' );
if ( ! $preloader_active || is_user_logged_in() ) {
	get_template_part( 'template-parts/header' );
	get_template_part( 'template-parts/breadcrumb' );
}
echo '<main class="page-main" id="page-main">';

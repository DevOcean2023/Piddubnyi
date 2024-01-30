<?php
/**
 * Template Name: Flexible Content
 */

get_header();

if ( have_posts() ) {
	the_post();
	if ( post_password_required() ) {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo get_the_password_form();
	} else {
		$need_run = apply_filters( 'dov_need_run_content', true );

		if ( $need_run ) {
			do_action( 'dov_run_content' );
			if ( have_rows( 'content' ) ) {
				while ( have_rows( 'content' ) ) {
					the_row();
					DOV_ACF_Flex_Content::the_content();
				}
			}
			do_action( 'dov_end_content' );
		} else {
			do_action( 'dov_not_need_run_content' );
		}
	}
}

get_footer();

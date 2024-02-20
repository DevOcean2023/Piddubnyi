<?php
get_header();

if ( is_singular() ) {
	if ( is_single() ) {
		while ( dov_loop( 'dov_blog_menu' ) ) {
			get_template_part( 'template-parts/blog/blog-menu' );
		}
		get_template_part( 'template-parts/blog/blog-title' );
		get_template_part( 'template-parts/blog/blog-post' );
		while ( dov_loop( 'dov_blog_form' ) ) {
			get_template_part( 'template-parts/blog/blog-form' );
		}
	} else {
		get_template_part( 'template-parts/default-page' );
	}
} elseif ( is_home() || is_archive() ) {
	echo '<h1 class="blog-title">' . esc_html( dov_get_the_archive_title() ) . '</h1>';
	get_template_part( 'template-parts/blog/blog-preview' );
	get_template_part(
		'template-parts/blog/blog-newsletter',
		null,
		array(
			'selector' => 'dov_blog_newsletter_top',
			'class'    => 'blog-newsletter_top',
		)
	);
	get_template_part( 'template-parts/blog/blog-posts' );
} elseif ( is_search() ) {
	$search_title = sprintf( '<h1 class="title">%s</h1>', esc_html__( 'Search', 'theme' ) );
	get_template_part( 'template-parts/search-form', null, array( 'title' => $search_title ) );
	get_template_part( 'template-parts/search-results' );
} else {
	get_template_part( 'template-parts/error-404' );
}

get_footer();

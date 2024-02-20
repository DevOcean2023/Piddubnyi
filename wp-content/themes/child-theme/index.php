<?php
get_header();

if ( is_singular() ) {
	if ( is_single() ) {
		get_template_part( 'template-parts/blog/blog-post-banner' );
		get_template_part( 'template-parts/blog/blog-post' );
		get_template_part( 'template-parts/blog/blog-posts-related' );
		get_template_part( 'template-parts/flexible-content/contact-head' );
	} else {
		get_template_part( 'template-parts/default-page' );
	}
} elseif ( is_home() || is_archive() ) {
	get_template_part( 'template-parts/blog/blog-banner' );
	get_template_part( 'template-parts/blog/blog-posts' );
	get_template_part( 'template-parts/flexible-content/contact-head' );
} elseif ( is_search() ) {
	$search_title = sprintf( '<h1 class="title">%s</h1>', esc_html__( 'Search', 'theme' ) );
	get_template_part( 'template-parts/search-form', null, array( 'title' => $search_title ) );
	get_template_part( 'template-parts/search-results' );
} else {
	get_template_part( 'template-parts/error-404' );
}

//get_footer();

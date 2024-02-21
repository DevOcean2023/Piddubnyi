<?php
$allowed_html = array(
	'ul' => array(
		'class' => true,
	),
	'li' => array(
		'class' => true,
	),
	'a'  => array(
		'class'        => true,
		'href'         => true,
		'rel'          => true,
		'aria-current' => true,
	),
);

$categories_tags = new WP_HTML_Tag_Processor(
	wp_list_categories(
		array(
			'echo'     => false,
			'title_li' => '',
		)
	)
);
while ( $categories_tags->next_tag() ) {
	if ( 'A' === $categories_tags->get_tag() ) {
		$categories_tags->add_class( 'categories__link' );
		if ( 'page' === $categories_tags->get_attribute( 'aria-current' ) ) {
			$categories_tags->add_class( 'categories__link_current' );
		}
	} elseif ( 'LI' === $categories_tags->get_tag() ) {
		$categories_tags->add_class( 'categories__item' );
	}
}
?>
<nav class="blog-sidebar__categories">
	<h2 class="blog-sidebar__title"><?php esc_html_e( 'Категорії', 'theme' ); ?></h2>
	<ul class="categories">
		<li class="cat-item categories__item">
			<a class="categories__link active" href="/blog">
				<?php esc_html_e( 'Усі статті', 'theme' ); ?>
			</a>
		</li>
		<?php echo wp_kses( $categories_tags->get_updated_html(), $allowed_html ); ?>
	</ul>
</nav>

<?php
$categories_tags = new WP_HTML_Tag_Processor( get_the_category_list( ' ' ) );
while ( $categories_tags->next_tag() ) {
	if ( 'A' === $categories_tags->get_tag() ) {
		$categories_tags->add_class( 'blog-article-categories__link' );
	}
}
?>
<section class="blog-post">
	<div class="inner">
		<div class="blog-post__wrapper">
			<section class="blog-post__item">
				<div class="blog-article-categories blog-post__categories" aria-label="Categories">
					<?php echo wp_kses( $categories_tags->get_updated_html(), DOV_KSES::get_by_tag( 'a' ) ); ?>
				</div>
				<?php the_title( '<h1 class="blog-post__title">', '</h1>' ); ?>
				<div class="blog-post__posted">
					<?php esc_html_e( 'Опубліковано', 'theme' ); ?>
					<?php echo get_the_date( 'F j, Y' ); ?></div>
				<div class="blog-post__thumbnail">
					<?php
					the_post_thumbnail(
						'full',
						array(
							'class' => 'blog-post__image',
						),
					);
					?>
				</div>
			</section>
			<section class="blog-post-content">
				<div class="blog-post-content__content">
					<?php DOV_BAM_Content::the_content( 'blog-post-content' ); ?>
				</div>
			</section>
		</div>
	</div>
</section>

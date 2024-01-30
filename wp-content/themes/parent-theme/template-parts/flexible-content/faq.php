<?php
$section_index = get_row_index();
$faq_terms     = get_terms(
	array(
		'taxonomy' => 'faq_category',
	)
);

if ( ! $faq_terms ) {
	return;
}
?>
<section class="faq">
	<div class="inner">
		<?php get_template_part( 'template-parts/faq/form' ); ?>
		<theme-tabs class="faq__tabs" all="<?php esc_attr_e( 'All', 'theme' ); ?>">
			<?php foreach ( $faq_terms as $tab_index => $faq_term ) : ?>
				<?php
				$faq_posts = get_posts(
					array(
						'numberposts' => - 1,
						'post_type'   => 'faq',
						'tax_query'   => array(
							array(
								'taxonomy' => 'faq_category',
								'field'    => 'slug',
								'terms'    => $faq_term->slug,
							),
						),
					),
				);
				?>
				<theme-tabs-button class="faq__tabs-button"><?php echo esc_html( $faq_term->name ); ?></theme-tabs-button>
				<theme-tabs-content class="faq__tabs-content">
					<?php while ( dov_loop( $faq_posts ) ) : ?>
						<?php
						get_template_part(
							'template-parts/faq/accordion',
							null,
							array(
								'section_index' => $section_index,
								'tab_index'     => $tab_index + 1,
							)
						);
						?>
					<?php endwhile; ?>
				</theme-tabs-content>
			<?php endforeach; ?>
		</theme-tabs>
	</div>
</section>

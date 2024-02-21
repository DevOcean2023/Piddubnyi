<?php
$index = 0;
?>
<section class="blog-preview">
	<div class="inner">
		<?php while ( dov_loop( 'dov_sticky_posts', '<div class="blog-preview__wrapper">' ) ) : ?>
			<?php
			get_template_part(
				'template-parts/blog/blog-preview-item',
				null,
				array(
					'is_first' => 0 === $index++,
				)
			);
			?>
		<?php endwhile; ?>
	</div>
</section>

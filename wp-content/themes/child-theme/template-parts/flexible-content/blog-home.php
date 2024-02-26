<?php
/**
 * @var array{
 *     is_first: bool
 * } $args
 */
?>
<section class="blog-home">
	<div class="inner">
		<div class="blog-home__head">
			<?php dov_the( 'title', 'blog-home__head-title' ); ?>
			<?php dov_the( 'button', 'top-btn btn' ); ?>
		</div>

		<?php $i = 1; ?>
		<?php while ( dov_loop( 'blog', '<div class="blog-home__wrapper">' ) ) : ?>
			<a class="blog-home__item <?php echo ( $i == 1 ) ? 'blog-home__item_first' : ''; ?>" href="<?php the_permalink(); ?>">
				<div class="blog-home__image">
					<?php the_post_thumbnail( 'full' ); ?>
				</div>
				<div class="blog-home__content">
					<h2 class="blog-home__title"><?php the_title(); ?></h2>
					<p class="blog-home__excerpt">
						<?php dov_the_excerpt( $args['is_first'] ? ' 160' : '100', false, true, '...' ); ?>
					</p>
				</div>
			</a>
			<?php ++$i; ?>
		<?php endwhile; ?>

		<?php dov_the( 'button', 'bottom-btn btn' ); ?>
	</div>
</section>

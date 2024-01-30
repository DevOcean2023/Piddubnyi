<?php
/**
 * @var array{
 *     selector: string,
 *     class: string
 * } $args
 */
?>
<?php while ( dov_loop( $args['selector'] ) ) : ?>
	<?php if ( dov_has( 'images', 'title', 'text', 'form' ) ) : ?>
		<section class="blog-newsletter <?php echo esc_attr( $args['class'] ); ?>">
			<div class="blog-newsletter__wrapper">
				<div class="blog-newsletter__content">
					<?php while ( dov_loop( 'images', '<div class="blog-newsletter__images">' ) ) : ?>
						<?php dov_the( 'image', '66x66', 'blog-newsletter__image' ); ?>
					<?php endwhile; ?>
					<?php dov_the( 'title', 'blog-newsletter__title' ); ?>
					<?php dov_the( 'text', '<p class="blog-newsletter__description">' ); ?>
				</div>
				<?php dov_the( 'form' ); ?>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

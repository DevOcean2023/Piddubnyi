<section class="blog-articles">
	<div class="inner">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts', 'theme' ); ?></h2>
		<div class="blog-articles__wrapper">
			<div class="blog-articles__content">
				<h2 class="blog-articles__title"><?php esc_html_e( 'Інші статті', 'theme' ); ?></h2>
				<?php if ( have_posts() ) : ?>
					<ul class="blog-articles__items">
						<?php while ( have_posts() ) : ?>
							<?php the_post(); ?>
							<?php get_template_part( 'template-parts/blog/blog-item' ); ?>
						<?php endwhile; ?>
					</ul>
				<?php else : ?>
					<p><?php esc_html_e( 'Нічого не знайдено', 'theme' ); ?></p>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/blog/blog-sidebar' ); ?>
		</div>
		<?php dov_the_pagination(); ?>
	</div>
</section>

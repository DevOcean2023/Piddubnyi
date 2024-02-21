
<li class="blog-articles__item">
	<article class="blog-article accessibility-card">
		<div class="blog-article__thumbnail">
			<?php
			the_post_thumbnail(
				'299x0',
				array(
					'class' => 'blog-article__image',
				),
			);
			?>
		</div>
		<div class="blog-article__content">
			<div class="blog-article__posted">
				<?php echo get_the_date( 'F j, Y' ); ?> |
				<?php
				echo wp_kses(
					preg_replace(
						'/<a /',
						'<a class="blog-article-categories__link" ',
						get_the_category_list( ' ' )
					),
					DOV_KSES::get_by_tag( 'span' )
				);
				?>
			</div>
			<?php if ( has_category() ) : ?>
				<aside class="blog-article-categories blog-article__categories" aria-label="Categories">
					<?php
					echo wp_kses(
						preg_replace(
							'/<a /',
							'<a class="blog-article-categories__link" ',
							get_the_category_list( ' ' )
						),
						DOV_KSES::get_by_tag( 'a' )
					);
					?>
				</aside>
			<?php endif; ?>
			<h2 class="blog-article__title title">
				<a class="blog-article__link" href="<?php the_permalink(); ?>"
				   aria-describedby="read-more-<?php the_ID(); ?>">
					<?php the_title(); ?>
				</a>
			</h2>
			<p class="blog-article__except">
				<?php dov_the_excerpt( 160, false, true, '...' ); ?>
			</p>
			<a href="<?php the_permalink(); ?>" class="blog-article__more"><?php esc_html_e( 'Читати більше', 'theme' ); ?></a>
		</div>
	</article>
</li>

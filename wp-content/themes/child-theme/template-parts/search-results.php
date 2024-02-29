<?php
global $wp_query;

$current_per_page = $wp_query->get( 'posts_per_page' );
$current_paged    = $wp_query->get( 'paged' );
$base             = $current_paged ? $current_per_page * ( $current_paged - 1 ) : 0;
$showing          = sprintf( // translators: %1$d min number showing posts, %2$d max number showing posts, %3$d all count found posts
	esc_html__( 'Показано %1$d з %2$d результатів', 'theme' ),
	$base + 1,
	$base + $wp_query->post_count,
	$wp_query->found_posts
);
?>
<section class="search-results">
	<div class="inner">
		<div class="search-results__results">
			<h2 class="search-results__title">
				<?php esc_html_e( 'Результати пошуку для: ', 'theme' ); ?>
				<span class="search-results__for"><?php echo esc_html( get_search_query( false ) ); ?></span>
			</h2>
			<p class="search-results__pages"><?php echo esc_html( $showing ); ?></p>
		</div>
		<ul class="search-results__wrapper">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
				<li class="search-result">
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/search-item' ); ?>
				</li>
				<?php endwhile; ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Нічого не знайдено', 'theme' ); ?></p>
			<?php endif; ?>
		</ul>
		<?php dov_the_pagination(); ?>
	</div>
</section>

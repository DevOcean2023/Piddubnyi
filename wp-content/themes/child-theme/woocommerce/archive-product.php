<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>
<?php

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
	<div class="shop-page">
	<div class="inner">
		<div class="shop-page__sidebar">
			<a href="#" class="shop-page__filter-open-btn"><?php echo esc_html( 'Фільтр' ); ?></a>
			<div class="shop-page__filter-holder">
				<a href="#" class="shop-page__filter-close-btn">
					<span class="screen-reader-text"><?php echo esc_html( 'Закрити' ); ?></span>
				</a>
				<?php echo do_shortcode( '[wpf-filters id=1]' ); ?>
			</div>
		</div>

		<div class="shop-page__products-holder">
			<header class="woocommerce-products-header">
				<?php if ( is_product_category( 'sale' ) && dov_get( 'dov_banner_background' ) ) : ?>
					<div class="sale-banner">
						<?php dov_the( 'dov_banner_image', '<figure class="sale-logo-holder">' ); ?>
						<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
							<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
						<?php endif; ?>
						<?php dov_the( 'dov_banner_background', 'object-fit object-fit-cover' ); ?>
					</div>
				<?php else : ?>
					<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
						<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
					<?php endif; ?>
				<?php endif; ?>

				<?php
				/**
				 * Hook: woocommerce_archive_description.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				do_action( 'woocommerce_archive_description' );

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked woocommerce_output_all_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
				?>
				<div class="filter-results-holder">
					<?php echo do_shortcode( '[wpf-selected-filters id=1]' ); ?>
				</div>
			</header>
			<?php
			if ( woocommerce_product_loop() ) {

				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();

						/**
						 * Hook: woocommerce_shop_loop.
						 */
						do_action( 'woocommerce_shop_loop' );

						wc_get_template_part( 'content', 'product' );
					}
				}

				woocommerce_product_loop_end();

				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			}

			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );

			?>

		</div>
	</div>

<?php if ( is_product_category() ) : ?>
	<?php
	$viewed_products = ! empty( $_COOKIE['recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['recently_viewed'] ) ) : array();
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
	if ( $viewed_products ) :
		?>
		<div class="recently-products">
			<div class="inner">
				<h2><?php esc_html_e( 'Недавно переглянуті товари', 'woocommerce' ); ?></h2>
				<div class="slider-holder">
					<div class="product-cards-slider" data-slider="product-cards-slider">
						<?php foreach ( $viewed_products as $_id ) : ?>
							<div class="product-cards-slider__slide">
								<?php
								$post_object = get_post( $_id );

								setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

								wc_get_template_part( 'content', 'product' );
								?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php
get_footer( 'shop' );

<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>
<?php
$image_alt = $product->get_title();
if ( $product->get_image_id() ) {
	$url = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
} else {
	$url = wc_placeholder_img_src( 'full' );
}
?>

<div class="product-images swiper">
	<div class="swiper-wrapper">
	<?php
	echo '<div class="product-img swiper-slide">
				<img alt="' . esc_html( $image_alt ) . '" src="' . esc_url( $url ) . '">
			</div>';
	do_action( 'woocommerce_product_thumbnails' );
	?>
	</div>
</div>
<!-- / product-images -->
<?php
if ( $product->get_image_id() ) :
	$thumb_url = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
else :
	$thumb_url = wc_placeholder_img_src( 'medium' );
endif;
?>
<div class="thumbs-holder">
	<div thumbsSlider="" class="swiper swiper-thumbs">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_html( $image_alt ); ?>">
			</div>
			<?php $attachment_ids = $product->get_gallery_image_ids(); ?>
			<?php if ( $attachment_ids ) : ?>
				<?php foreach ( $attachment_ids as $attachment_id ) : ?>
					<div class="swiper-slide">
						<img src="<?php echo esc_html( wp_get_attachment_image_src( $attachment_id, 'medium' )[0] ); ?>">
					</div>
				<?php endforeach ?>
			<?php endif ?>

		</div>
	</div>
</div>
<!-- /.thumbs-holder -->

<footer class="page-footer">
	<div class="inner">
		<div class="page-footer__top">
			<div class="page-footer__content">
				<div class="logo">
					<a href="/">
						<?php dov_the( 'dov_logo_footer', '193x0' ); ?>
					</a>
				</div>
				<div class="page-footer__contacts">
					<?php while ( dov_loop( 'dov_header_phone', '<p class="page-footer__phone">' ) ) : ?>
						<?php dov_the( 'link', 'page-footer__link-phone' ); ?>
					<?php endwhile; ?>
					<?php while ( dov_loop( 'dov_footer_address', '<p class="page-footer__address">' ) ) : ?>
						<?php dov_the( 'link', 'page-footer__a' ); ?>
					<?php endwhile; ?>
					<?php while ( dov_loop( 'dov_header_time', '<div class="page-footer__time">' ) ) : ?>
						<?php dov_the( 'time', '<p class="page-footer__time-item">' ); ?>
					<?php endwhile; ?>
				</div>
			</div>
			<?php dov_the_nav( 'Footer Main' ); ?>
			<div class="page-footer__social">
				<?php dov_the( 'dov_footer_social_title' ); ?>
				<?php get_template_part( 'template-parts/social-links', null, array( 'class' => 'social-links_toggle' ) ); ?>
			</div>
		</div>
		<div class="page-footer__bottom">
			<div class="page-footer__wrapper">
				<?php dov_the( 'dov_footer_copyright', '<p class="page-footer__copyright">' ); ?>
			</div>
			<div class="page-footer__by">
				<?php dov_the_by(); ?>
			</div>
			<div class="page-footer__terms">
				<div class="page-footer__pay">
					<?php dov_the( 'dov_footer_image_pay', '79x0' ); ?>
				</div>
				<?php dov_the_nav( 'Footer Links' ); ?>
			</div>
		</div>
	</div>
</footer>




<dialog id="popup-mini-cart">
	<div class="popup">
		<div class="popup__content">
			<?php woocommerce_mini_cart(); ?>
		</div>
		<button class="popup__close-btn" type="button">
			<span class="popup__close-text">Close</span>
		</button>
	</div>
</dialog>

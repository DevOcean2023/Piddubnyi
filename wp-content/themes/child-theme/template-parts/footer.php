<footer class="page-footer">
	<div class="inner">
		<div class="page-footer__top">
			<?php dov_the_nav( 'Footer Main' ); ?>
			<div class="page-footer__content">
				<?php dov_the( 'dov_footer_text', '<div class="page-footer__contacts">' ); ?>
				<?php get_template_part( 'template-parts/social-links', null, array( 'class' => 'social-links_toggle' ) ); ?>
			</div>
		</div>
		<div class="page-footer__bottom">
			<div class="page-footer__wrapper">
				<?php dov_the( 'dov_footer_copyright', '<p class="page-footer__copyright">' ); ?>
				<?php dov_the_nav( 'Footer Links' ); ?>
			</div>
			<div class="page-footer__by">
				<?php dov_the_by(); ?>
			</div>
		</div>
	</div>
</footer>

<dialog id="mini-cart">
	<div class="popup">
		<div class="popup__content">
<!--			<h2 class="popup__title">Mini Cart</h2>-->
			<?php woocommerce_mini_cart(); ?>
		</div>
		<button class="popup__close-btn" type="button">
			<svg class="popup__close-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
				 viewBox="0 0 18 18">
				<path
					d="M.93 18a.926.926 0 0 1-.654-1.581L16.422.27a.926.926 0 1 1 1.31 1.31L1.584 17.728A.929.929 0 0 1 .929 18Z"></path>
				<path
					d="M17.078 18a.919.919 0 0 1-.654-.272L.275 1.581a.926.926 0 0 1 1.31-1.31L17.732 16.42A.926.926 0 0 1 17.078 18Z"></path>
			</svg>
			<span class="popup__close-text">Close</span>
		</button>
	</div>
</dialog>
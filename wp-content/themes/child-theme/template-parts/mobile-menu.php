<aside class="mobile-menu" id="mobile-menu" aria-hidden="true">
	<div class="mobile-menu__wrapper">
		<?php dov_the( 'dov_header_button', 'btn' ); ?>
		<?php dov_the_nav( 'Header Mobile', true, array( 'expend' => true ) ); ?>
		<?php dov_the_nav( 'Header Second' ); ?>
		<?php get_template_part( 'template-parts/social-links-header', null, array( 'class' => 'social-links_toggle' ) ); ?>
	</div>
</aside>

<section class="home-banner">
	<?php while ( dov_loop( 'banner_slider', '<div class="banner-slider" data-slider="banner-slider">' ) ) : ?>

		<div>
			<div class="home-banner__item">
				<div class="home-banner__wrap">
					<?php dov_the( 'fon_desktop', array( 'class' => 'desktop' ) ); ?>
					<?php dov_the( 'fon_mobile', array( 'class' => 'mobile' ) ); ?>
					<div class="home-banner__text">
						<?php dov_the( 'title', 'home-banner__title' ); ?>
						<?php dov_the( 'link', 'btn' ); ?>
					</div>
				</div>
				<div class="home-banner__image">
					<?php dov_the( 'background_desktop', array( 'class' => 'desktop' ) ); ?>
					<?php dov_the( 'background_mobile', array( 'class' => 'mobile' ) ); ?>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</section>

<section class="home-about">
	<div class="inner">
		<div class="home-about__wrapper">
			<?php dov_the( 'title', 'home-about__title' ); ?>
			<div class="home-about__top">
				<div class="home-about__top-left">
					<?php dov_the( 'text_left' ); ?>
				</div>
				<div class="home-about__top-center">
					<?php dov_the( 'text_center' ); ?>
				</div>
				<div class="home-about__top-right">
					<?php dov_the( 'button', 'btn' ); ?>
				</div>
			</div>
			<div class="home-about__image">
				<?php dov_the( 'image_desktop', 'desktop', '1280x0' ); ?>
				<?php dov_the( 'image_mobile', 'mobile', '341x0' ); ?>
			</div>
		</div>
	</div>
</section>

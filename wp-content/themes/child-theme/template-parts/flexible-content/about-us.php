<section class="about-us">
	<div class="inner">
		<div class="about-us__left">
			<?php while ( dov_loop( 'items', '<div class="about-us__left-items">' ) ) : ?>
				<div class="about-us__left-item">
					<?php dov_the( 'title', '<p class="about-us__left-item-title">' ); ?>
					<?php dov_the( 'text', '<p class="about-us__left-item-text">' ); ?>
				</div>
			<?php endwhile; ?>
			<div class="about-us__left-info">
				<?php dov_the( 'title', 'about-us__left-info-title' ); ?>
				<?php dov_the( 'text' ); ?>
			</div>
		</div>
		<div class="about-us__right">
			<?php dov_the( 'image', '519x0' ); ?>
		</div>
	</div>
</section>

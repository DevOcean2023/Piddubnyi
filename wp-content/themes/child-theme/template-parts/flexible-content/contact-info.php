<section class="contact-info">
	<div class="inner">
		<?php while ( dov_loop( 'items', '<div class="contact-info__items">' ) ) : ?>
			<div class="contact-info__item">
				<?php dov_the( 'title', 'contact-info__item-title' ); ?>
				<?php $class = dov_get( 'class' ); ?>
				<?php if ( 'contact-info__item-address' === $class ) : ?>
					<p class="contact-info__item-text <?php dov_the( 'class' ); ?>">
						<?php dov_the( 'info_text' ); ?>
					</p>
				<?php endif; ?>
				<?php if ( 'contact-info__item-time' === $class ) : ?>
					<p class="contact-info__item-text <?php dov_the( 'class' ); ?>">
						<?php dov_the( 'info_text' ); ?>
					</p>
				<?php endif; ?>
				<?php if ( 'contact-info__item-phone' === $class ) : ?>
					<?php dov_the( 'info_phone', 'contact-info__item-text contact-info__item-phone' ); ?>
				<?php endif; ?>
				<?php if ( 'contact-info__item-mail' === $class ) : ?>
					<?php dov_the( 'info_mail', 'contact-info__item-text contact-info__item-mail' ); ?>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
	<?php dov_the( 'map' ); ?>
</section>

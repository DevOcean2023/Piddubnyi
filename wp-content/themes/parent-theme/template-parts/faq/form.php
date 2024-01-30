<div class="faq__search-block">
	<?php while ( dov_loop( 'faq_custom_fields' ) ) : ?>
		<?php
		dov_the( 'image', '1170x216', 'object-fit object-fit-cover', );
		dov_the( 'title', 'faq__title' );
		?>
		<form class="faq__search-form form" role="search" method="get" action="/">
			<div class="form__item">
				<label class="form__label" for="faq-search-field"><?php dov_the( 'search_field_label' ); ?></label>
				<input class="form__input" id="faq-search-field" type="search" value="" name="s">
				<input class="form__reset" type="reset" value="&#x2715;">
			</div>
			<input class="form__submit btn" type="submit" value="<?php dov_the( 'search_button' ); ?>">
		</form>
	<?php endwhile; ?>
</div>

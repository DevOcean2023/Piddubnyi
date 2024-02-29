<?php while ( dov_loop( 'dov_search_form' ) ) : ?>
	<section class="search-form search-form_search-results-page">
		<div class="search-form__wrapper inner">
			<?php dov_the( 'background', '1170x0', 'search-form__image' ); ?>
			<?php dov_the( 'search_form_title', 'search-form__title' ); ?>
			<form class="search-form__form" role="search" action="<?php echo esc_url( home_url() ); ?>">
				<label class="search-form__label">
					<span class="screen-reader-text"><?php esc_html_e( 'Пошук:', 'theme' ); ?></span>
					<input class="search-form__input"
						   type="search"
						   placeholder="<?php esc_attr_e( 'Напишіть Ваше запитання', 'theme' ); ?>"
						   value=""
						   name="s">
				</label>
				<input class="search-form__submit" type="submit" value="<?php esc_attr_e( 'Пошук:', 'theme' ); ?>">
			</form>
		</div>
	</section>
<?php endwhile; ?>

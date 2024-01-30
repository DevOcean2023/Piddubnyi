<?php while ( dov_loop( 'dov_404' ) ) : ?>
	<section class="error-404">
		<div class="inner">
			<?php dov_the( 'image', '500x0', '<div class="error-404__image">' ); ?>
			<?php dov_the( 'title', 'error-404__title' ); ?>
			<?php dov_the( 'text', '', 'error-404__text' ); ?>
			<?php while ( dov_loop( 'links', '<div class="error-404__links">' ) ) : ?>
				<?php dov_the( 'link', 'error-404__link' ); ?>
			<?php endwhile; ?>
			<?php while ( dov_loop( 'buttons', '<div class="error-404__buttons">' ) ) : ?>
				<?php dov_the( 'button', 'error-404__button btn' ); ?>
			<?php endwhile; ?>
		</div>
	</section>
	<?php if ( dov_has( 'search_form_enabled' ) ) : ?>
		<section class="search-form search-form_error-404-page">
			<div class="search-form__wrapper inner">
				<?php dov_the( 'search_form_title', 'search-form__title' ); ?>
				<form class="search-form__form" role="search" action="<?php echo esc_url( home_url() ); ?>">
					<label class="search-form__label">
						<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'theme' ); ?></span>
						<input class="search-form__input"
							   type="search"
							   placeholder="<?php esc_attr_e( 'Type your question', 'theme' ); ?>"
							   value=""
							   name="s">
					</label>
					<input class="search-form__submit" type="submit" value="<?php esc_attr_e( 'Go!', 'theme' ); ?>">
				</form>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

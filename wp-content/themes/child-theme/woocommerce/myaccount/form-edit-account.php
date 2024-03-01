<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>
<div class="wrapper-form-account">
	<div class="wrap-head">
		<h2 class="title-my-account"><?php esc_html_e( 'Контактні дані', 'woocommerce' ); ?></h2>

		<form class="woocommerce-EditAccountForm edit-account" action=""
			  method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

			<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
			<div class="wrapper-edit-form">
				<div class="edit-first-form">
					<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
						<label for="account_first_name"><?php esc_html_e( 'Ім’я', 'woocommerce' ); ?>&nbsp;<span
									class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text deactive"
							   name="account_first_name"
							   placeholder="<?php esc_attr_e( 'Ваше Ім’я*', 'woocommerce' ); ?>"
							   id="account_first_name" autocomplete="given-name"
							   value="<?php echo esc_attr( $user->first_name ); ?>"
							   readonly/>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
						<label for="account_last_name"><?php esc_html_e( 'Прізвище', 'woocommerce' ); ?>
							&nbsp;<span
									class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text deactive"
							   name="account_last_name"
							   placeholder="<?php esc_attr_e( 'Ваше прізвище*', 'woocommerce' ); ?>"
							   id="account_last_name" autocomplete="family-name"
							   value="<?php echo esc_attr( $user->last_name ); ?>"
							   readonly/>
					</p>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide account-display-name">
						<label for="account_display_name"><?php esc_html_e( 'Відображувальне Ім’я', 'woocommerce' ); ?>
							&nbsp;<span
									class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text deactive"
							   name="account_display_name"
							   placeholder="<?php esc_attr_e( 'Відображувальне Ім’я*', 'woocommerce' ); ?>"
							   id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>"
							   readonly/>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="account_phone"><?php esc_html_e( 'Телефон', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="tel" class="woocommerce-Input woocommerce-Input--text input-text deactive phone-field" name="account_phone" placeholder="<?php esc_attr_e( 'Телефон*', 'woocommerce' ); ?>" id="account_phone" value="<?php echo esc_attr( get_user_meta( $user->ID, 'billing_phone', true ) ); ?>" />
					</p>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="account_email"><?php esc_html_e( 'Email', 'woocommerce' ); ?>&nbsp;<span
									class="required">*</span></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--email input-text deactive"
							   name="account_email" placeholder="<?php esc_attr_e( 'Email*', 'woocommerce' ); ?>"
							   id="account_email" autocomplete="email"
							   value="<?php echo esc_attr( $user->user_email ); ?>"
							   readonly/>
					</p>
				</div>
				<div class="password-fields" style="display: none;">
					<fieldset>
						<legend><?php esc_html_e( 'Пароль', 'woocommerce' ); ?></legend>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
								   name="password_current"
								   placeholder="<?php esc_attr_e( 'Поточний пароль*', 'woocommerce' ); ?>"
								   id="password_current"
								   autocomplete="off"/>
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
								   name="password_1"
								   placeholder="<?php esc_attr_e( 'Новий пароль*', 'woocommerce' ); ?>"
								   id="password_1" autocomplete="off"/>
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
								   name="password_2"
								   placeholder="<?php esc_attr_e( 'Підтвердження паролю*', 'woocommerce' ); ?>"
								   id="password_2" autocomplete="off"/>
						</p>
					</fieldset>
				</div>
			</div>
			<?php do_action( 'woocommerce_edit_account_form' ); ?>
			<p>
				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
				<button type="submit"
						class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
						name="save_account_details"
						value="<?php esc_attr_e( 'Зберегти', 'woocommerce' ); ?>"><?php esc_html_e( 'Зберегти', 'woocommerce' ); ?></button>
				<input type="hidden" name="action" value="save_account_details"/>
			</p>

			<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
		</form>
	</div>
	<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
	<button type="button" class="edit-fields-link"
			value="<?php esc_attr_e( 'Редагувати дані', 'woocommerce' ); ?>"><?php esc_html_e( 'Редагувати дані', 'woocommerce' ); ?></button>
</div>

<?php

class DOV_ACF_WYSIWYG_Height_Base {
	public static function init() : void {
		add_action(
			'acf/render_field_settings/type=wysiwyg',
			array( static::class, 'settings' )
		);
		add_action(
			'acf/prepare_field/type=wysiwyg',
			array( static::class, 'add_class' )
		);
		add_action(
			'acf/input/admin_head',
			array( static::class, 'styles' )
		);
		add_action(
			'acf/input/admin_footer',
			array( static::class, 'script' )
		);
	}

	public static function settings( $field ) : void {
		acf_render_field_setting(
			$field,
			array(
				'label' => __( 'Height', 'theme' ),
				'name'  => 'dov_height',
				'type'  => 'text',
			)
		);
	}

	public static function add_class( array $field ) : array {
		$height = absint( $field['dov_height'] ?? '0' );
		if ( $height ) {
			$field['wrapper']['class'] .= ' height-' . $height;
		}

		return $field;
	}

	public static function styles() : void {
		?>
		<style>
			.acf-field-wysiwyg iframe,
			.acf-field-wysiwyg textarea {
				min-height: auto;
			}
		</style>
		<?php
	}

	public static function script() : void {
		?>
		<script>
			( function ($) {
				$('.acf-field-wysiwyg[class*="height-"]').each(function () {
					const $field = $(this);
					const height = getHeight($field);
					if (height) {
						$field.find('textarea, iframe').css({
							'height': height + 'px',
							'min-height': height + 'px',
						});
					}
				});

				acf.add_action('wysiwyg_tinymce_init', function (ed, id, init, $field) {
					const height = getHeight($field);
					if (height) {
						$field.find('textarea, iframe').css({
							'height': height + 'px',
							'min-height': height + 'px',
						});
					}
				});

				acf.add_filter('wysiwyg_tinymce_settings', function (init, id, $field) {
					const height = getHeight($field);
					if (height) {
						init.height = height;
					}

					return init;
				});

				function getHeight($field) {
					const match = $field.attr('class').match(/ height-(\d+)/);

					return match ? parseInt(match[1], 10) : 0;
				}
			} )(jQuery);
		</script>
		<?php
	}
}

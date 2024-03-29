<?php

class DOV_ACF_Replace_Text_Field_Base extends acf_field {
	public static bool $has_main_auto_title = false;

	public function initialize() : void {
		$this->name     = 'replace_text';
		$this->label    = __( 'Replace Text', 'theme' );
		$this->defaults = array(
			'replace'       => '',
			'default_value' => '',
			'maxlength'     => '',
			'placeholder'   => '',
			'rows'          => '2',
		);
	}

	/** @noinspection DuplicatedCode */
	public function render_field( array $field ) : void {
		$atts  = array();
		$keys  = array( 'id', 'class', 'name', 'value', 'placeholder', 'rows', 'maxlength' );
		$keys2 = array( 'readonly', 'disabled', 'required' );
		foreach ( $keys as $k ) {
			if ( isset( $field[ $k ] ) ) {
				$atts[ $k ] = $field[ $k ];
			}
		}
		foreach ( $keys2 as $k ) {
			if ( ! empty( $field[ $k ] ) ) {
				$atts[ $k ] = $k;
			}
		}
		$atts = acf_clean_atts( $atts );
		acf_textarea_input( $atts );
	}

	public function render_field_settings( array $field ) : void {
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Replace', 'theme' ),
				'instructions' => __(
					'If you use this parameter, do not forget to add a description of what will happen.
For example: [text] - bold style<br>If you need multiple substitutes, you can write them as follows: <code>[:strong:], {:em:}</code>',
					'theme'
				),
				'type'         => 'text',
				'name'         => 'replace',
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Default Value', 'theme' ),
				'instructions' => __( 'Appears when creating a new post', 'theme' ),
				'type'         => 'textarea',
				'name'         => 'default_value',
				'rows'         => $this->defaults['rows'],
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Placeholder Text', 'theme' ),
				'instructions' => __( 'Appears within the input', 'theme' ),
				'type'         => 'text',
				'name'         => 'placeholder',
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Character Limit', 'theme' ),
				'instructions' => __( 'Leave blank for no limit', 'theme' ),
				'type'         => 'number',
				'name'         => 'maxlength',
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Rows', 'theme' ),
				'instructions' => __( 'Sets the textarea height', 'theme' ),
				'type'         => 'number',
				'name'         => 'rows',
				'placeholder'  => $this->defaults['rows'],
			)
		);
	}

	protected function pre_formatting( ?string $value, array $field ) : string {
		if ( empty( $value ) ) {
			return '';
		}

		if ( ! empty( $field['replace'] ) ) {
			$replaces = array_map( 'trim', explode( ',', $field['replace'] ) );
			foreach ( $replaces as $replace ) {
				$search_start = '[';
				$search_end   = ']';
				if ( str_contains( $replace, ':' ) ) {
					// This is not a valid error, it is not short array.
					// phpcs:ignore Generic.Arrays.DisallowShortArraySyntax.Found
					[ $search_start, $replace, $search_end ] = explode( ':', $replace );
				}

				$value = str_replace(
					array( $search_start, $search_end ),
					array( '<' . $replace . '>', '</' . $replace . '>' ),
					$value
				);
			}
		}

		return nl2br( $value, ! current_theme_supports( 'html5', 'script' ) );
	}

	/** @noinspection PhpUnusedParameterInspection */
	public function format_value( ?string $value, $post_id, array $field ) : string {
		return $this->pre_formatting( $value, $field );
	}
}

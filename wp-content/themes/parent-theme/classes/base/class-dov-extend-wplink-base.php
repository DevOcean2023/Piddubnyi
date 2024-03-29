<?php

class DOV_Extend_WPLink_Base {
	public static function init() : void {
		add_action( 'admin_init', array( static::class, 'remove_render_field' ) );
		add_filter( 'acf/render_field/type=link', array( static::class, 'render_field' ), 8 );
		add_filter( 'acf/format_value/type=link', array( static::class, 'format_value' ), 8, 3 );
		add_action( 'wp_tiny_mce_init', array( static::class, 'add_inputs_in_popup' ) );
		add_action( 'admin_enqueue_scripts', array( static::class, 'enqueue' ) );
		add_filter( 'wp_link_query_args', array( static::class, 'filter_link_query_args' ) );
		add_filter( 'wp_link_query', array( static::class, 'filter_link_query' ) );
	}

	public static function remove_render_field() : void {
		$link = acf_get_field_type( 'link' );
		remove_filter( 'acf/render_field/type=link', array( $link, 'render_field' ), 9 );
	}

	public static function render_field( array $field ) : void {
		$div = array(
			'id'    => $field['id'],
			'class' => $field['class'] . ' acf-link',
		);
		acf_enqueue_uploader();
		$link = static::set_default( $field['value'] );
		if ( $link['url'] ) {
			$div['class'] .= ' -value';
		}
		if ( '_blank' === $link['target'] ) {
			$div['class'] .= ' -external';
		}
		?>
		<div <?php acf_esc_attrs( $div ); ?>>
			<div class="acf-hidden">
				<a class="link-node" href="<?php echo esc_url( $link['url'] ); ?>"
				   target="<?php echo esc_attr( $link['target'] ); ?>"
				   data-class="<?php echo esc_attr( $link['class'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
				<?php foreach ( $link as $k => $v ) : ?>
					<?php
					acf_hidden_input(
						array(
							'class' => "input-$k",
							'name'  => $field['name'] . "[$k]",
							'value' => $v,
						)
					);
					?>
				<?php endforeach; ?>
			</div>
			<a href="#" class="button" data-name="add" target="">
				<?php esc_html_e( 'Select Link', 'theme' ); ?>
			</a>
			<div class="link-wrap">
				<span class="link-title"><?php echo esc_html( $link['title'] ); ?></span>
				<a class="link-url" href="<?php echo esc_url( $link['url'] ); ?>"
				   target="_blank"><?php echo esc_html( $link['url'] ); ?></a>
				<i class="acf-icon -link-ext acf-js-tooltip"
				   title="<?php esc_attr_e( 'Opens in a new window/tab', 'theme' ); ?>"></i>
				<a class="acf-icon -pencil -clear acf-js-tooltip" data-name="edit" href="#"
				   title="<?php esc_attr_e( 'Edit', 'theme' ); ?>"></a>
				<a class="acf-icon -cancel -clear acf-js-tooltip" data-name="remove" href="#"
				   title="<?php esc_attr_e( 'Remove', 'theme' ); ?>"></a>
			</div>
		</div>
		<?php
	}

	public static function set_default( $value ) : array {
		$link = array(
			'title'  => '',
			'url'    => '',
			'target' => '',
			'class'  => '',
		);
		if ( is_array( $value ) ) {
			$link = array_merge( $link, $value );
		}

		return $link;
	}

	public static function enqueue() : void {
		wp_enqueue_script(
			'dov-extend-wplink',
			DOV_File::get_parent_url( 'js/dov-extend-wplink.js' ),
			array( 'wplink' ),
			DOV_Theme::get_version(),
			true
		);
	}

	/** @noinspection PhpUnusedParameterInspection */
	public static function format_value( $value, $post_id, array $field ) : ?array {
		if ( ! empty( $value ) ) {
			$value = static::set_default( $value );
			if ( 'url' === $field['return_format'] ) {
				$value = $value['url'];
			}
		}

		return is_array( $value ) ? $value : null;
	}

	public static function add_inputs_in_popup() : void {
		if ( ! class_exists( '_WP_Editors' ) ) {
			require ABSPATH . WPINC . '/class-wp-editor.php';
		}
		ob_start();
		_WP_Editors::wp_link_dialog();
		$dialog = ob_get_clean();
		if ( $dialog ) {
			$inputs = '
				<div>
				<label><span>Link Class</span> <input id="wp-link-class" type="text" style="width: 53%;"></label>
				<label><input id="wp-link-btn" type="checkbox"> Is Button</label>
				</div>
				<div class="link-target">
			';

			// Our inclusion is secure, WordPress itself takes care of the rest.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo str_replace( '<div class="link-target">', $inputs, $dialog );
		}
	}

	public static function filter_link_query_args( array $query ) : array {
		add_filter( 'posts_search', array( static::class, 'filter_link_search' ), 10, 2 );
		$query['post_status']      = array( 'publish', 'inherit' );
		$query['suppress_filters'] = false;

		return $query;
	}

	public static function filter_link_query( array $results ) : array {
		remove_filter( 'posts_search', array( static::class, 'filter_link_search' ) );
		foreach ( $results as $k => $result ) {
			if ( 'Media' === $result['info'] ) {
				$url                        = get_the_guid( $result['ID'] );
				$results[ $k ]['title']     = $result['title'] . ' [ ' . basename( $url ) . ' ]';
				$results[ $k ]['permalink'] = $url;
			}
		}

		return $results;
	}

	public static function filter_link_search( string $search, WP_Query $query ) : array|string {
		global $wpdb;

		$search_query = $wpdb->esc_like( $query->query_vars['s'] );

		return str_replace(
			'AND ((',
			"AND ((($wpdb->posts.guid LIKE '%$search_query%') OR ",
			$search
		);
	}
}

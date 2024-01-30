<?php
/**
 * ACF helpers
 */
if ( ! function_exists( 'dov_get' ) ) {
	function dov_get( string $selector, ...$args ) : string {
		return DOV_Fields::get( $selector, $args );
	}
}
if ( ! function_exists( 'dov_the' ) ) {
	function dov_the( string $selector, ...$args ) : void {
		echo dov_get( $selector, ...$args );
	}
}
if ( ! function_exists( 'dov_get_value' ) ) {
	function dov_get_value( string $key = '' ) : string {
		return DOV_Fields::get_value( $key );
	}
}
if ( ! function_exists( 'dov_the_value' ) ) {
	function dov_the_value( string $key = '' ) : void {
		echo dov_get_value( $key );
	}
}
if ( ! function_exists( 'dov_get_loop_index' ) ) {
	function dov_get_loop_index() : int {
		return DOV_Fields::get_loop_index();
	}
}
if ( ! function_exists( 'dov_get_index_attr' ) ) {
	function dov_get_index_attr( $before = '', $after = '' ) : string {
		return implode(
			'-',
			array_filter(
				array(
					$before,
					DOV_ACF_Flex_Content::get_index(),
					DOV_Fields::get_loop_index(),
					$after,
				)
			)
		);
	}
}
if ( ! function_exists( 'dov_the_index_attr' ) ) {
	function dov_the_index_attr( $before = '', $after = '' ) : void {
		echo esc_attr( dov_get_index_attr( $before, $after ) );
	}
}
if ( ! function_exists( 'dov_loop' ) ) {
	function dov_loop( $selector_or_posts_array, string $wrapper = '' ) : bool {
		return DOV_Fields::loop( $selector_or_posts_array, $wrapper );
	}
}
if ( ! function_exists( 'dov_wrap' ) ) {
	function dov_wrap( string $selector_or_url, string $class_name = '', bool $is_force = true, array $attrs = array() ) : bool {
		return DOV_Fields::wrap( $selector_or_url, $class_name, $is_force, $attrs );
	}
}
if ( ! function_exists( 'dov_has' ) ) {
	function dov_has( ...$selectors_and_maybe_conditional ) : bool {
		return DOV_Fields::has( $selectors_and_maybe_conditional );
	}
}
if ( ! function_exists( 'dov_get_as' ) ) {
	function dov_get_as( string $as_type, string $selector, ...$args ) : string {
		DOV_Fields::next_field_process_as_type( $as_type );

		return dov_get( $selector, ...$args );
	}
}
if ( ! function_exists( 'dov_the_as' ) ) {
	function dov_the_as( string $as_type, string $selector, ...$args ) : void {
		echo dov_get_as( $as_type, $selector, ...$args );
	}
}

/**
 * Templates helpers
 */
if ( ! function_exists( 'dov_get_the_excerpt' ) ) {
	function dov_get_the_excerpt( int $length, bool $filter = false, bool $trim = false, string $after = '' ) : string {
		global $post;
		if ( has_excerpt() ) {
			$output = wp_strip_all_tags( $post->post_excerpt );
		} else {
			$output = get_the_content();
			if ( empty( $output ) && DOV_Theme::$acf_enabled ) {
				$fields = get_fields( $post->ID );
				dov_array_to_excerpt( $fields, $output );
			}
			$output = wp_strip_all_tags( strip_shortcodes( $output ) );
			if ( false === $filter ) {
				$output = str_replace( array( "\r\n", "\r", "\n" ), '', $output );
				$output = trim( str_replace( '&nbsp;', ' ', $output ) );
				$output = preg_replace( '/\s+/', ' ', $output );
			}
			if ( strlen( $output ) > $length ) {
				$output = substr( $output, 0, $length );
				for ( $i = $length - 1; $i >= 0; $i-- ) {
					if ( preg_match( '/(\.|,|!|\?|:|;|\s)/', $output[ $i ] ) ) {
						$output = substr( $output, 0, $i + 1 );
						break;
					}
				}
			}
			if ( $trim ) {
				$output = rtrim( $output, '.,!?:; ' );
			}
		}
		$output = rtrim( $output, "\r\n" ) . $after;
		if ( $filter ) {
			return apply_filters( 'get_the_excerpt', $output );
		}

		return $output;
	}
}
if ( ! function_exists( 'dov_the_excerpt' ) ) {
	function dov_the_excerpt( int $length, bool $filter = false, bool $trim = false, string $after = '' ) : void {
		$output = dov_get_the_excerpt( $length, $filter, $trim, $after );
		if ( $filter ) {
			$output = apply_filters( 'the_excerpt', $output );
		}

		echo wp_kses_post( $output );
	}
}
if ( ! function_exists( 'dov_get_the_replace' ) ) {
	function dov_get_the_replace( ?string $text, string $replace = 'strong' ) : string {
		return str_replace(
			array( '[', ']' ),
			array( '<' . $replace . '>', '</' . $replace . '>' ),
			$text
		);
	}
}
if ( ! function_exists( 'dov_the_replace' ) ) {
	function dov_the_replace( ?string $text, string $replace = 'strong' ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_replace( $text, $replace );
	}
}
if ( ! function_exists( 'dov_get_the_archive_link' ) ) {
	function dov_get_the_archive_link( $post = null ) : string {
		$post = get_post( $post );
		if ( empty( $post ) ) {
			return '';
		}
		$url   = dov_get_the_archive_url( $post );
		$title = dov_get_the_archive_title( $post );
		if ( empty( $url ) || empty( $title ) ) {
			return '';
		}
		$title = __( 'Back to ', 'theme' ) . $title;
		$icon  = '
			<svg xmlns="http://www.w3.org/2000/svg" width="11" height="8">
				<path fill="currentColor" d="M.646 4.354a.5.5 0 0 1 0-.708L3.828.464a.5.5 0 1 1 .708.708L1.707 4l2.829 2.828a.5.5 0 1 1-.708.708L.646 4.354ZM11 4.5H1v-1h10v1Z"/>
			</svg>
		';

		return '<div class="blog-post__back"><a href="' . $url . '" class="blog-post__back-link">' . $icon . $title . '</a></div>';
	}
}
if ( ! function_exists( 'dov_the_archive_link' ) ) {
	function dov_the_archive_link( $post = null ) : void {
		echo wp_kses(
			dov_get_the_archive_link( $post ),
			array_merge(
				DOV_KSES::get_by_svg(),
				array(
					'div' => array(
						'class' => true,
					),
					'a'   => array(
						'class' => true,
						'href'  => true,
					),
				)
			)
		);
	}
}
if ( ! function_exists( 'dov_get_the_archive_url' ) ) {
	function dov_get_the_archive_url( $post = null ) : string {
		$post = get_post( $post );
		if ( empty( $post ) ) {
			return '';
		}
		$post_type = get_post_type( $post );
		if ( 'product' === $post_type && function_exists( 'wc_get_page_id' ) ) {
			$url = get_permalink( wc_get_page_id( 'shop' ) );
		} else {
			$url = get_post_type_archive_link( $post_type );
		}

		return (string) $url;
	}
}
if ( ! function_exists( 'dov_the_archive_url' ) ) {
	function dov_the_archive_url( $post = null ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_archive_url( $post );
	}
}
if ( ! function_exists( 'dov_get_the_archive_title' ) ) {
	function dov_get_the_archive_title( $post = null ) : string {
		$post = get_post( $post );
		if ( ! $post ) {
			return '';
		}
		$post_type = get_post_type( $post );
		if ( 'post' === $post_type ) {
			return get_the_title( get_option( 'page_for_posts' ) );
		}
		if ( 'product' === $post_type && function_exists( 'wc_get_page_id' ) ) {
			return get_the_title( wc_get_page_id( 'shop' ) );
		}
		$post_type = get_post_type_object( get_post_type( $post ) );
		if ( $post_type ) {
			return $post_type->labels->all_items;
		}

		return '';
	}
}
if ( ! function_exists( 'dov_get_the_by_seo_link' ) ) {
	function dov_get_the_by_seo_link( array $args = array() ) : string {
		$args = wp_parse_args(
			$args,
			array(
				'title' => 'Search engine optimization by:',
				'name'  => '',
				'href'  => '',
			)
		);

		return dov_get_the_by_link( $args );
	}
}
if ( ! function_exists( 'dov_the_by_seo_link' ) ) {
	function dov_the_by_seo_link( array $args = array() ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_by_seo_link( $args );
	}
}
if ( ! function_exists( 'dov_get_the_by' ) ) {
	function dov_get_the_by( array $args = array(), array $seo_args = array() ) : string {
		return dov_get_the_by_link( $args ) . dov_get_the_by_seo_link( $seo_args );
	}
}
if ( ! function_exists( 'dov_the_by' ) ) {
	function dov_the_by( array $args = array() ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_by( $args );
	}
}
if ( ! function_exists( 'dov_the_by_link' ) ) {
	function dov_the_by_link( array $args = array() ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_by_link( $args );
	}
}
if ( ! function_exists( 'dov_get_the_by_link' ) ) {
	function dov_get_the_by_link( array $args = array() ) : string {
		if ( ! is_front_page() ) {
			return '';
		}

		$theme = wp_get_theme();
		$name  = $theme->get( 'Author' );
		$href  = $theme->get( 'AuthorURI' );
		$args  = wp_parse_args(
			$args,
			array(
				'title' => 'Web Design Agency:',
				'name'  => $name,
				'href'  => $href,
				'attr'  => array(),
			)
		);

		if ( empty( $args['name'] ) || empty( $args['href'] ) ) {
			return '';
		}

		$_attr = array_filter(
			wp_parse_args(
				$args['attr'],
				array(
					'href'   => $args['href'],
					'target' => '_blank',
					'rel'    => 'noopener',
				)
			)
		);

		$_attr['class'] = 'page-footer__by-link' . ( empty( $_attr['class'] ) ? '' : ' ' . $_attr['class'] );

		$attr = '';
		foreach ( $_attr as $k => $v ) {
			$attr .= ' ' . $k . '="' . $v . '"';
		}

		return sprintf(
			'%s%s <span class="page-footer__by-name">%s</span></a>',
			wp_kses_attr( 'a', $attr, 'post', wp_allowed_protocols() ),
			esc_html( $args['title'] ),
			esc_html( $args['name'] )
		);
	}
}
if ( ! function_exists( 'dov_get_the_logo' ) ) {
	function dov_get_the_logo(
		string $selector = 'dov_header_logo',
		string $size = 'full',
		string $id = 'options',
		$sizes = ''
	) : string {
		$is_head = doing_action( 'wp_head' );
		$logo    = '';
		$image   = '';
		$attr    = array(
			'loading'       => $is_head ? 'eager' : 'lazy',
			'alt'           => get_bloginfo( 'name' ),
			'fetchpriority' => $is_head ? 'high' : 'low',
		);
		if ( $sizes ) {
			$attr['sizes'] = $sizes;
		}

		foreach ( array( '', '_2' ) as $i ) {
			$image .= DOV_Images::get_img(
				(int) get_field( $selector . $i, $id, false ),
				$size,
				$attr
			);
		}
		if ( $image ) {
			$logo .= is_front_page() ? $image : '<a href="' . home_url() . '">' . $image . '</a>';
		}

		return '<div class="logo">' . $logo . '</div>';
	}
}
if ( ! function_exists( 'dov_the_logo' ) ) {
	function dov_the_logo(
		string $selector = 'dov_header_logo',
		string $size = 'full',
		string $id = 'options',
		string $sizes = ''
	) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_logo( $selector, $size, $id, $sizes );
	}
}
if ( ! function_exists( 'dov_get_the_nav' ) ) {
	function dov_get_the_nav( string $location_label, bool $is_mobile = false, array $args = array() ) : string {
		$defaults = array(
			'keyboard' => false,
			'expend'   => false,
			'hover'    => false,
		);

		$defaults['bam_block_name'] = DOV_Nav::get_bam_block_name( $location_label );
		if ( $is_mobile ) {
			$defaults['bam_block_name'] .= '-mobile';
		}

		$container_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
			'Footer Main'   => true,
		);

		if ( isset( $container_navs[ $location_label ] ) ) {
			$defaults['container'] = 'nav';
		}

		$keyboard_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
			'Footer Main'   => true,
			'Footer Links'  => true,
		);

		if ( isset( $keyboard_navs[ $location_label ] ) ) {
			$defaults['keyboard'] = $is_mobile ? false : 'auto-close';
		}

		$expend_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
		);

		if ( isset( $expend_navs[ $location_label ] ) ) {
			$defaults['expend'] = $is_mobile ? true : 'auto-close';
		}

		$hover_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
		);

		if ( isset( $hover_navs[ $location_label ] ) ) {
			$defaults['hover'] = ! $is_mobile;
		}

		$defaults['theme_location']       = DOV_Nav::get_location( $location_label );
		$defaults['fallback_cb']          = '__return_empty_string';
		$defaults['is_mobile']            = $is_mobile;
		$defaults['echo']                 = false;
		$defaults['container_aria_label'] = $location_label;
		$defaults['items_wrap']           = '<ul class="%2$s">%3$s</ul>';
		$defaults['walker']               = new DOV_Walker_Nav_Menu();

		$args = wp_parse_args( $args, $defaults );

		$attributes = '';
		foreach ( array( 'expend', 'keyboard', 'hover' ) as $attribute ) {
			if ( ! empty( $args[ $attribute ] ) ) {
				$attributes .= ' data-' . $attribute . '-menu';
				if ( is_string( $args[ $attribute ] ) ) {
					$attributes .= '="' . $args[ $attribute ] . '"';
				}
			}
		}

		if ( $attributes ) {
			$args['items_wrap'] = str_replace( '<ul', '<ul' . $attributes, $args['items_wrap'] );
		}

		return (string) wp_nav_menu( $args );
	}
}
if ( ! function_exists( 'dov_the_nav' ) ) {
	function dov_the_nav( string $location_label, bool $is_mobile = false, array $args = array() ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_nav( $location_label, $is_mobile, $args );
	}
}
if ( ! function_exists( 'dov_nav_has_sub_menu' ) ) {
	function dov_nav_has_sub_menu( array $menu_location_labels ) : bool {
		return DOV_Nav::has_sub_menu( $menu_location_labels );
	}
}
if ( ! function_exists( 'dov_get_supported_video_url' ) ) {
	function dov_get_supported_video_url( ?string $url ) : string {
		$video_url = '';

		if ( $url ) {
			$youtube_id = DOV_YouTube::get_id( $url );
			if ( $youtube_id ) {
				$video_url = 'https://www.youtube.com/watch?v=' . $youtube_id;
			} elseif ( str_contains( $url, 'vimeo' ) ) {
				preg_match( '~vimeo\.com/(?>video/)?(\d+)~', $url, $matches );
				if ( isset( $matches[1] ) ) {
					$video_url = 'https://vimeo.com/' . $matches[1];
				}
			}
		}

		return $video_url;
	}
}
if ( ! function_exists( 'dov_get_supported_video_embed_url' ) ) {
	function dov_get_supported_video_embed_url( ?string $url ) : string {
		$video_url = '';

		if ( $url ) {
			$youtube_id = DOV_YouTube::get_id( $url );
			if ( $youtube_id ) {
				$video_url = 'https://www.youtube.com/embed/' . $youtube_id;
			} elseif ( str_contains( $url, 'vimeo' ) ) {
				preg_match( '~vimeo\.com/(?>video/)?(\d+)(\?.+)?~', $url, $matches );
				if ( isset( $matches[1] ) ) {
					if ( $matches[2] ) {
						$params = $matches[2];
					} else {
						$params = '?title=0&byline=0&portrait=0';
					}

					$video_url = 'https://player.vimeo.com/video/' . $matches[1] . $params;
				}
			}
		}

		return $video_url;
	}
}
if ( ! function_exists( 'dov_get_the_pagination' ) ) {
	/** @noinspection DuplicatedCode, HtmlUnknownTarget */
	function dov_get_the_pagination( array $args = array() ) : string {
		global $wp_query;

		$args      = apply_filters( 'dov_pagination_args', $args );
		$old_query = $wp_query;
		if ( isset( $args['query'] ) ) {
			// We deliberately change the global variable here to change the output later we reset it.
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$wp_query = $args['query'];
		}

		$format = '';
		if ( isset( $args['format'] ) ) {
			$format = trim( str_replace( '=%#%', '', $args['format'] ), '?' );
		}

		$defaults = array(
			'type'      => 'list',
			'prev_text' => esc_html__( '&#8592; Previous', 'theme' ),
			'next_text' => esc_html__( 'Next &#8594;', 'theme' ),
			'mid_size'  => 1,
		);

		$pagination = paginate_links( wp_parse_args( apply_filters( 'dov_pagination_args', $args ), $defaults ) );

		if ( ! empty( $args['first_last'] ) ) {
			$total   = $wp_query->max_num_pages;
			$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

			$first_url = get_pagenum_link();
			$last_url  = get_pagenum_link( $total );
			if ( $format ) {
				$last_url  = str_replace(
					array( '%_%', '%#%' ),
					array( $args['format'], $total ),
					trailingslashit( explode( '?', $first_url )[0] ) . '%_%'
				);
				$first_url = remove_query_arg( $format, $first_url );
			}

			$first_text  = esc_html__( 'First Page', 'theme' );
			$first_link  = sprintf( '<a href="%s" class="first-page">%s</a>', $first_url, $first_text );
			$last_text   = esc_html__( 'Last Page', 'theme' );
			$last_link   = sprintf( '<a href="%s" class="last-page">%s</a>', $last_url, $last_text );
			$pattern     = array();
			$replacement = array();

			if ( $current > 1 ) {
				$pattern[]     = '/(<ul[^>]*>)/';
				$replacement[] = "$1<li>$first_link</li>";
			}

			if ( $current < $total ) {
				$pattern[]     = '/(<\/ul>)/';
				$replacement[] = "<li>$last_link</li>$1";
			}

			$pagination = preg_replace( $pattern, $replacement, $pagination );
		}

		$pagination = str_replace(
			array( "<ul class='page-numbers" ),
			array( "<ul class='page-numbers pagination" ),
			$pagination
		);

		// We reset the variable to its initial state, changed above.
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wp_query = $old_query;

		return (string) $pagination;
	}
}
if ( ! function_exists( 'dov_the_pagination' ) ) {
	function dov_the_pagination( array $args = array() ) : void {
		// todo: Escape output.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo dov_get_the_pagination( $args );
	}
}
if ( ! function_exists( 'dov_array_to_excerpt' ) ) {
	function dov_array_to_excerpt( $values, string &$excerpt ) : void {
		$keys = array( 'text', 'content', 'title', 'subtitle' );
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) && ! isset( $value['filename'] ) ) {
						dov_array_to_excerpt( $value, $excerpt );
					} elseif ( is_string( $value ) && in_array( $key, $keys, true ) ) {
						$excerpt .= $value . "\n ";
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'dov_remove_filter_for_class' ) ) {
	function dov_remove_filter_for_class( string $hook, string $class_name, string $method, int $priority = 10 ) : void {
		global $wp_filter;

		$callbacks = $wp_filter[ $hook ][ $priority ] ?? array();
		if ( $callbacks ) {
			foreach ( $callbacks as $id => $filter ) {
				if (
					is_array( $filter['function'] ) &&
					! empty( $filter['function'][0] ) &&
					! empty( $filter['function'][1] ) &&
					$filter['function'][1] === $method
				) {
					if ( is_object( $filter['function'][0] ) ) {
						$filter_class = get_class( $filter['function'][0] );
					} else {
						$filter_class = $filter['function'][0];
					}
					if ( $class_name === $filter_class ) {
						unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $id ] );
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'dov_get_the_header_classes' ) ) {
	function dov_get_the_header_classes() : string {
		$classes = apply_filters( 'dov_header_classes', array( 'page-header' ) );
		foreach ( $classes as $i => $class ) {
			$classes[ $i ] = sanitize_html_class( $class );
		}

		return implode( ' ', array_unique( $classes ) );
	}
}
if ( ! function_exists( 'dov_add_filter_once' ) ) {
	function dov_add_filter_once( string $hook_name, callable $hook_callback, int $priority = 10, int $accepted_args = 1 ) : bool {
		$callback = static function () use ( $hook_name, $hook_callback, $priority, &$callback ) {
			remove_filter( $hook_name, $callback, $priority );

			return $hook_callback( ...func_get_args() );
		};

		return add_filter( $hook_name, $callback, $priority, $accepted_args );
	}
}
if ( ! function_exists( 'dov_add_action_once' ) ) {
	function dov_add_action_once( string $hook_name, callable $hook_callback, int $priority = 10, int $accepted_args = 1 ) : bool {
		return dov_add_filter_once( $hook_name, $hook_callback, $priority, $accepted_args );
	}
}
if ( ! function_exists( 'dov_get_attrs' ) ) {
	function dov_get_attrs( array $attrs ) : string {
		$html               = '';
		$boolean_attributes = array(
			'allowfullscreen' => true,
			'async'           => true,
			'autofocus'       => true,
			'autoplay'        => true,
			'checked'         => true,
			'controls'        => true,
			'default'         => true,
			'defer'           => true,
			'disabled'        => true,
			'formnovalidate'  => true,
			'inert'           => true,
			'ismap'           => true,
			'itemscope'       => true,
			'loop'            => true,
			'multiple'        => true,
			'muted'           => true,
			'nomodule'        => true,
			'novalidate'      => true,
			'open'            => true,
			'playsinline'     => true,
			'readonly'        => true,
			'required'        => true,
			'reversed'        => true,
			'selected'        => true,
		);

		foreach ( $attrs as $key => $value ) {
			$key = strtolower( $key );
			if ( is_string( $value ) ) {
				$value = trim( $value );
			} elseif ( is_bool( $value ) ) {
				if ( false === $value ) {
					continue;
				}

				$value = '';
			} else {
				$value = wp_json_encode( $value );
			}

			if ( $value ) {
				$html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			} elseif ( isset( $boolean_attributes[ $key ] ) || str_starts_with( $key, 'data-' ) ) {
				$html .= sprintf( ' %s', esc_attr( $key ) );
			}
		}

		return trim( $html );
	}
}
if ( ! function_exists( 'dov_the_attrs' ) ) {
	function dov_the_attrs( array $attrs ) : void {
		echo dov_get_attrs( $attrs );
	}
}
if ( ! function_exists( 'dov_get_svg' ) ) {
	function dov_get_svg( string|int $file_name_or_attachment_id, string $class_name = '' ) : string {
		$svg       = '';
		$file_path = '';

		if ( is_numeric( $file_name_or_attachment_id ) ) {
			$attachment_id = $file_name_or_attachment_id;
			if ( 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
				$file_path = get_attached_file( $attachment_id );
			}
		} else {
			$file_path = DOV_File::get_assets_path( 'images/' . $file_name_or_attachment_id );
		}

		if ( $file_path && file_exists( $file_path ) ) {
			$svg = preg_replace_callback(
				'~<svg[^>]+>~',
				static function ( $matches ) use ( $class_name ) {
					$svg_tag = $matches[0];
					if ( ! str_contains( $svg_tag, 'aria-hidden=' ) ) {
						$svg_tag = str_replace(
							'<svg ',
							'<svg aria-hidden="true" ',
							$svg_tag
						);
					}
					if ( ! str_contains( $svg_tag, 'class=' ) ) {
						$svg_tag = str_replace(
							'<svg ',
							'<svg class="' . esc_attr( $class_name ) . '" ',
							$svg_tag
						);
					} else {
						$svg_tag = preg_replace(
							'~class=([\'"])~',
							'class=$1' . esc_attr( $class_name ) . ' ',
							$svg_tag
						);
					}

					return $svg_tag;
				},
				DOV_Filesystem::get_file_contents( $file_path )
			);
		}

		return $svg;
	}
}
if ( ! function_exists( 'dov_the_svg' ) ) {
	function dov_the_svg( string $file_name, string $class_name = '' ) : void {
		echo wp_kses(
			dov_get_svg( $file_name, $class_name ),
			DOV_KSES::get_by_svg()
		);
	}
}

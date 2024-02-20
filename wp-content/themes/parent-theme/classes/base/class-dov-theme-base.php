<?php

class DOV_Theme_Base {
	public static bool $disable_file_edit = true;
	public static bool $acf_enabled       = false;
	public static bool $gf_enabled        = false;
	public static bool $yoast_enable      = false;

	public static string $parent_path = '';
	public static string $child_path  = '';
	public static string $parent_url  = '';
	public static string $child_url   = '';

	protected static array $cache_post_ids_taking_into_preview = array();
	protected static array $theme_files_list                   = array();
	protected static array $autoload_files_list                = array();

	public static function init() : void {
		static::set_variables();
		static::set_files();

		spl_autoload_register( array( static::class, 'autoloader' ) );

		static::require_file( 'inc/helpers.php' );
		static::init_classes();
		static::require_file( 'theme-functions.php' );
		static::set_hooks();
	}

	protected static function set_variables() : void {
		static::$parent_path  = wp_normalize_path( get_template_directory() ) . '/';
		static::$child_path   = wp_normalize_path( get_stylesheet_directory() ) . '/';
		static::$parent_url   = get_template_directory_uri() . '/';
		static::$child_url    = get_stylesheet_directory_uri() . '/';
		static::$acf_enabled  = function_exists( 'acf' );
		static::$gf_enabled   = class_exists( 'GFForms' );
		static::$yoast_enable = defined( 'WPSEO_FILE' );

		defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', static::$disable_file_edit );
	}

	protected static function set_files() : void {
		static::set_files_to_theme_files_list( '' );
		static::set_files_to_theme_files_list( 'inc' );
		static::set_files_to_theme_files_list( 'inc/beautify-html' );
		static::set_files_to_theme_files_list( 'classes' );
		static::set_files_to_theme_files_list( 'classes/base' );
		static::set_files_to_theme_files_list( 'classes/traits' );
	}

	protected static function set_hooks() : void {
		add_action(
			'after_setup_theme',
			array( static::class, 'after_setup_theme' )
		);
		add_filter(
			'pre_get_posts',
			array( static::class, 'removed_sticky_for_blog' )
		);

		static::removed_wptexturize();
		static::fixed_shortcode_wpautop();
	}

	protected static function init_classes() : void {
		do_action( 'dov_before_init' );

		if ( static::$acf_enabled ) {
			$init_acf_classes = apply_filters(
				'the_init_acf_classes',
				array(
					'DOV_ACF',
					'DOV_ACF_Add_Field_Helper',
					'DOV_ACF_Flex_Content',
					'DOV_ACF_Google_Maps_API',
					'DOV_ACF_Relationship_All',
					'DOV_ACF_WYSIWYG_Height',
					'DOV_ACF_Search',
					'DOV_Not_A_Page', // todo: Remove dependence on ACF.
					'DOV_Extend_WPLink',  // todo: Remove dependence on ACF.
				)
			);

			foreach ( $init_acf_classes as $class ) {
				$class::init();
			}
		}

		$init_classes = apply_filters(
			'the_init_classes',
			array(
				'DOV_Log',
				'DOV_AccessiBe_Plugin',
				'DOV_Admin_Bar',
				'DOV_Admin_Notices',
				'DOV_Admin_Panel',
				'DOV_BAM_Content',
				'DOV_BAM_Menu',
				'DOV_Minify_Beautify_HTML',
				'DOV_Clear_Cache_Menu_Order',
				'DOV_CPT',
				'DOV_Defer_Scripts',
				'DOV_Delay_Scripts',
				'DOV_Dequeue',
				'DOV_Dequeue_Scripts',
				'DOV_Enqueue_Scripts',
				'DOV_Enqueue_Styles',
				'DOV_Favicon',
				'DOV_Fix_GF_Multiple_IDs',
				'DOV_Fonts',
				'DOV_Front',
				'DOV_GA_GTM',
				'DOV_GF',
				'DOV_GF_Custom_Merge_Tags',
				'DOV_Images',
				'DOV_Importer',
				'DOV_KSES',
				'DOV_Local_External_Scripts',
				'DOV_Login_Style',
				'DOV_Nav',
				'DOV_Page_Additional_Data',
				'DOV_Preload',
				'DOV_Preload_Logo',
				'DOV_QM',
				'DOV_Security_Headers',
				'DOV_Site_Map',
				'DOV_Styles_Shortcode',
				'DOV_SVG',
				'DOV_Tax',
				'DOV_TinyMCE',
				'DOV_Widgets',
				'DOV_Yoast',
				'DOV_Yoast_SEO_Score_Fix',
			)
		);

		foreach ( $init_classes as $class ) {
			$class::init();
		}

		do_action( 'dov_init' );
	}

	public static function after_setup_theme() : void {
		static::load_theme_textdomain();
		static::add_theme_supports();

		static::require_file( 'inc/scripts-and-styles.php' );
	}

	public static function get_version() : string {
		static $version;
		if ( null === $version ) {
			$version = wp_get_theme( 'parent-theme' )->get( 'Version' );
		}

		return $version;
	}

	public static function autoloader( string $class_or_trait_name ) : void {
		if ( 0 !== strncmp( $class_or_trait_name, 'DOV_', 4 ) ) {
			return;
		}

		if ( isset( static::$autoload_files_list[ $class_or_trait_name ] ) ) {
			/** @noinspection PhpIncludeInspection, RedundantSuppression */
			require static::$autoload_files_list[ $class_or_trait_name ];

			return;
		}

		$file_name  = str_replace( '_', '-', strtolower( $class_or_trait_name ) ) . '.php';
		$is_base    = str_ends_with( $class_or_trait_name, '_Base' ); // Here I decided to focus on the end, in order to check for mane with one condition
		$root       = 'classes';
		$class_name = $root . ( $is_base ? '/base/' : '/' ) . 'class-' . $file_name;
		$trait_name = $root . '/traits/trait-' . $file_name;

		foreach ( array( $class_name, $trait_name ) as $theme_file ) {
			if ( isset( static::$theme_files_list[ $theme_file ] ) ) {
				static::$autoload_files_list[ $class_or_trait_name ] = static::$theme_files_list[ $theme_file ];
				/** @noinspection PhpIncludeInspection, RedundantSuppression */
				require static::$theme_files_list[ $theme_file ];
				break;
			}
		}
	}

	protected static function require_file( string $file_path_from_theme ) : void {
		if ( isset( static::$theme_files_list[ $file_path_from_theme ] ) ) {
			/** @noinspection PhpIncludeInspection, RedundantSuppression */
			require static::$theme_files_list[ $file_path_from_theme ];
		}
	}

	protected static function set_files_to_theme_files_list( string $directory_path_from_theme ) : void {
		if ( $directory_path_from_theme ) {
			$directory_path_from_theme .= '/';
		}

		$files_list  = array();
		$scan_paths  = array();
		$parent_path = static::$parent_path . $directory_path_from_theme;
		$child_path  = static::$child_path . $directory_path_from_theme;

		if ( is_dir( $parent_path ) ) {
			$scan_paths[] = $parent_path;
		}

		if ( is_dir( $child_path ) ) {
			$scan_paths[] = $child_path;
		}

		foreach ( $scan_paths as $scan_path ) {
			$file_names = scandir( $scan_path );
			foreach ( $file_names as $file_name ) {
				if ( '.' === $file_name || '..' === $file_name || ! str_contains( $file_name, '.' ) ) {
					continue;
				}

				$files_list[ $directory_path_from_theme . $file_name ] = $scan_path . $file_name;
			}
		}

		if ( $files_list ) {
			static::$theme_files_list = array_merge( static::$theme_files_list, $files_list );
		}
	}

	public static function get_post_id_taking_into_preview() {
		$post_id = get_the_ID();
		if ( isset( static::$cache_post_ids_taking_into_preview[ $post_id ] ) ) {
			return static::$cache_post_ids_taking_into_preview[ $post_id ];
		}

		// phpcs:disabled WordPress.Security.NonceVerification
		if (
			isset( $_GET['preview'] ) &&
			(
				( isset( $_GET['preview_id'] ) && $post_id === (int) $_GET['preview_id'] ) ||
				( isset( $_GET['page_id'] ) && $post_id === (int) $_GET['page_id'] ) ||
				( isset( $_GET['p'] ) && $post_id === (int) $_GET['p'] )
			)
		) {
			$revisions = wp_get_post_revisions( $post_id, array( 'numberposts' => 1 ) );
			$revision  = array_shift( $revisions );
			if ( $revision && $revision->post_parent === $post_id ) {
				$post_id = (int) $revision->ID;
			}
		}
		// phpcs:enabled WordPress.Security.NonceVerification

		static::$cache_post_ids_taking_into_preview[ $post_id ] = $post_id;

		return $post_id;
	}

	public static function never() : bool {
		return false;
	}

	public static function load_theme_textdomain() : void {
		if ( is_dir( DOV_File::get_path( 'languages' ) ) ) {
			load_theme_textdomain( 'theme', DOV_File::get_path( 'languages' ) );
		}
	}

	public static function add_theme_supports() : void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);
		add_theme_support(
			'post-thumbnails',
			apply_filters( 'dov_thumbnails_support', array( 'post' ) )
		);
	}

	public static function removed_wptexturize() : void {
		remove_filter( 'the_title', 'wptexturize' );
		remove_filter( 'the_content', 'wptexturize' );
		remove_filter( 'the_excerpt', 'wptexturize' );
	}

	public static function fixed_shortcode_wpautop() : void {
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop', 12 );
	}

	public static function removed_sticky_for_blog( WP_Query $wp_query ) : void {
		if ( $wp_query->is_home() && $wp_query->is_main_query() ) {
			$wp_query->set( 'post__not_in', get_option( 'sticky_posts' ) );
		}
	}
}

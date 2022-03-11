<?php
/**
 * Tile Kiosk functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Tile_Kiosk
 *  
 * 
 *  
 *  
 * tk_get_theme_text_domain()			 | Textdomain stored in a variable
 * cmb2									 | Load CMB2 functions 
 * tk_get_ID_by_slug()					 | Retrive the id of a specifyed page/post 
 * tk_choose_template() 				 | Chose a custom template 
 * tk_modify_wp_query()					 | Modifying the initial WP query with pre_get_posts hook
 * sf_ajax_loader_handler() 			 | Ajax handler for loading and filtering posts
 * tk_get_images() 						 | Get custom field values: file list images  
 * tk_get_movies()						 | Get custom field values: oembed
 * tk_get_svg_data()					 | Get custom field values: file list svgs
 * tk_get_svg()							 | Store the SVG markup in a function
 * tk_custom_head()						 | Customize the sites head tag
 * tk_custom_img_sizes()				 | adding custom image sizes  
 * tk_big_image_size()					 | increase the big image size
 * tk_display_footer_menu()				 | add menu to footer
 * 
 *  
 *  
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	//define( '_S_VERSION', '1.0.0' );
	define( '_S_VERSION', '1.0.2' );
}

if ( ! function_exists( 'tile_kiosk_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function tile_kiosk_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Tile Kiosk, use a find and replace
		 * to change 'tile_kiosk' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'tile_kiosk', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'tile_kiosk' ),
				'tk-footer-menu' => esc_html__( 'Footer', 'tajimi_custom_tiles' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'tile_kiosk_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'tile_kiosk_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function tile_kiosk_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'tile_kiosk_content_width', 640 );
}
add_action( 'after_setup_theme', 'tile_kiosk_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function tile_kiosk_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'tile_kiosk' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'tile_kiosk' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'tile_kiosk_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function tile_kiosk_scripts() {
	wp_enqueue_style( 'tile_kiosk-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'tile_kiosk-style', 'rtl', 'replace' );
	wp_enqueue_script( 'tile_kiosk-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	/* testing */
	if( is_page( 'page-for-testing' ) ){ 
		// shop WP filters
		wp_enqueue_script( 'shopwp-filters', get_template_directory_uri() . '/js/shopwp-filter.js', array( 'shopwp-public' ), _S_VERSION, true );
		return;
	}	
	
	// jquery marquee
	wp_enqueue_script( 'jquery-marquee-js', get_template_directory_uri() . '/js/jquery.marquee.min.js', array( 'jquery' ), _S_VERSION, true );	
	// slick slider
	wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), _S_VERSION, true );
	wp_enqueue_style( 'slick-js', get_template_directory_uri() . '/css/slick.css', array(), _S_VERSION );
	// vimeo api
	wp_enqueue_script( 'vimeo-api', 'https://player.vimeo.com/api/player.js', array(), _S_VERSION, true );
/*	
	// Ajax Loader Scripts
	global $wp_query;
	$sfAjaxLoaderParams = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'contentContainer' => '#tk-tiles', // html element
		'nonce' => wp_create_nonce( 'sf_ajax_loader_nonce' ),
		'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
		'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1, // 0 page is initial load, next load will be 2nd page
		'max_page' => $wp_query->max_num_pages
	);
	*/	
	
	// main JS
	wp_enqueue_script( 'tk-scripts-js', get_template_directory_uri() . '/js/tk-scripts.js', array( 'wp-i18n', 'jquery', 'shopwp-public' ), _S_VERSION, true );
	wp_localize_script( 'tk-scripts-js', 'sf_ajax_loader_params', $sfAjaxLoaderParams );

	// JS i18n
	$tk_translation = array(
		'wps_cart_remove' => __( 'Remove', tk_get_theme_text_domain() ),
		'wps_cart_subtotal' => __( 'Subtotal', tk_get_theme_text_domain() ),
		'wps_cart_checkout' => __( 'Checkout', tk_get_theme_text_domain() ),
	);
	wp_localize_script( 'tk-scripts-js', 'tk_i18n', $tk_translation );
	//wp_set_script_translations( 'tk-scripts-js', tk_get_theme_text_domain(), get_template_directory_uri() . 'languages' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tile_kiosk_scripts', 55 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



/** SF:
 * Retrieve the text domain of the theme.
 *
 * @since     1.0.0
 * @return    string    The text domain of the plugin.
 */
function tk_get_theme_text_domain() {
	$textdomain = 'tile_kiosk';
	return $textdomain;
}



/** SF:
 * Load CMB2 functions
 */
require_once( get_template_directory() . '/inc/tk-cmb2-functions.php');



/* GET ID BY SLUG
* Retrive the id of a specifyed page/post
* 
* @param 	string
* @return	integer
*
*/
function tk_get_ID_by_slug( $page ) {

	$page_obj = get_page_by_path( $page );
	$page_id = $page_obj->ID;
	
	return $page_id;	
}



/**
 * Chose a custom template 
 */
function tk_choose_template( $template ) {
	
	if ( is_admin() ) {
		return $template;
	}
	
	// HOME
	if ( is_home() && is_main_query() ) {
		$new_template = locate_template( array( 'tmpl_startpage.php' ) );
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'tk_choose_template', 99 );



/** SF:
 * Modifying the initial WP query with pre_get_posts hook
 */
function tk_modify_wp_query( $query ) {

	if( $query->is_main_query() && is_home() ){	
		
		// VARS
		$post_types = array( 'wps_products' );
		$meta_query = ( is_array( $query->get( 'meta_query' ) ) ) ? $query->get( 'meta_query' ) : []; //Get original meta query before adding additional arguments 
		
		// QUERY SET
		$query->set( 'meta_query', $meta_query ); //Add our meta query to the original meta queries
		$query->set( 'post_type', $post_types );
		$query->set( 'posts_per_page', -1 );
//		$query->set( 'posts_per_page', 50 );
//		$query->set( 'orderby', 'title' );
//		$query->set( 'orderby', 'date' );
		$query->set( 'orderby', 'rand' );
		$query->set( 'order', 'asc' );
		
	}
}
// add_action( 'pre_get_posts', 'tk_modify_wp_query' );



/** SF:
 * Ajax handler for loading posts
 */
function sf_ajax_loader_handler() {
	
	// SECURITY
/*	
	if ( wp_doing_ajax() ) :
		check_ajax_referer( 'sf_ajax_loader_nonce', 'nonce', true );
	endif;
*/	
	
	// OBJECTS
	$Tags = WP_Shopify\Factories\DB\Tags_Factory::build();
	$Products = WP_Shopify\Factories\DB\Products_Factory::build();
	
	
	// VARS
	$filter_options = filter_input( INPUT_POST, 'tk_filter', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ); // JS input
	$filtered = ( $filter_options ) ? true : false;
	$filter_results = false;
	$post_type = array( 'wps_products' );
	$load_posts = -1;
	$post_in = '';

	
	// CONDITIONAL FILTER
	if( $filtered ){
		
//		$filter_tags = array();
		$filtered_post_ids = array();
		
		// SHOPIFY TAGS
		// get all product ids from tags -> summarized 2D array
		foreach( $filter_options as $option ){
			$filter_tags[][ $option[ 'name' ] ] = $Tags->get_product_ids_from_tags( $option[ 'value' ] );
		}

		$filter_tags = array_merge_recursive( ... $filter_tags );
		
		// check if more than one filter category is set and switch to AND mode if true
		if( count( $filter_tags ) > 1 ) {
			// AND mode
			$filtered_product_ids = call_user_func_array( 'array_intersect', $filter_tags );
		}
		else{
			// OR mode (only 1 category active)
			// convert 2D array to 1D
			$filtered_product_ids = array_reduce( $filter_tags, 'array_merge', array() );
		}		
		
		// get the post ids from the product ids
		if( ! empty( $filtered_product_ids ) ){
			foreach( $filtered_product_ids as $id => $value ){
				$filtered_post_ids[] = $Products->get_post_id_from_product_id( $value );
			}
		}			
		
		// check if filter leads to a result and fill the $post_in variable if true
		$filter_results = !empty( $filtered_post_ids ) ? true : false;
		$post_in = $filter_results ? $filtered_post_ids : array( 0 ); // display filtered results if found or display no results (forced WP query failure)
	}
	
	
	// ARGS
	$args[ 'post_type' ] = $post_type;
	$args[ 'post__in' ] = $post_in;
	$args[ 'posts_per_page' ] = $filtered ? -1 : $load_posts;
//	$args[ 'paged' ] = ( isset( $_POST[ 'page' ] ) ) ? $_POST[ 'page' ] + 1 : '';
	$args[ 'post_status' ] = 'publish';
//	$args[ 'orderby' ] = 'title';
//	$args[ 'orderby' ] = 'date';
	$args[ 'orderby' ] = $filtered ? 'date' : 'rand';
	$args[ 'order' ] = 'asc';
//	$args[ 'lang' ] = '';
	
	
	// LOOP
	$tk_query = null;
	$tk_query = new WP_Query( $args );
	
	ob_start();
	if ( $tk_query->have_posts() ) :
	
		while( $tk_query->have_posts() ) : 

			$tk_query->the_post();
	
				// SINGLE ($post_id)
				get_template_part( 'template-parts/content', get_post_type() );

		endwhile;
		wp_reset_postdata();
	
	else :
		get_template_part( 'template-parts/content', 'none' );
	
 	endif;
	$data = ob_get_contents();
	ob_end_clean();

	
	// RESPONSE
	$resp = array(
		'success'  => true,
		'data'     => $data
	);
	
	// GATEWAY PHP or JS
	if ( wp_doing_ajax() ) :
		wp_send_json( $resp );
	else:
		echo $data;
	endif;
}
add_action( 'wp_ajax_sf_ajax_loader', 'sf_ajax_loader_handler' );
add_action( 'wp_ajax_nopriv_sf_ajax_loader', 'sf_ajax_loader_handler' );



/** SF:
 * Get custom field values: file list images
 * @params	string $meta_key	int $post_id	string $class	string $img_size 
 * @return  string image data
 */
function tk_get_images( $meta_key, $post_id = '', $class = '', $img_size = '' ){
	
	// VARS
	$post_id = !empty( $post_id ) ? $post_id : get_the_ID();
	$data = '';
	$images = array();
	$class = !empty( $class ) ? ' ' . $class : '' ;
	
	// GET FIELD
	$files = get_post_meta( $post_id, $meta_key, 1 );

	// LOOP
	if( !empty( $files ) ){	
		foreach ( (array) $files as $attachment_id => $attachment_url ) {
			$img = wp_get_attachment_image( $attachment_id, $img_size );
			$img_src = wp_get_attachment_image_src( $attachment_id, $img_size );
			$orientation = ( $img_src[ 1 ] <= $img_src[ 2 ] ) ? 'portrait' : 'landscape'; // 1->width, 2->height
			$img_name = get_post( $attachment_id )->post_title;
			$images[] = '<div class="entry-media itm-img ' . $orientation . $class . '" data-name="' . $img_name . '">' . $img . '</div>';
		}	

		$data = implode( '', $images );
	}	
	
	return $data;
}



/** SF:
 * Get custom field values: oembed
 * @params	string $meta_key	int $post_id	string $class
 * @return  string oembed data
 */
function tk_get_movies( $meta_key, $post_id = '', $class = '' ){
	
	// VARS
	$post_id = !empty( $post_id ) ? $post_id : get_the_ID();
	$data = '';
	$movies = array();
	$class = !empty( $class ) ? ' ' . $class : '' ;	
	
	// GET FIELD
	$entries = get_post_meta( $post_id, $meta_key, 1 );	
	
	// LOOP
	foreach ( (array) $entries as $key => $entry ) {

		if ( isset( $entry[ 'tk_movies_oembed' ] ) ) {
			$url = esc_url( $entry[ 'tk_movies_oembed' ] );
			$mov = wp_oembed_get( $url );
			$movies[] = '<div class="entry-media itm-mov ' . $class . '" data-vimeo-url="' . $url . '" data-vimeo-background="true"></div>';
		}
	}
	
	$data = implode( '', $movies );
	return $data;
}



/** SF:
 * Get custom field values: file list images
 * @params	string $meta_key	int $post_id	string $class	string $img_size 
 * @return  string image data
 */
function tk_get_svg_data( $meta_key, $post_id = '', $class = '', $img_size = '' ){
	
	// VARS
	$post_id = !empty( $post_id ) ? $post_id : get_the_ID();
	$data = '';
	$images = array();
	$class = !empty( $class ) ? ' ' . $class : '' ;
	
	// GET FIELD
	$files = get_post_meta( $post_id, $meta_key, 1 );

	// LOOP
	if( !empty( $files ) ){	
		foreach ( (array) $files as $attachment_id => $attachment_url ) {
			$svg = wp_get_attachment_image( $attachment_id, $img_size );
			$svg_src = wp_get_attachment_image_src( $attachment_id, $img_size );
			$svg_data = file_get_contents( $svg_src[ 0 ] );
			$svg_name = get_post( $attachment_id )->post_title;
//			$object = '<object type="image/svg+xml" data="' . $svg_src[ 0 ] . '"></object>';
			$images[] = '<div class="entry-media itm-svg-map ' . $class . '" data-name="' . $svg_name . '">' . $svg_data . '</div>';
		}	

		$data = implode( '', $images );
	}	
	
	return $data;
}


/** SF:
 * Store the SVG markup in a function 
 * 
 */
function tk_get_svg( $svg ){
	
	switch( $svg ){
			
		case 'tk_logo' :
			
			$svg_markup = '
				<svg version="1.1" id="tk-logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 283.5 48.2" style="enable-background:new 0 0 283.5 48.2;" xml:space="preserve">
					<g>
						<polygon class="st0" points="36.4,6.9 7.3,6.9 7.3,12.5 18.8,12.5 18.8,40 24.9,40 24.9,12.5 36.4,12.5 	"/>
						<polygon class="st0" points="63.9,34.4 63.9,6.9 57.9,6.9 57.9,40 60.9,40 63.9,40 77.9,40 77.9,34.4 	"/>
						<polygon class="st0" points="106,12.5 106,6.9 90,6.9 87.6,6.9 84,6.9 84,40 87,40 90,40 105.4,40 105.4,34.4 90,34.4 90,26 
							99.8,26 99.8,20.3 90,20.3 90,12.5 	"/>
						<rect x="42.8" y="6.9" class="st0" width="6" height="33.1"/>
						<g>
							<path class="st0" d="M201.5,23.4c0,6.4-4.8,11.5-10.7,11.5c-5.9,0-10.7-5.2-10.7-11.5s4.8-11.5,10.7-11.5
								C196.7,11.9,201.5,17.1,201.5,23.4 M207.3,23.4c0-9.5-7.4-17.2-16.5-17.2c-9.1,0-16.5,7.7-16.5,17.2s7.4,17.2,16.5,17.2
								C199.9,40.6,207.3,32.9,207.3,23.4"/>
						</g>
						<rect x="163" y="6.9" class="st0" width="6" height="33.1"/>
						<g>
							<path class="st0" d="M233.1,21.6c-2.5-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
								c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
								c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0c0,0,0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
								c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
								c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
								c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C239.8,26.1,237.3,22.9,233.1,21.6"/>
						</g>
						<polygon class="st0" points="129.7,40 135.7,40 135.7,28.8 140,24.5 151.8,40 159.5,40 144.3,20.2 157.6,6.9 149.4,6.9 135.7,20.5 
							135.7,6.9 135.7,6.9 129.7,6.9 	"/>
						<polygon class="st0" points="246,40 252,40 252,28.8 256.3,24.5 268.1,40 275.8,40 260.6,20.2 273.9,6.9 265.7,6.9 252,20.5 
							252,6.9 252,6.9 246,6.9 	"/>
					</g>
				</svg>';
			
			break;
			
		case 'float_text' :
			
			$svg_markup = '
			<svg version="1.1" id="tk-logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 1988.2 48.2" style="enable-background:new 0 0 1988.2 48.2;" xml:space="preserve">
				<style type="text/css">
					.st0{fill:#1D1D1B;}
				</style>
				<g>
					<polygon class="st0" points="629.7,20.3 612.6,20.3 612.6,6.8 606.6,6.8 606.6,20.3 606.6,25.9 606.6,39.9 612.6,39.9 612.6,25.9 
						629.7,25.9 629.7,39.9 635.7,39.9 635.7,25.9 635.7,20.3 635.7,6.8 629.7,6.8 	"/>
					<polygon class="st0" points="325.7,12.5 337.3,12.5 337.3,39.9 343.3,39.9 343.3,12.5 354.9,12.5 354.9,6.8 325.7,6.8 	"/>
					<polygon class="st0" points="382.9,6.8 376.9,6.8 376.9,39.9 397,39.9 397,34.3 382.9,34.3 	"/>
					<rect x="361.3" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="409.1,25.9 418.8,25.9 418.8,20.3 409.1,20.3 409.1,12.4 424.4,12.4 424.4,6.8 403,6.8 403,39.9 
						425,39.9 425,34.3 409.1,34.3 	"/>
					<path class="st0" d="M452.1,21.5c-2.5-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
						c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
						c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0c0,0,0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
						c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
						c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
						c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C458.8,26,456.4,22.9,452.1,21.5z"/>
					<polygon class="st0" points="983.3,6.8 977.3,6.8 977.3,39.9 997.4,39.9 997.4,34.3 983.3,34.3 	"/>
					<rect x="883.5" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="1009.5,25.9 1019.2,25.9 1019.2,20.3 1009.5,20.3 1009.5,12.4 1024.8,12.4 1024.8,6.8 1003.4,6.8 
						1003.4,39.9 1025.5,39.9 1025.5,34.3 1009.5,34.3 	"/>
					<polygon class="st0" points="1047.6,12.5 1059.2,12.5 1059.2,39.9 1065.2,39.9 1065.2,12.5 1076.8,12.5 1076.8,6.8 1047.6,6.8 	"/>
					<polygon class="st0" points="1104.8,6.8 1098.8,6.8 1098.8,39.9 1118.9,39.9 1118.9,34.3 1104.8,34.3 	"/>
					<rect x="1083.2" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="1131,25.9 1140.7,25.9 1140.7,20.3 1131,20.3 1131,12.4 1146.3,12.4 1146.3,6.8 1124.9,6.8 
						1124.9,39.9 1146.9,39.9 1146.9,34.3 1131,34.3 	"/>
					<path class="st0" d="M1174,21.5c-2.4-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
						c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
						c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0c0,0,0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
						c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
						c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
						c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C1180.7,26,1178.3,22.9,1174,21.5z"/>
					<polygon class="st0" points="166.7,12.5 178.3,12.5 178.3,39.9 184.3,39.9 184.3,12.5 195.9,12.5 195.9,6.8 166.7,6.8 	"/>
					<polygon class="st0" points="224,6.8 217.9,6.8 217.9,39.9 238,39.9 238,34.3 224,34.3 	"/>
					<rect x="202.3" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="250.1,25.9 259.9,25.9 259.9,20.3 250.1,20.3 250.1,12.4 265.4,12.4 265.4,6.8 244,6.8 244,39.9 
						266.1,39.9 266.1,34.3 250.1,34.3 	"/>
					<path class="st0" d="M293.2,21.5c-2.5-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
						c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
						c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0c0,0,0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
						c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
						c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
						c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C299.9,26,297.4,22.9,293.2,21.5z"/>
					<polygon class="st0" points="7.8,12.5 19.3,12.5 19.3,39.9 25.4,39.9 25.4,12.5 36.9,12.5 36.9,6.8 7.8,6.8 	"/>
					<polygon class="st0" points="65,6.8 58.9,6.8 58.9,39.9 79,39.9 79,34.3 65,34.3 	"/>
					<rect x="43.3" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="91.1,25.9 100.9,25.9 100.9,20.3 91.1,20.3 91.1,12.4 106.4,12.4 106.4,6.8 85.1,6.8 85.1,39.9 
						107.1,39.9 107.1,34.3 91.1,34.3 	"/>
					<path class="st0" d="M134.2,21.5c-2.5-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
						c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
						c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0s0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
						c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
						c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
						c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C140.9,26,138.5,22.9,134.2,21.5z"/>
					<path class="st0" d="M1343.6,6.8l-12.7,33.1h6.5l3.3-8.6h18.4l3.3,8.6h6.5l-12.7-33.1H1343.6z M1342.9,25.6l5-13h4l5,13H1342.9z"/>
					<polygon class="st0" points="1306.3,17.7 1292,6.8 1286,6.8 1286,39.9 1292.1,39.9 1292.1,14.5 1306.3,25.3 1320.4,14.5 
						1320.4,39.9 1326.5,39.9 1326.5,6.8 1320.5,6.8 	"/>
					<path class="st0" d="M1569.9,6.8l-12.7,33.1h6.5l3.3-8.6h18.4l3.3,8.6h6.5l-12.7-33.1H1569.9z M1569.2,25.6l5-13h4l5,13H1569.2z"/>
					<rect x="1635.2" y="6.8" class="st0" width="6" height="33.1"/>
					<rect x="1701.6" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="1671.3,17.7 1657.1,6.8 1651.1,6.8 1651.1,39.9 1657.1,39.9 1657.1,14.5 1671.3,25.3 1685.5,14.5 
						1685.5,39.9 1691.5,39.9 1691.5,6.8 1685.5,6.8 	"/>
					<polygon class="st0" points="1557.6,6.8 1528.5,6.8 1528.5,12.5 1540,12.5 1540,39.9 1546.1,39.9 1546.1,12.5 1557.6,12.5 	"/>
					<path class="st0" d="M1599.6,12.5h20.3l0,12.1c0,0.3,0,0.5,0,0.8h0c0,6.9-3.5,9.6-8.1,9.9c-3.9,0.2-7.9-2.5-8.8-7.6l-5.8,1.4
						c1.7,7,7.6,11.9,14.2,11.9c0.2,0,0.5,0,0.7,0c8.4-0.4,13.8-6.8,13.8-16.3V6.8h-26.3V12.5z"/>
					<rect x="1458.6" y="6.8" class="st0" width="6" height="33.1"/>
					<polygon class="st0" points="1500.1,31 1477.6,6.8 1471.5,6.8 1471.5,39.9 1477.6,39.9 1477.6,15.7 1500.1,39.9 1506.2,39.9 
						1506.2,6.8 1500.1,6.8 	"/>
					<polygon class="st0" points="927.2,31 904.7,6.8 898.6,6.8 898.6,39.9 904.6,39.9 904.6,15.7 927.2,39.9 933.2,39.9 933.2,6.8 
						927.2,6.8 	"/>
					<path class="st0" d="M1390.6,7c-1.3-0.1-2.4-0.2-4-0.2h-12.9V40h12.9c1.6,0,2.6,0,4-0.2c1.6-0.2,4.7-0.7,7.4-3.3
						c3.9-3.7,4.9-8,4.9-13.1s-1.1-9.4-4.9-13.1C1395.3,7.7,1392.2,7.1,1390.6,7z M1393.6,32c-1.8,1.7-3.9,2.1-5,2.2
						c-0.9,0.1-1.6,0.1-2.7,0.1h-6.2V12.5h6.2c1.1,0,1.8,0,2.7,0.1c1.1,0.1,3.2,0.5,5,2.2c2.6,2.4,3.3,5.3,3.3,8.6
						S1396.2,29.6,1393.6,32z"/>
					<polygon class="st0" points="1414.9,25.9 1424.7,25.9 1424.7,20.3 1414.9,20.3 1414.9,12.4 1430.2,12.4 1430.2,6.8 1408.8,6.8 
						1408.8,39.9 1430.9,39.9 1430.9,34.3 1414.9,34.3 	"/>
					<rect x="466.3" y="6.8" class="st0" width="6" height="22.6"/>
					<rect x="466.3" y="33.9" class="st0" width="6" height="6"/>
					<path class="st0" d="M594.1,21.5c-2.5-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
						c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
						c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0c0,0,0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
						c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
						c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
						c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C600.8,26,598.4,22.9,594.1,21.5z"/>
					<path class="st0" d="M869.6,21.5c-2.5-0.8-5.1-1.3-7.2-1.7c-0.4-0.1-0.8-0.1-1.1-0.2c0,0,0,0,0,0c-0.1,0-0.1,0-0.2,0
						c-0.1,0-0.3-0.1-0.4-0.1c0,0,0,0-0.1,0c-1.2-0.3-2.6-0.5-3.7-1c-0.7-0.3-1.2-0.6-1.5-1c-0.4-0.5-0.7-1.1-0.7-1.8
						c0-2.9,3.1-4.2,7-4.2c3.5,0,5.7,1.5,7.2,3.7c0.2,0.2,0.3,0.5,0.4,0.7c0,0,0,0,0,0c0,0,0,0,0,0c0.4,0.8,0.5,1.4,0.6,1.6l5.9-1.1
						c-0.7-3.6-4.1-9.8-12.7-10.2l-0.2,0c-0.6,0-0.9-0.1-1.6,0c-3.5,0-6.5,0.9-9.1,2.9c-3,2.2-4.1,5.2-3.2,9c1.4,5.5,6.8,6.8,10.1,7.4
						c1,0.2,1.3,0.2,3,0.5c3.6,0.6,8.2,0.7,8.2,4.3c0,3.5-3.6,5-8,5c-2.2,0-4.3-0.6-5.8-1.8c-1.7-1.1-2.8-3.2-3.2-5.6l-6,0.9
						c1.1,7.3,6.7,11.7,15,11.7c11.9,0,13.8-6.6,13.8-10.5C876.3,26,873.9,22.9,869.6,21.5z"/>
					<path class="st0" d="M700,7.3c-1.2-0.3-2.2-0.5-4.5-0.5h-15.5v33h6V26.7h2.2c0,0,7.9,0,8.9,0c7.7-0.2,9.8-5.7,9.8-10.1
						c0-1.9-0.5-3.7-1.5-5.3C704.8,10.2,703.1,8.2,700,7.3z M699.2,21.1c-1.1,0.5-2.8,0.5-5.2,0.6h-8v-9.4h8.3c0.6,0,1.8,0,2.4,0
						c1.7,0.1,3.2,0.7,4.1,2.5c0.4,0.7,0.5,1.5,0.5,2.3C701.4,17.7,701.2,20.1,699.2,21.1z"/>
					<path class="st0" d="M823.4,16.5c0-1.9-0.5-3.7-1.5-5.3c-0.7-1-2.3-3-5.4-3.9c-1.2-0.3-2.2-0.5-4.5-0.5h-15.5v33h6V26.7h2.2
						c0,0,4.4,0,7.1,0l6.2,13.3h6.4l-6.6-14.1C822.1,24.2,823.4,20,823.4,16.5z M802.6,21.7v-9.4h8.3c0.6,0,1.8,0,2.4,0
						c1.7,0.1,3.2,0.7,4.1,2.5c0.4,0.7,0.5,1.5,0.5,2.3c0,0.6-0.2,3-2.2,4c-1.1,0.5-2.8,0.5-5.2,0.6H802.6z"/>
					<path class="st0" d="M657.9,6.2c-9.1,0-16.5,7.7-16.5,17.2c0,9.5,7.4,17.2,16.5,17.2c9.1,0,16.5-7.7,16.5-17.2
						C674.5,13.9,667.1,6.2,657.9,6.2z M657.9,34.9c-5.9,0-10.7-5.2-10.7-11.5c0-6.4,4.8-11.5,10.7-11.5c5.9,0,10.7,5.2,10.7,11.5
						C668.7,29.8,663.9,34.9,657.9,34.9z"/>
					<path class="st0" d="M774.1,6.2c-9.1,0-16.5,7.7-16.5,17.2c0,9.5,7.4,17.2,16.5,17.2s16.5-7.7,16.5-17.2
						C790.6,13.9,783.2,6.2,774.1,6.2z M774.1,34.9c-5.9,0-10.7-5.2-10.7-11.5c0-6.4,4.8-11.5,10.7-11.5s10.7,5.2,10.7,11.5
						C784.8,29.8,780,34.9,774.1,34.9z"/>
					<polygon class="st0" points="733,39.9 739.1,39.9 739.1,25.9 752.8,25.9 752.8,20.3 739.1,20.3 739.1,12.4 754.4,12.4 754.4,6.8 
						733,6.8 	"/>
					<path class="st0" d="M954.9,22.4l0,5l9.3,0c-0.1,4-5.5,7.9-9.6,7.9c-5.6,0-10.2-5.6-10.2-11.9c0-6.4,4.6-11.5,10.2-11.5
						c3.6,0,6.8,2.1,8.6,5.3l5.6-1c-2.5-5.9-7.9-10-14.2-10c-8.7,0-15.7,7.7-15.7,17.2c0,9.5,7,17.2,15.7,17.2c3.9,0,7.1-1.9,9.6-4.7
						v4.1h5.4V26.3h0l0-3.9L954.9,22.4z"/>
					<path class="st0" d="M1902.9,11.2c-0.7-1-2.3-3-5.4-3.9c-1.2-0.3-2.2-0.5-4.5-0.5h-15.5v33h6V26.7h2.2c0,0,7.9,0,8.9,0
						c7.7-0.2,9.8-5.7,9.8-10.1C1904.4,14.6,1903.9,12.8,1902.9,11.2z M1896.7,21.1c-1.1,0.5-2.8,0.5-5.2,0.6h-8v-9.4h8.3
						c0.6,0,1.8,0,2.4,0c1.7,0.1,3.2,0.7,4.1,2.5c0.4,0.7,0.5,1.5,0.5,2.3C1898.8,17.7,1898.6,20.1,1896.7,21.1z"/>
					<path class="st0" d="M1847.7,6.8L1835,39.9h6.5l3.3-8.6h18.4l3.3,8.6h6.5l-12.7-33.1H1847.7z M1847,25.6l5-13h4l5,13H1847z"/>
					<path class="st0" d="M1916,6.8l-12.7,33.1h6.5l3.3-8.6h18.4l3.3,8.6h6.5l-12.7-33.1H1916z M1915.2,25.6l5-13h4l5,13H1915.2z"/>
					<path class="st0" d="M1806.9,12.5h20.3l0,12.1c0,0.3,0,0.5,0,0.8h0c0,6.9-3.5,9.6-8.1,9.9c-3.9,0.2-7.9-2.5-8.8-7.6l-5.8,1.4
						c1.7,7,7.6,11.9,14.2,11.9c0.2,0,0.5,0,0.7,0c8.4-0.4,13.8-6.8,13.8-16.3V6.8h-26.3V12.5z"/>
					<polygon class="st0" points="1973.9,6.8 1973.9,31 1951.4,6.8 1945.3,6.8 1945.3,39.9 1951.3,39.9 1951.3,15.7 1973.9,39.9 
						1979.9,39.9 1979.9,6.8 	"/>
				</g>
				</svg>';
			
			break;
			
		case 'filter' :
			$svg_markup = '
				<svg version="1.1" id="tk-filter" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 45.4 45.4" style="enable-background:new 0 0 45.4 45.4;" xml:space="preserve">
				<g>
					<path d="M22.7,2.6c-11.1,0-20.1,9-20.1,20.1c0,11.1,9,20.1,20.1,20.1s20.1-9,20.1-20.1C42.8,11.6,33.8,2.6,22.7,2.6z M22.7,39.1
						c-9.1,0-16.4-7.4-16.4-16.4c0-9.1,7.4-16.4,16.4-16.4c9.1,0,16.4,7.4,16.4,16.4C39.1,31.7,31.7,39.1,22.7,39.1z"/>
					<path d="M16.6,11.7c-1.2,0-2.2,0.7-2.7,1.7h-2.1v2.8h2.1c0.5,1,1.5,1.7,2.7,1.7s2.2-0.7,2.7-1.7h14.3v-2.8H19.3
						C18.8,12.4,17.8,11.7,16.6,11.7z"/>
					<path d="M27.8,19.3c-1.2,0-2.2,0.7-2.7,1.7H11.7v2.8h13.3c0.5,1,1.5,1.7,2.7,1.7s2.2-0.7,2.7-1.7h3.1V21h-3.1
						C30,20,29,19.3,27.8,19.3z"/>
					<path d="M19.7,26.9c-1.2,0-2.2,0.7-2.7,1.7h-5.2v2.8H17c0.5,1,1.5,1.7,2.7,1.7s2.2-0.7,2.7-1.7h11.2v-2.8H22.4
						C21.9,27.6,20.9,26.9,19.7,26.9z"/>
				</g>
				</svg>';
			break;
	} 
	
	return $svg_markup;
}



/** SF:
 * Customize the sites head tag
 * 
 */
function tk_custom_head(){
	
	// GTM
	print '<!-- Google Tag Manager -->';
	// GTM SF
	print "		
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PWFWW76');</script>
	";
	// GTM Xs
	print "
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore
		(j,f);
		})(window,document,'script','dataLayer','GTM-KJ96SVC');</script>
	";
	print '<!-- End Google Tag Manager -->';
	
	
	// for EN typeface
	print '<link rel="stylesheet" href="https://use.typekit.net/wlv6frg.css">';
	
	// for JP typeface
//	if ( function_exists( 'pll_current_language' ) && ( pll_default_language() != pll_current_language() ) ){	
	/*
	print '
			<script>
			  (function(d) {
				var config = {
				  kitId: \'ept0bzo\',
				  scriptTimeout: 3000,
				  async: true
				},
				h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src=\'https://use.typekit.net/\'+config.kitId+\'.js\';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
			  })(document);
			</script>
		';
		*/
//	}	
	
	// Social Media
	echo '
	<!-- Primary Meta Tags -->
	<title>TILE KIOSK</title>
	<meta name="title" content="TILE KIOSK">
	<meta name="description" content="Tiles tiles tiles! Shop for single tiles made in Tajimi, Japan.">

	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://tile-kiosk.jp/">
	<meta property="og:title" content="TILE KIOSK">
	<meta property="og:description" content="Tiles tiles tiles! Shop for single tiles made in Tajimi, Japan.">
	<meta property="og:image" content="https://tile-kiosk.jp/wp/wp-content/uploads/2021/02/TK_postcard_s.jpg">

	<!-- Twitter -->
	<meta property="twitter:card" content="summary_large_image">
	<meta property="twitter:url" content="https://tile-kiosk.jp/">
	<meta property="twitter:title" content="TILE KIOSK">
	<meta property="twitter:description" content="Tiles tiles tiles! Shop for single tiles made in Tajimi, Japan.">
	<meta property="twitter:image" content="https://tile-kiosk.jp/wp/wp-content/uploads/2021/02/TK_postcard_s.jpg">
	';	
}
add_action( 'wp_head', 'tk_custom_head' );



/** SF:
 * adding custom image sizes
 */
function tk_custom_img_sizes() {	
	// add image size
	add_image_size( 'tk-super-large', 3000, 3000 );
	
	// remove image size
	remove_image_size( '1536x1536' );
	
	// disable max image size of 2560px
	add_filter( 'big_image_size_threshold', '__return_false' );
	
	update_option( 'medium_size_w', 2000 );
	update_option( 'medium_size_h', 2000 );
	update_option( 'large_size_w', 2000 );
	update_option( 'large_size_h', 2000 );
	update_option( 'medium_large_size_w', 2000 );
	update_option( 'medium_large_size_h', 2000 );
	
	

}
add_action( 'after_setup_theme', 'tk_custom_img_sizes' );



/** SF:
 * increase the big image size
 */
function tk_big_image_size( $threshold ) {
    return 3000; // new threshold
}
add_filter( 'big_image_size_threshold', 'tk_big_image_size', 100, 1 );



/** SF:
 * Redirect Single Custom Post Type Pages to Post Type Archive Page and 404 errors to homepage
 */
function tk_redirect_handler(){
	
	// redirect 404 errors to homepage
    if( is_404() ){
        wp_redirect( home_url(), 301 );
        exit();
    }
}
add_action( 'template_redirect', 'tk_redirect_handler' );



/*
function turn_blogposts_translation_off( $post_types, $is_settings ) {
	
	unset( $post_types[ 'post' ] );
	return $post_types;
	
}
add_filter( 'pll_get_post_types', 'turn_blogposts_translation_off', 10, 2 );
*/


/** SF:
 * add menu to footer
 */
/*
function tk_display_footer_menu() { 
 
	wp_nav_menu( array( 'theme_location' => 'tk-footer-menu' ) );
	
}
add_action( 'wp_footer', 'tk_display_footer_menu' ); 
*/


add_filter( 'shopwp_currency_symbol', function( $defaultSymbol ) {
    return '¥';
});

add_filter( 'shopwp_currency_code', function( $defaultCode ) {
    return 'JPY';
});



/** SF:
 * remove Gutenberg Block Library CSS from frontent
 */
function tk_remove_gutenberg_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', 'tk_remove_gutenberg_css', 100 );
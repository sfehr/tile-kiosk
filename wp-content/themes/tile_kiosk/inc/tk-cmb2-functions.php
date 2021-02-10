<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category susuri theme
 * @package  susuri
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */


/** SU CMB2 Functions Inventory
 *  
 * tk_movies_metabox()
 * tk_showcase_image_metabox()
 * tk_showcase_svg_maps_metabox()
 *  
 */



/* MOVIE METABOX (repeatable)
*
* [group]
* [oembed] movie
*
*/
add_action( 'cmb2_admin_init', 'tk_movies_metabox' );

function tk_movies_metabox() {

	$perfix = 'tk_movies_';

	// METABOX
	$movies = new_cmb2_box( array(
		'id'            => $perfix . 'metabox',
		'title'         => __( 'Movies', tk_get_theme_text_domain() ),
		'object_types'  => array( 'page', ), // Post type
		'show_on'       => array( 'key' => 'id', 'value' => array( tk_get_ID_by_slug( 'section-head' ) ) ),
		'context'       => 'normal',
		'priority'      => 'low',
		'show_names'    => true, // Show field names on the left
		'closed'        => false, // true to have the groups closed by default		
	) );
	
	// GROUP FIELD
	$movie_group = $movies->add_field( array(
		'id'          => $perfix . 'group',
		'type'        => 'group',
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'       => __( 'Movie {#}', tk_get_theme_text_domain() ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'        => __( 'Add Movie', tk_get_theme_text_domain() ),
			'remove_button'     => __( 'Remove Movie', tk_get_theme_text_domain() ),
			'sortable'          => true,
			// 'closed'            => true, // true to have the groups closed by default
			'remove_confirm'	=> esc_html__( 'Are you sure you want to remove?', tk_get_theme_text_domain() ), // Performs confirmation before removing group.
		),
	) );	
	
	// MOVIE FIELD
	$movies->add_group_field( $movie_group, array(
		'name' => __( 'Movie', tk_get_theme_text_domain() ),
		'desc' => __( 'Enter the url', tk_get_theme_text_domain() ),
		'id'   => $perfix . 'oembed',
		'type' => 'oembed',
	) );
}




/* SHOWCASE METABOX
*
* [file list] showcase images
*
*/
add_action( 'cmb2_admin_init', 'tk_showcase_image_metabox' );

function tk_showcase_image_metabox() {
	
	$perfix = 'tk_showcase_';

	// METABOX
	$showcase = new_cmb2_box( array(
		'id'            => $perfix . 'metabox',
		'title'         => __( 'Showcase Images', tk_get_theme_text_domain() ),
		'object_types'  => array( 'page' ), // Post type
		'show_on'       => array( 'key' => 'id', 'value' => array( tk_get_ID_by_slug( 'section-head' ) ) ),		
	) );
	
	// IMAGE FIELD
	$showcase->add_field( array(
		'name' => __( 'Image', tk_get_theme_text_domain() ),
		'id'   => $perfix . 'image',
		'type' => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
		// 'query_args' => array( 'type' => 'image' ), // Only images attachment
		// Optional, override default text strings
		'text' => array(
			'add_upload_files_text' => __( 'Add Image', tk_get_theme_text_domain() ), // default: "Add or Upload Files"
			'remove_image_text' => __( 'Remove Image', tk_get_theme_text_domain() ), // default: "Remove Image"
			'file_text' => __( 'File:', tk_get_theme_text_domain() ), // default: "File:"
			'file_download_text' => __( 'Download:', tk_get_theme_text_domain() ), // default: "Download"
			'remove_text' => __( 'Remove', tk_get_theme_text_domain() ), // default: "Remove"
		),
	) );	
}



/* SVG MAPS METABOX
*
* [file list] showcase images
* [file list] showcase images SVG maps
*
*/
add_action( 'cmb2_admin_init', 'tk_showcase_svg_maps_metabox' );

function tk_showcase_svg_maps_metabox() {
	
	$perfix = 'tk_svg_maps_';

	// METABOX
	$showcase = new_cmb2_box( array(
		'id'            => $perfix . 'metabox',
		'title'         => __( 'SVG Maps', tk_get_theme_text_domain() ),
		'object_types'  => array( 'page' ), // Post type
		'show_on'       => array( 'key' => 'id', 'value' => array( tk_get_ID_by_slug( 'section-head' ) ) ),
		'priority'      => 'low',
	) );
	
	// IMAGE FIELD
	$showcase->add_field( array(
		'name' => __( 'Image', tk_get_theme_text_domain() ),
		'id'   => $perfix . 'image',
		'type' => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
		'query_args' => array( 'type' => 'image/svg+xml' ), // Only images attachment
		// Optional, override default text strings
		'text' => array(
			'add_upload_files_text' => __( 'Add Image', tk_get_theme_text_domain() ), // default: "Add or Upload Files"
			'remove_image_text' => __( 'Remove Image', tk_get_theme_text_domain() ), // default: "Remove Image"
			'file_text' => __( 'File:', tk_get_theme_text_domain() ), // default: "File:"
			'file_download_text' => __( 'Download:', tk_get_theme_text_domain() ), // default: "Download"
			'remove_text' => __( 'Remove', tk_get_theme_text_domain() ), // default: "Remove"
		),
	) );
	
	//
}
	 
<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Tile_Kiosk
 */

global $post;

// PRODUCT
// include php class
$Products = WP_Shopify\Factories\Render\Products\Products_Factory::build();
$Products->products(
	apply_filters( 'wps_products_single_args', [
		'dropzone_product_buy_button' => '#product_buy_button-post-' . $post->ID,
		'dropzone_product_title' => '#product_title-post-' . $post->ID,
		'dropzone_product_description' => '#product_description-post-' . $post->ID,
		'dropzone_product_pricing' => '#product_pricing-post-' . $post->ID,
		'dropzone_product_gallery' => '#product_gallery-post-' . $post->ID,
		'excludes' => false,
		'post_id' => $post->ID,
		'pagination' => false,
		'limit' => 1,
	] )
);

// PRICE
$price = $Products->pricing([
	'title' => $post->post_title,
	'dropzone_product_pricing' => '#preview_pricing-post-' . get_the_ID()
]);

// IMAGE
$image = $Products->gallery([
	'title' => $post->post_title,
	'show_featured_only' => true,
	'dropzone_product_gallery' => '#preview_gallery-post-' . get_the_ID()
]);

// LABELING
// include php class
$Tags = WP_Shopify\Factories\DB\Tags_Factory::build();
$Products_DB = WP_Shopify\Factories\DB\Products_Factory::build();

// get the shopify product id of the current post
$current_product_ids = $Products_DB->get_product_ids_from_post_ids( $post->ID );

// array with the tags to extract
$tags = array( 
	'label?new', 
	'label?sales',
	'label?popular',
	'label?designer'
);
// label array to be filled with tag values (if matched)
$labels = array();

foreach( $tags as $tag => $value ){
	// get product ids by single tag
	$product_ids = $Tags->get_product_ids_from_tags( $value );
	// check if current product has any label
	if( ! empty( $current_product_ids ) ){
		// check if current product id (label) is in the (label) list
		if( in_array( $current_product_ids[ 0 ], $product_ids ) ){
			$labels[] = $value;
		}
	}
}


?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-label="<?php echo implode( ' ', $labels ) ?>">

	<div class="tk-preview">
		<div id="preview_gallery-post-<?php the_ID(); ?>" class="tk-gallery"></div>
		<div id="preview_pricing-post-<?php the_ID(); ?>" class="tk-pricing"></div>
	</div>
	
	<div class="tk-product">
		<div class="tk-compontents-wrapper">
			<div class="tk-modal-close">x</div>
			<div id="product_gallery-post-<?php the_ID(); ?>" class="tk-gallery"></div>
			<div id="product_title-post-<?php the_ID(); ?>" class="tk-title"></div>
			<div id="product_description-post-<?php the_ID(); ?>" class="tk-description"></div>
			<div id="product_pricing-post-<?php the_ID(); ?>" class="tk-price"></div>
			<div id="product_buy_button-post-<?php the_ID(); ?>" class="tk-buy"></div>
		</div>	
	</div>
	
</article><!-- #post-<?php the_ID(); ?> -->

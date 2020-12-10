<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Tile_Kiosk
 */

$Products = WP_Shopify\Factories\Render\Products\Products_Factory::build();
/*
		$Products->products(
			apply_filters('wps_products_single_args', [
				'dropzone_product_buy_button' => '#button-post-' . get_the_ID(),
				'dropzone_product_title' => '#product_title',
				'dropzone_product_description' => '#product_description',
				'dropzone_product_pricing' => '#product_pricing',
				'dropzone_product_gallery' => '#gallery-post-' . get_the_ID(),
				'excludes' => false,
				'post_id' => $post->ID,
				'pagination' => false,
				'limit' => 1,
			])
		);
*/

// TITLE
$Products->title([
	'title' => $post->post_title,
	'dropzone_product_title' => '#product_title-post-' . get_the_ID()
]);
/*
// TITLE
$Products->products([
	'title' => $post->post_title,
	'tag' => '#product_tag-post-' . get_the_ID()
]);
*/
		
// PRICE
$Products->pricing([
	'title' => $post->post_title,
	'dropzone_product_pricing' => '#product_pricing-post-' . get_the_ID()
]);
		
// GALLERY
$Products->gallery([
	'title' => $post->post_title,	
	'dropzone_product_gallery' => '#gallery-post-' . get_the_ID(),
	'show_featured_only' => true
]);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-product">
		<?php
		
		echo 'image: ';
//		echo get_the_post_thumbnail();
		the_post_thumbnail();
		?>
		
		
		<div id="gallery-post-<?php the_ID(); ?>" class="tk-gallery"></div>
		<div id="product_pricing-post-<?php the_ID(); ?>" class="tk-pricing"></div>
		
	</div><!-- .entry-product -->

	<footer class="entry-footer">
		<?php // tile_kiosk_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->

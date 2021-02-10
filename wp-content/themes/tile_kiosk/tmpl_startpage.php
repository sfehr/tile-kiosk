<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Tile_Kiosk
 */

get_header( 'tk' );

$section_head_id = tk_get_ID_by_slug( 'section-head' );
$section_about_id = tk_get_ID_by_slug( 'about-section' );
$section_contact_id = tk_get_ID_by_slug( 'contact-section' );
$showcase_images = tk_get_images( 'tk_showcase_image', $section_head_id, 'itm-showcase' ); // meta_key, post_id, css-class, img-size
$showcase_svg_maps = tk_get_svg_data( 'tk_svg_maps_image', $section_head_id, 'itm-showcase' ); // meta_key, post_id, css-class, img-size
$showcase_movies = tk_get_movies( 'tk_movies_group', $section_head_id, 'itm-showcase' ); // meta_key, post_id, css-class, img-size
?>

	<main id="primary" class="site-main">
		
		<section id="tk-info" class="page-section">
			<div class="entry-content section-about">
				<?php 
				echo apply_filters( 'the_content', get_post( $section_about_id ) -> post_content ); 
				?>
			</div>
			<div class="entry-content section-contact">
				<?php 
				echo apply_filters( 'the_content', get_post( $section_contact_id ) -> post_content );
				?>
			</div>
			<div class="entry-content section-footer">
				<?php 
				wp_nav_menu( array( 'theme_location' => 'tk-footer-menu' ) );
				?>				
			</div>	
		</section>		

		<section id="tk-showcase" class="page-section">
			<div class="section-wrapper">
				<div class="itm-wrapper">
					<?php 
					echo $showcase_images;
					echo $showcase_movies;
					echo $showcase_svg_maps;
					?>
				</div>
			</div>	
		</section>
		
		<section id="tk-tiles" class="page-section">
		<?php
/*			
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			// Start the Loop
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

//			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
*/			
			
			sf_ajax_loader_handler();
		
		?>
		</section>
		
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();

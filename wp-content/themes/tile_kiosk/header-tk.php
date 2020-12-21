<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Tile_Kiosk
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'tile_kiosk' ); ?></a>

	<header id="masthead" class="site-header">
		
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$tile_kiosk_description = get_bloginfo( 'description', 'display' );
			if ( $tile_kiosk_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $tile_kiosk_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		
			<a id="tk-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php echo tk_get_svg( 'tk_logo' ); ?>				
			</a>			
		</div><!-- .site-branding -->

		<div id="tk-text-float">
			<div class="text-float-wrapper">
				<?php echo tk_get_svg( 'float_text' ); ?>
			</div>
		</div><!-- #tk-text-float -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'tile_kiosk' ); ?></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);		
			?>
		</nav><!-- #site-navigation -->
		
		<div id="tk-filter">
			
			<form id="tk-filter-form">
				<div class="filter-wrapper use">
					<div class="filter-options-title"><?php esc_html_e( 'Use', tk_get_theme_text_domain() ); ?></div>
					<div class="filter-options">
						<label for="filter-use-coaster" class="control control-checkbox"><?php esc_html_e( 'Coaster', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-coaster" name="tk_filter[use]" value="use?coaster">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-use-hashioki" class="control control-checkbox"><?php esc_html_e( 'Hashioki', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-hashioki" name="tk_filter[use]" value="use?hashioki">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-use-nabeshiki" class="control control-checkbox"><?php esc_html_e( 'Nabeshiki', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-nabeshiki" name="tk_filter[use]" value="use?nabeshiki">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-use-object" class="control control-checkbox"><?php esc_html_e( 'Object', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-object" name="tk_filter[use]" value="use?object">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-use-stand" class="control control-checkbox"><?php esc_html_e( 'Tool-stand', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-stand" name="tk_filter[use]" value="use?stand">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-use-plate" class="control control-checkbox"><?php esc_html_e( 'Plate', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-plate" name="tk_filter[use]" value="use?plate">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-use-tray" class="control control-checkbox"><?php esc_html_e( 'Tray', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-use-tray" name="tk_filter[use]" value="use?tray">
							<div class="control_indicator"></div>
						</label>						
					</div>
				</div>				
				<div class="filter-wrapper color">
					<div class="filter-options-title"><?php esc_html_e( 'Color', tk_get_theme_text_domain() ); ?></div>
					<div class="filter-options">
						<label for="filter-color-beige" class="control control-checkbox"><?php esc_html_e( 'Beige', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-beige" name="tk_filter[color]" value="color?Beige">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-black" class="control control-checkbox"><?php esc_html_e( 'Black', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-black" name="tk_filter[color]" value="color?Black">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-blue" class="control control-checkbox"><?php esc_html_e( 'Blue', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-blue" name="tk_filter[color]" value="color?Blue">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-brown" class="control control-checkbox"><?php esc_html_e( 'Brown', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-brown" name="tk_filter[color]" value="color?Brown">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-gray" class="control control-checkbox"><?php esc_html_e( 'Gray', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-gray" name="tk_filter[color]" value="color?Gray">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-green" class="control control-checkbox"><?php esc_html_e( 'Green', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-green" name="tk_filter[color]" value="color?Green">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-pink" class="control control-checkbox"><?php esc_html_e( 'Pink', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-pink" name="tk_filter[color]" value="color?Pink">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-purple" class="control control-checkbox"><?php esc_html_e( 'Purple', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-purple" name="tk_filter[color]" value="color?Purple">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-red" class="control control-checkbox"><?php esc_html_e( 'Red', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-red" name="tk_filter[color]" value="color?Red">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-white" class="control control-checkbox"><?php esc_html_e( 'White', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-white" name="tk_filter[color]" value="color?White">
							<div class="control_indicator"></div>
						</label>
						<label for="filter-color-yellow" class="control control-checkbox"><?php esc_html_e( 'Yellow', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-color-yellow" name="tk_filter[color]" value="color?Yellow">
							<div class="control_indicator"></div>
						</label>						
					</div>
				</div>
				<div class="filter-wrapper size">
					<div class="filter-options-title"><?php esc_html_e( 'Size', tk_get_theme_text_domain() ); ?></div>
					<div class="filter-options">
						<label for="filter-size-xs" class="control control-checkbox"><?php esc_html_e( 'XS', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-size-xs" name="tk_filter[size]" value="size_cat?XS">
							<div class="control_indicator"></div>
						</label>						
						<label for="filter-size-s" class="control control-checkbox"><?php esc_html_e( 'S', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-size-s" name="tk_filter[size]" value="size_cat?S">
							<div class="control_indicator"></div>
						</label>						
						<label for="filter-size-m" class="control control-checkbox"><?php esc_html_e( 'M', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-size-m" name="tk_filter[size]" value="size_cat?M">
							<div class="control_indicator"></div>
						</label>						
						<label for="filter-size-l" class="control control-checkbox"><?php esc_html_e( 'L', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-size-l" name="tk_filter[size]" value="size_cat?L">
							<div class="control_indicator"></div>
						</label>						
						<label for="filter-size-xl" class="control control-checkbox"><?php esc_html_e( 'XL', tk_get_theme_text_domain() ); ?>
							<input type="checkbox" id="filter-size-xl" name="tk_filter[size]" value="size_cat?XL">
							<div class="control_indicator"></div>
						</label>						
					</div>
				</div>
				<div class="filter-wrapper reset">
					<input class="filter-reset" type="reset" value="<?php esc_html_e( 'Clear Setting', tk_get_theme_text_domain() ); ?>" />
				</div>	
				
			</form>	
			
		</div><!-- #tk-filter -->
		
	</header><!-- #masthead -->

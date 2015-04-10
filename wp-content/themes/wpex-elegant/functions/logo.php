<?php
/**
 * Outputs the site logo 
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */

if ( ! function_exists( 'wpex_logo' ) ) {
	
	function wpex_logo() {

		// Vars
		$logo_img = get_theme_mod('wpex_logo');
		$blog_name = get_bloginfo( 'name' );
		$home_url = home_url(); ?>

		<div id="logo" class="clr">
			<?php if ( $logo_img ) { ?>
				<a href="<?php echo $home_url; ?>" title="<?php echo $blog_name; ?>" rel="home"><img src="<?php echo $logo_img; ?>" alt="<?php echo $blog_name; ?>" /></a>
			<?php } else { ?>
				<h2><a href="<?php echo $home_url; ?>" title="<?php echo $blog_name; ?>" rel="home"><?php echo $blog_name; ?></a></h2>
			<?php } ?>
		</div><!-- #logo -->

		<?php
	} // end wpex_copyright

} // end function_exists 
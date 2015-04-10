<?php
/**
 * Main theme support functions
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */

add_action( 'after_setup_theme', 'wpex_theme_setup' );

if ( !function_exists('wpex_theme_setup') ) {

	function wpex_theme_setup() {
	
		// Register navigation menus
		register_nav_menus (
			array(
				'main_menu'	=> __( 'Main', 'wpex' ),
			)
		);
		
		// Localization support
		load_theme_textdomain( 'wpex', get_template_directory() .'/languages' );
		
		// Enable some useful post formats for the blog
		add_theme_support( 'post-formats', array( 'video' ) );
			
		// Add theme support
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-background' );
		add_theme_support( 'post-thumbnails' );

		// Set default thumbnail size
		set_post_thumbnail_size( 150, 150 );
	
	} // end wpex_theme_setup

} // end function_exists


// Alter post formats based on custom post types
add_action( 'load-post.php','wpex_adjust_post_formats' );
add_action( 'load-post-new.php','wpex_adjust_post_formats' );
if ( !function_exists( 'wpex_adjust_post_formats' ) ) {
	function wpex_adjust_post_formats() {
		if (isset($_GET['post'])) {
			$post = get_post($_GET['post']);
			if ($post) {
				$post_type = $post->post_type;
			}
		}
		elseif ( !isset($_GET['post_type']) ) {
			$post_type = 'post';
		}
		elseif ( in_array( $_GET['post_type'], get_post_types( array('show_ui' => true ) ) ) ) {
			$post_type = $_GET['post_type'];
		}
		else {
			return; // Page is going to fail anyway
		}
		if ( 'portfolio' == $post_type ) {
			add_theme_support( 'post-formats', array( 'video', 'gallery' ) );
		}
		elseif ( 'post' == $post_type ) {
			add_theme_support( 'post-formats', array( 'video' ) );
		}
	}
}


// Flush rewrite rules for custom post types on theme activation
add_action( 'after_switch_theme', 'wpex_flush_rewrite_rules' );
function wpex_flush_rewrite_rules() {
     flush_rewrite_rules();
}
<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */


/** Theme Setup **/

if ( ! isset( $content_width ) ) {
	$content_width = 1000;
}

// Theme setup - menus, theme support, etc
require_once( get_template_directory() .'/functions/theme-setup.php' );

// Adds a feed metaboxes
require_once ( get_template_directory() .'/functions/dashboard-feed.php' );

// Splash Page
require_once ( get_template_directory() .'/functions/welcome.php' );


/** Theme Customizer **/

// General Options
require_once ( get_template_directory() .'/functions/theme-customizer/header.php' );

// Portfolio Options
require_once ( get_template_directory() .'/functions/theme-customizer/portfolio.php' );

// Blog Options
require_once ( get_template_directory() .'/functions/theme-customizer/blog.php' );

// Copyright Options
require_once ( get_template_directory() .'/functions/theme-customizer/copyright.php' );


/** Includes **/

// Define widget areas
require_once( get_template_directory() .'/functions/widget-areas.php' );

// Register the features post type
if ( get_theme_mod( 'wpex_features', '1' ) == '1' ) {
	require_once( get_template_directory() .'/functions/post-types/features.php' );
}

// Register the slides post type
if ( get_theme_mod( 'wpex_slides', '1' ) == '1' ) {
	require_once( get_template_directory() .'/functions/post-types/slides.php' );
}

// Register the portfolio post type
if ( get_theme_mod( 'wpex_portfolio', '1' ) == '1' ) {
	require_once( get_template_directory() .'/functions/post-types/portfolio.php' );
}

// Register the staff post type
if ( get_theme_mod( 'wpex_staff', '1' ) == '1' ) {
	require_once( get_template_directory() .'/functions/post-types/staff.php' );
}

// Admin only functions
if ( is_admin() ) {

	// Load the gallery metabox only if the portfolio post type is enabled
	if ( get_theme_mod( 'wpex_portfolio', '1' ) == '1' ) {
		require_once( get_template_directory() .'/functions/meta/gallery-metabox/gmb-admin.php' );
	}

	// Default meta options usage
	require_once( get_template_directory() .'/functions/meta/usage.php' );

	// Post editor tweaks
	require_once( get_template_directory() .'/functions/mce.php' );

// Non admin functions
} else {

	// Front-end Portfolio functions
	if ( get_theme_mod( 'wpex_portfolio', '1' ) == '1' ) {

		// Displays portfolio gallery images
		require_once( get_template_directory() .'/functions/meta/gallery-metabox/gmb-display.php' );

	}

	// Outputs the main site logo
	require_once( get_template_directory() .'/functions/logo.php' );

	// Loads front end css and js
	require_once( get_template_directory() .'/functions/scripts.php' );

	// Custom Menu Walker
	require_once( get_template_directory() .'/functions/menu-walker.php' );

	// Image resizing script
	require_once( get_template_directory() .'/functions/aqua-resizer.php' );

	// Returns the correct image sizes for cropping
	require_once( get_template_directory() .'/functions/featured-image.php' );

	// Comments output
	require_once( get_template_directory() .'/functions/comments-callback.php' );

	// Pagination output
	require_once( get_template_directory() .'/functions/pagination.php' );

	// Custom excerpts
	require_once( get_template_directory() .'/functions/excerpts.php' );

	// Alter posts per page for various archives
	require_once( get_template_directory() .'/functions/posts-per-page.php' );

	// Outputs the footer copyright
	require_once( get_template_directory() .'/functions/copyright.php' );

	// Outputs post meta (date, cat, comment count)
	require_once( get_template_directory() .'/functions/post-meta.php' );

	// Used for next/previous links on single posts
	require_once( get_template_directory() .'/functions/next-prev.php' );

	// Outputs the post format video
	require_once( get_template_directory() .'/functions/post-video.php' );

	// Outputs post author bio
	require_once( get_template_directory() .'/functions/post-author.php' );

	// Outputs post slider
	require_once( get_template_directory() .'/functions/post-slider.php' );

	// Adds classes to entries
	require_once( get_template_directory() .'/functions/post-classes.php' );

	// Adds a mobile search to the sidr container
	require_once( get_template_directory() .'/functions/mobile-search.php' );

	// Displays the homepage slides
	require_once( get_template_directory() .'/functions/homepage-slider.php' );
}
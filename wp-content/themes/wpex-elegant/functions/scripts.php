<?php
/**
 * This file loads custom css and js for our theme
 *
 * @package		WordPress
 * @subpackage	Elegant WPExplorer Theme
 * @since		Elegant 1.0
 */

function wpex_load_scripts() {

	/** CSS **/
	wp_enqueue_style( 'wpex-style', get_stylesheet_uri() );
	wp_enqueue_style( 'wpex-responsive', get_template_directory_uri() .'/responsive.css' );
	wp_enqueue_style( 'wpex-custom-styles', get_template_directory_uri() .'/assets/css/custom-styles.css' );
	wp_enqueue_style( 'open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic' );
	if ( function_exists( 'wpcf7_enqueue_styles') ) {
		wp_dequeue_style( 'contact-form-7' );
	}

	/** jQuery **/
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'wpex-plugins', get_template_directory_uri().'/js/plugins.js', array( 'jquery' ), '1.7.5', true );
	wp_enqueue_script( 'wpex-global', get_template_directory_uri().'/js/global.js', array( 'jquery', 'wpex-plugins' ), '1.7.5', true );
    wp_enqueue_script( 'wpex-custom-scripts', get_template_directory_uri().'/assets/js/custom-scripts.js', array( 'jquery' ), '1.7.5', true );
	
}
add_action( 'wp_enqueue_scripts','wpex_load_scripts' );
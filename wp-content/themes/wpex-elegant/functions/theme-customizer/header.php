<?php
/**
 * Header theme options
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */



add_action( 'customize_register', 'wpex_customizer_general' );
function wpex_customizer_general($wp_customize) {

	// General Section
	$wp_customize->add_section( 'wpex_header_section' , array(
		'title'      => __( 'Header', 'wpex' ),
		'priority'   => 210,
	) );

	// Logo Image
	$wp_customize->add_setting( 'wpex_logo', array(
		'type'	=> 'theme_mod'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'wpex_logo', array(
		'label'		=> __('Image Logo','wpex'),
		'section'	=> 'wpex_header_section',
		'settings'	=> 'wpex_logo',
	) ) );
		
}
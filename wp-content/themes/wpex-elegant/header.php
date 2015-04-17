<?php
/**
 * The Header for our theme.
 *
 * @package		WordPress
 * @subpackage	Elegant WPExplorer Theme
 * @since		Elegant 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <script src="//use.typekit.net/ryz6zwj.js"></script>
    <script>try{Typekit.load();}catch(e){}</script>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php if ( get_theme_mod('wpex_custom_favicon') ) { ?>
		<link rel="shortcut icon" href="<?php echo get_theme_mod('wpex_custom_favicon'); ?>" />
	<?php } ?>
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="wrap">
		<?php
        global $post;
        $post_id = $wp_query->post->ID;
		if(!is_front_page()){
            //d($post_id);

            $bg_id = get_post_thumbnail_id($post_id);
            $bg_url = wp_get_attachment_url( $bg_id );
            ?>
		<div id="header-wrap" class="clr fixed-header"  style="background-image: url('<?php print $bg_url ?>')">
            <div id="header-wrap-block">
                <header id="header" class="site-header clr container" role="banner">
                    <?php
                    // Outputs the site logo
                    wpex_logo(); ?>
                    <div id="sidr-close"><a href="#sidr-close" class="toggle-sidr-close"></a></div>
                    <div id="site-navigation-wrap">
                        <a href="#sidr-main" id="navigation-toggle"><span class="fa fa-bars"></span></a>
                        <nav id="site-navigation" class="navigation main-navigation clr" role="navigation">
                            <?php
                            // Display main menu
                            wp_nav_menu( array(
                                'theme_location'	=> 'main_menu',
                                'sort_column'		=> 'menu_order',
                                'menu_class'		=> 'dropdown-menu sf-menu',
                                'fallback_cb'		=> false,
                                'walker'			=> new WPEX_Dropdown_Walker_Nav_Menu()
                            ) ); ?>
                        </nav><!-- #site-navigation -->
                    </div><!-- #site-navigation-wrap -->
                </header><!-- #header -->
                <div class="sub-main-menu clr container">
                    <?php dynamic_sidebar( 'sub-main-menu' ); ?>
                </div>
            </div><!--header-wrap-block -->

                <?php

                $caption = get_post_meta( $post_id, 'Header Text Box', true );
                if(!empty($caption)){?>
                    <div id="header-text-box">
                        <?php print $caption; ?>
                    </div><!--header-text-box -->
                <?php } ?>
            <div class="sub-sub-main-menu clr">
                <?php dynamic_sidebar( 'sub-sub-main-menu' ); ?>
            </div>


		</div><!-- #header-wrap -->
		<?php } ?>
		<?php
		// Displays the homepage slider based on the slides custom post type
		wpex_homepage_slider(); ?>
		<?php if(!is_front_page()){?>
		<div id="main" class="site-main clr container">
<?php } ?>
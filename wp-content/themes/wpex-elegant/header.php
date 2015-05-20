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
    <script>(function() {
            var _fbq = window._fbq || (window._fbq = []);
            if (!_fbq.loaded) {
                var fbds = document.createElement('script');
                fbds.async = true;
                fbds.src = '//connect.facebook.net/en_US/fbds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(fbds, s);
                _fbq.loaded = true;
            }
            _fbq.push(['addPixelId', '420487224790880']);
        })();
        window._fbq = window._fbq || [];
        window._fbq.push(['track', 'PixelInitialized', {}]);
    </script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-37846218-1', 'auto');
        ga('send', 'pageview');

    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=420487224790880&amp;ev=PixelInitialized" /></noscript>
    <script src="//use.typekit.net/ryz6zwj.js"></script>
    <script>try{Typekit.load();}catch(e){}</script>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pondhouse_favicon.jpg" />
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="wrap">
		<?php

        $post_id = $wp_query->post->ID;
       // d($wp_query);
        //$wp_query->post->post_parent -> = ;
		if(!is_front_page()){
            //d($post_id);


            if($wp_query->query[post_type] == 'tribe_events'){
             $bg_id = get_post_thumbnail_id(80);

            }else{
                $bg_id = get_post_thumbnail_id($post_id);
            }
            $bg_url = wp_get_attachment_url( $bg_id );
            //d($post_id);
            ?>
		<div id="header-wrap" class="clr fixed-header"  <?php if(!empty($bg_url)){ ?>style="background-image: url('<?php print $bg_url ?>')" <?php }?>>
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
                $title = get_field('header_box_title');
                $text = get_field('header_box_content');

                if(!empty($title) or !empty($text)){?>
                    <div id="header-text-box">
                      <?php if(!empty($title) ){ ?> <title> <?php print $title; ?></title><?php }?>
                        <?php print $text?>
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
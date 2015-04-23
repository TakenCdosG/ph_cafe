<?php
/* 
Plugin Name: Media Grid
Plugin URI: http://codecanyon.net/item/media-grid-wordpress-responsive-portfolio/2218545?ref=LCweb
Description: Create stunning responsive portfolios using the responsive grid layout. Display videos, images, galleries and audio files. Choose the colours and the graphic settings. Set the parameters to display in the items description.
Author: Luca Montanari
Version: 3.122
Author URI: http://codecanyon.net/user/LCweb?ref=LCweb
*/  


/////////////////////////////////////////////
/////// MAIN DEFINES ////////////////////////
/////////////////////////////////////////////

// plugin path
$wp_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
define( 'MG_DIR', $wp_plugin_dir);

// plugin url
$wp_plugin_url = substr(plugin_dir_url(__FILE__), 0, -1);
define( 'MG_URL', $wp_plugin_url);


// timthumb url - also for MU
if(is_multisite()){ define('MG_TT_URL', MG_URL . '/classes/timthumb_MU.php'); }
else { define( 'MG_TT_URL', MG_URL . '/classes/timthumb.php'); }



/////////////////////////////////////////////
/////// MULTILANGUAGE SUPPORT ///////////////
/////////////////////////////////////////////

function mg_multilanguage() {
	$param_array = explode(DIRECTORY_SEPARATOR, MG_DIR);
 	$folder_name = end($param_array);
	load_plugin_textdomain( 'mg_ml', false, $folder_name . '/languages'); 
}
add_action('init', 'mg_multilanguage', 1);



/////////////////////////////////////////////
/////// MAIN SCRIPT & CSS INCLUDES //////////
/////////////////////////////////////////////

// check for jQuery UI slider
function mg_register_scripts() {
    global $wp_scripts;
    if( !is_object( $wp_scripts ) ) {return;}
	
    if( !isset( $wp_scripts->registered['jquery-ui-slider'] ) ) {
		wp_register_script('lcwp-jquery-ui-slider', MG_URL.'/js/jquery.ui.slider.min.js', 999, '1.8.16', true);
		wp_enqueue_script('lcwp-jquery-ui-slider');
	}
	else {wp_enqueue_script('jquery-ui-slider');}
 
	return true;
}


// global script enqueuing
function mg_global_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_style('mg-font-awesome', MG_URL . '/css/font-awesome/css/font-awesome.css');

	// admin css & js
	if (is_admin()) {  
		mg_register_scripts();
		wp_enqueue_style('mg_admin', MG_URL . '/css/admin.css', 999);
		
		// chosen
		wp_enqueue_style( 'lcwp-chosen-style', MG_URL.'/js/chosen/chosen.css', 999);
		
		// lcweb switch
		wp_enqueue_style( 'lc-switch', MG_URL.'/js/lc-switch/lc_switch.css', 999);
		
		// iphone checks
		wp_enqueue_style( 'lcwp-ip-checks', MG_URL.'/js/iphone_checkbox/style.css', 999); // TO REMOVE IN NEXT RELEASE
		
		// colorpicker
		wp_enqueue_style( 'mg-colpick', MG_URL.'/js/colpick/css/colpick.css', 999);
		
		// LCWP jQuery ui
		wp_enqueue_style( 'lcwp-ui-theme', MG_URL.'/css/ui-wp-theme/jquery-ui-1.8.17.custom.css', 999);
		
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-tabs' );
		
		// lightbox and thickbox
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	}
	
	if (!is_admin()) {
		// frontent JS on header or footer
		if(get_option('mg_js_head') != '1') {
			wp_enqueue_script('mg-frontend-js', MG_URL.'/js/frontend.js', 100, '3.121', true);	
		} else { 
			wp_enqueue_script('mg-frontend-js', MG_URL.'/js/frontend.js', 900, '3.121');
		}

		// frontend css
		if(!get_option('mg_inline_css') && !get_option('mg_force_inline_css')) {
			wp_enqueue_style('mg-custom-css', MG_URL. '/css/custom.css', 100);	
		}
		else {add_action('wp_head', 'mg_inline_css', 989);}	
	}
}
add_action('init', 'mg_global_scripts');

// use frontend CSS inline
function mg_inline_css(){
	echo '<style type="text/css">';
	include_once(MG_DIR.'/frontend_css.php');
	echo '</style>';
}

// loader class in footer
function mg_loader_class() {
	?>
    <script type="text/javascript">
    if(	navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
		document.body.className += ' mg_old_loader';
	} else {
		document.body.className += ' mg_new_loader';
	}
	</script>
    <?php	
}
add_action('wp_footer', 'mg_loader_class', 1);




/////////////////////////////////////////////
/////// MAIN INCLUDES ///////////////////////
/////////////////////////////////////////////

// admin menu and cpt and taxonomy
include_once(MG_DIR . '/admin_menu.php');

// taxonomy options 
include_once(MG_DIR . '/taxonomy_options.php');

// mg items metaboxes
include_once(MG_DIR . '/metaboxes.php');

// woocommerce metaboxe
include_once(MG_DIR . '/woocom_metabox.php');

// shortcode
include_once(MG_DIR . '/shortcodes.php');

// tinymce btn
include_once(MG_DIR . '/tinymce_btn.php');

// ajax
include_once(MG_DIR . '/ajax.php');

// dynamic javascript
include_once(MG_DIR . '/dynamic_js.php');

// grid preview
include_once(MG_DIR . '/grid_preview.php');

// lightbox
include_once(MG_DIR . '/lightbox.php');

// deeplink SEO HTML snapshot
include_once(MG_DIR . '/deeplink_snapshot.php');




////////////
// EASY WP THUMBS + forcing system
function mg_ewpt() {
	if(get_option('mg_ewpt_force')) {$_REQUEST['ewpt_force'] = true;}
	include_once(MG_DIR . '/classes/easy_wp_thumbs.php');	
}
add_action('init', 'mg_ewpt', 1);
////////////



////////////
// UPDATE NOTIFIER
if(!class_exists('lc_update_notifier')) {
	include_once(MG_DIR . '/lc_update_notifier.php');
}
$lcun = new lc_update_notifier(__FILE__, 'http://projects.lcweb.it/envato_update/mg.php');
////////////



//////////////////////////////////////////////////
// ACTIONS ON PLUGIN ACTIVATION
function mg_init_custom_css() {
	include_once(MG_DIR . '/functions.php');
	
	// create custom CSS
	if(!mg_create_frontend_css()) {update_option('mg_inline_css', 1);}
	else {delete_option('mg_inline_css');}
	
	// hack for non-latin characters (FROM v1.11)
	if(!get_option('mg_non_latin_char')) {
		if(mg_cust_opt_exists()) {delete_option('mg_non_latin_char');}	
		else {add_option('mg_non_latin_char', '1', '', 'yes');}
	}
	
	// update sliders (for versions < 1.3)
	mg_update_img_sliders();	
}
register_activation_hook(__FILE__, 'mg_init_custom_css');

// update sliders function
function mg_update_img_sliders() {
	global $wpdb;
	
	// retrieve all the items
	$args = array(
		'numberposts' => -1, 
		'post_type' => 'mg_items',
	);
	$posts_array = get_posts($args);
	
	if(is_array($posts_array)) {
	
		foreach($posts_array as $post) {
			$gallery_items = get_post_meta($post->ID, 'mg_slider_img', true);
			
			if(is_array($gallery_items) && count($gallery_items) > 0) {
				$new_array = array();
				foreach($gallery_items as $img_url) {
					if(filter_var($img_url, FILTER_VALIDATE_URL)) {
						$query = "SELECT ID FROM ".$wpdb->posts." WHERE guid='".$img_url."'";
						$id = (int)$wpdb->get_var($query);
						
						if(!is_int($id)) {var_dump($id); die('error during sliders update');}
						$new_array[] = $id;
					}
					else {$new_array[] = $img_url;}
				}
				
				delete_post_meta($post->ID, 'mg_slider_img');
				add_post_meta($post->ID, 'mg_slider_img', $new_array, true);
			}	
		}
	}
	
	return true;
}



//// save existing grids in term description (for versions < 3.0)
// use hook - on activation doesn't get custom taxonomy
add_action('admin_init', 'mg_update_grids_location', 1);
function mg_update_grids_location() {
	if(!get_option('mg_v3_update')) {
		include_once(MG_DIR . '/functions.php');
		$grids = get_terms('mg_grids', 'hide_empty=0');

		foreach($grids as $grid) {
			$items = get_option('mg_grid_'.$grid->term_id.'_items');
			$w = get_option('mg_grid_'.$grid->term_id.'_items_width');
			$h = get_option('mg_grid_'.$grid->term_id.'_items_height');
			$cats = get_option('mg_grid_'.$grid->term_id.'_cats');
			
			// create description array
			$arr = array('items' => array(), 'cats' => $cats);	
			if(is_array($items)) {
				for($a=0; $a < count($items); $a++) {
					if(!$w) {
						$cell_w = get_post_meta($items[$a], 'mg_width', true);
						$cell_h = get_post_meta($items[$a], 'mg_height', true);
					}
					else {
						$cell_w = $w[$a];
						$cell_h = $h[$a];	
					}
					
					$arr['items'][] = array(
						'id'	=> $items[$a],
						'w' 	=> $cell_w,
						'h' 	=> $cell_h,
						'm_w' 	=> (in_array($cell_w, mg_mobile_sizes())) ? $cell_w : '1_2',
						'm_h' 	=> (in_array($cell_h, mg_mobile_sizes()) || $cell_h == 'auto') ? $cell_h : '1_3'
					);
				}
			}
			wp_update_term($grid->term_id, 'mg_grids', array('description' => serialize($arr)));
		}
		update_option('mg_v3_update', 1);
	}
}



//////////////////////////////////////////////////
// REMOVE WP HELPER FROM PLUGIN PAGES

function mg_remove_wp_helper() {
	$cs = get_current_screen();
	$hooked = array('mg_items_page_mg_settings', 'mg_items_page_mg_builder');
	
	if(in_array($cs->base, $hooked)) {
		echo '
		<style type="text/css">
		#screen-meta-links {display: none;}
		</style>';	
	}
	
	//var_dump(get_current_screen()); // debug
}
add_action('admin_head', 'mg_remove_wp_helper', 999);

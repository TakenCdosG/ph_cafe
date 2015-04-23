<?php 
include_once(MG_DIR . '/functions.php');

// item types array
$types = mg_main_types();

$wooc_active = mg_woocomm_active();
if($wooc_active) {$wc_attr = wc_get_attribute_taxonomies();}
?>

<div class="wrap lcwp_form">  
	<div class="icon32"><img src="<?php echo MG_URL.'/img/mg_icon.png'; ?>" alt="mediagrid" /><br/></div>
    <?php echo '<h2 class="lcwp_page_title" style="border: none;">' . __( 'Media Grid Settings', 'mg_ml') . "</h2>"; ?>  

    <?php
	// HANDLE DATA
	if(isset($_POST['lcwp_admin_submit'])) { 
		if (!isset($_POST['pg_nonce']) || !wp_verify_nonce($_POST['pg_nonce'], __FILE__)) {die('<p>Cheating?</p>');};
		include(MG_DIR . '/classes/simple_form_validator.php');		
		
		$validator = new simple_fv;
		$indexes = array();
		
		$indexes[] = array('index'=>'mg_cells_margin', 'label'=>__('Cells Margin', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_cells_img_border', 'label'=>__('Image Border', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_cells_radius', 'label'=>__('Cells Border Radius', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_cells_border', 'label'=>__('Cells Outer Border', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_cells_shadow', 'label'=>__('Cells Shadow', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_maxwidth', 'label'=>__( 'Grid max width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_mobile_treshold', 'label'=>__('Mobile layout treshold', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_thumb_q', 'label'=>__( 'Thumbnail quality', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_tu_custom_padding', 'label'=>__( 'Title under images - custom padding', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_inl_txt_padding', 'label'=>__('Inline texts padding', 'mg_ml' ), 'required'=>true, 'type'=>'int');
		$indexes[] = array('index'=>'mg_clean_inl_txt', 'label'=>'Clean inline text box');
		$indexes[] = array('index'=>'mg_delayed_fx', 'label'=>'Show all items without delay');
		
		$indexes[] = array('index'=>'mg_filters_behav', 'label'=>'Filtered items behavior');
		$indexes[] = array('index'=>'mg_filters_align', 'label'=>'Filters Alignment', 'mg_ml');
		$indexes[] = array('index'=>'mg_dd_mobile_filter', 'label'=>'Use dropdown on mobile screens');
		$indexes[] = array('index'=>'mg_use_old_filters', 'label'=>'Use old filters style');
		$indexes[] = array('index'=>'mg_all_filter_txt', 'label'=>'"All" filter text');
		
		$indexes[] = array('index'=>'mg_item_width', 'label'=>__('Lightbox percentage width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_item_maxwidth', 'label'=>__('Lightbox maximum width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_lb_padding', 'label'=>__( 'Lightbox padding', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_lb_border_w', 'label'=>__( 'Lightbox border width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_item_radius', 'label'=>__( 'Lightbox border radius', 'mg_ml' ), 'type'=>'int');	
		$indexes[] = array('index'=>'mg_lb_shadow', 'label'=>'Lightbox shadow style');	
		$indexes[] = array('index'=>'mg_modal_lb', 'label'=>__( 'Use Lightbox modal mode', 'mg_ml' ));	
		$indexes[] = array('index'=>'mg_lb_touchswipe', 'label'=>'Use touchswipe in lightbox');	
		$indexes[] = array('index'=>'mg_lb_socials_style', 'label'=>'Lightbox socials style');
		$indexes[] = array('index'=>'mg_lb_cmd_pos', 'label'=>'Lightbox commands position');
		
		$indexes[] = array('index'=>'mg_audio_autoplay', 'label'=>__( 'Audio player autoplay', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_audio_tracklist', 'label'=>__( 'Display full Tracklistlist', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_video_autoplay', 'label'=>__( 'Video player autoplay', 'mg_ml' ));		
		
		$indexes[] = array('index'=>'mg_slider_style', 'label'=>'Slider style');
		$indexes[] = array('index'=>'mg_slider_fx', 'label'=>__( 'Slider transition effect', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_slider_fx_time', 'label'=>__( 'Slider transition duration', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_slider_interval', 'label'=>__( 'Slider - Slideshow interval', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_slider_main_w_val', 'label'=>__( 'Slider - Global width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_slider_main_w_type', 'label'=>__( 'Slider - Global width type', 'mg_ml' ), );		
		$indexes[] = array('index'=>'mg_disable_rclick', 'label'=>__( 'Disable right click', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_facebook', 'label'=>__( 'Facebook Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_twitter', 'label'=>__( 'Twitter Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_pinterest', 'label'=>__( 'Pinterest Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_googleplus', 'label'=>__( 'Google+ Button', 'mg_ml' ));
		
		$indexes[] = array('index'=>'mg_integrate_wc', 'label'=>'Integrate WooCommerce');
		$indexes[] = array('index'=>'mg_wc_hide_add_to_cart', 'label'=>'WooCommerce - Hide add-to-cart button');
		$indexes[] = array('index'=>'mg_wc_hide_attr', 'label'=>'WooCommerce - Hide product attributes');
		
		$indexes[] = array('index'=>'mg_preview_pag', 'label'=>__( 'Preview container', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_disable_dl', 'label'=>__( 'Disable Deeplinking', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_force_inline_css', 'label'=>__( 'Force inline css usage', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_ewpt_force', 'label'=>'EWPT Forcing system');
		$indexes[] = array('index'=>'mg_use_timthumb', 'label'=>'Use TimThumb');
		$indexes[] = array('index'=>'mg_js_head', 'label'=>'Javascript in Head');
		$indexes[] = array('index'=>'mg_enable_ajax', 'label'=>__( 'Enable Ajax Support', 'mg_ml' ));
		
		$indexes[] = array('index'=>'mg_loader_color', 'label'=>__( 'Loader color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_cells_border_color', 'label'=>__( 'Cells border color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_img_border_color', 'label'=>__( 'Image Border Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_img_border_opacity', 'label'=>__( 'Image Border Opacity', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_main_overlay_color', 'label'=>__( 'Main Overlay Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_main_overlay_opacity', 'label'=>__( 'Main Overlay Opacity', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_second_overlay_color', 'label'=>__( 'Second Overlay Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_icons_col', 'label'=>__( 'Icons Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_overlay_title_color', 'label'=>__( 'Second Overlay Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_txt_under_color', 'label'=>__('Text under images color', 'mg_ml' ), 'type'=>'hex');

		$indexes[] = array('index'=>'mg_filters_txt_color', 'label'=>__( 'Filters Text Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_filters_bg_color', 'label'=>__( 'Filters Background Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_border_color', 'label'=>__( 'Filters Border Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_txt_color_h', 'label'=>__( 'Filters Text Color - hover status', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_filters_bg_color_h', 'label'=>__( 'Filters Background Color - hover status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_border_color_h', 'label'=>__( 'Filters Border Color - hover status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_txt_color_sel', 'label'=>__( 'Filters Text Color - selected status', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_filters_bg_color_sel', 'label'=>__( 'Filters Background Color - selected status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_border_color_sel', 'label'=>__( 'Filters Border Color - selected status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_radius', 'label'=>__( 'Filter Border Radius', 'mg_ml' ), 'type'=>'int');

		$indexes[] = array('index'=>'mg_item_overlay_color', 'label'=>__( 'Lightbox Overlay color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_overlay_opacity', 'label'=>__( 'Lightbox Overlay opacity', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_item_overlay_pattern', 'label'=>__( 'Lightbox Overlay pattern', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_item_bg_color', 'label'=>__( 'Lightbox background color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_border_color', 'label'=>__( 'Lightbox border color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_txt_color', 'label'=>__('Lightbox text color', 'mg_ml'), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_icons_color', 'label'=>__('Lightbox icons color', 'mg_ml'), 'type'=>'hex');
		
		$indexes[] = array('index'=>'mg_custom_css', 'label'=>__( 'Custom CSS', 'mg_ml' ));
		
		if(is_multisite() && get_option('mg_use_timthumb')) {
			$indexes[] = array('index'=>'mg_wpmu_path', 'label'=>__('JS for old jQuery', 'mg_ml'), 'required'=>true);		
		}

		// type options
		foreach($types as $type => $name) {
			$indexes[] = array('index'=>'mg_'.$type.'_opt_icon', 'label' => $name.' option icon');
			$indexes[] = array('index'=>'mg_'.$type.'_opt', 'label' => $name.' '.__('Options', 'mg_ml'), 'max_len'=>150);	
		}
		
		// woocommerce attributes
		if(isset($wc_attr) && is_array($wc_attr)) {
			foreach($wc_attr as $attr) {
				$indexes[] = array('index'=>'mg_wc_attr_'.sanitize_title($attr->attribute_label).'_icon', 'label' => $attr->attribute_label.' attr icon');	
			}
		}

		//// overlay manager add-on ////////
		if(defined('MGOM_DIR')) {
			$indexes[] = array('index'=>'mg_default_overlay', 'label'=>__( 'Default Overlay', 'mg_ml' ));
		}
		////////////////////////////////////

		$validator->formHandle($indexes);
		$fdata = $validator->form_val;

		// opt builder custom validation
		foreach($types as $type => $name) {
			if($fdata['mg_'.$type.'_opt']) {
				$a = 0;
				foreach($fdata['mg_'.$type.'_opt'] as $opt_val) {
					if(trim($opt_val) == '') {unset($fdata['mg_'.$type.'_opt'][$a]);}
					$a++;
				}
				
				if( count(array_unique($fdata['mg_'.$type.'_opt'])) < count($fdata['mg_'.$type.'_opt']) ) {
					$validator->custom_error[$name.' '.__('Options', 'mg_ml')] = __('There are duplicate values', 'mg_ml');
				}
			}
		}
		
		$error = $validator->getErrors();
		
		if($error) {echo '<div class="error"><p>'.$error.'</p></div>';}
		else {
			// clean data and save options
			foreach($fdata as $key=>$val) {
				if(!is_array($val)) {
					$fdata[$key] = stripslashes($val);
				}
				else {
					$fdata[$key] = array();
					foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
				}
				
				if($fdata[$key] === false) {delete_option($key);}
				else {
					if(!get_option($key)) { add_option($key, '255', '', 'yes'); }
					update_option($key, $fdata[$key]);	
				}
			}
			
			// create frontend.css else print error
			if(!get_option('mg_inline_css')) {
				if(!mg_create_frontend_css()) {
					update_option('mg_inline_css', 1);	
					echo '<div class="updated"><p>'. __('An error occurred during dynamic CSS creation. The code will be used inline anyway', 'mg_ml') .'</p></div>';
				}
				else {delete_option('mg_inline_css');}
			}
			
			echo '<div class="updated"><p><strong>'. __('Options saved.', 'mg_ml') .'</strong></p></div>';
		}
	}
	
	else {  
		// Normal page display
		$fdata['mg_cells_margin'] = get_option('mg_cells_margin', 5);  
		$fdata['mg_cells_img_border'] = get_option('mg_cells_img_border', 3);  
		$fdata['mg_cells_radius'] = get_option('mg_cells_radius', 2); 
		$fdata['mg_cells_border'] = get_option('mg_cells_border'); 
		$fdata['mg_cells_shadow'] = get_option('mg_cells_shadow'); 
		$fdata['mg_maxwidth'] = get_option('mg_maxwidth', 1100); 
		$fdata['mg_mobile_treshold'] = get_option('mg_mobile_treshold', 800);
		$fdata['mg_thumb_q'] = get_option('mg_thumb_q', 85);
		$fdata['mg_tu_custom_padding'] = get_option('mg_tu_custom_padding', array('8', '15', '8', '15'));
		$fdata['mg_inl_txt_padding'] = get_option('mg_inl_txt_padding', array('15', '15', '15', '15'));
		$fdata['mg_clean_inl_txt'] = get_option('mg_clean_inl_txt');
		$fdata['mg_delayed_fx'] = get_option('mg_delayed_fx');
		
		$fdata['mg_filters_behav'] = get_option('mg_filters_behav');
		$fdata['mg_filters_align'] = get_option('mg_filters_align');
		$fdata['mg_dd_mobile_filter'] = get_option('mg_dd_mobile_filter');
		$fdata['mg_use_old_filters'] = get_option('mg_use_old_filters');
		$fdata['mg_all_filter_txt'] = get_option('mg_all_filter_txt');
		
		$fdata['mg_item_width'] = get_option('mg_item_width', 70); 
		$fdata['mg_item_maxwidth'] = get_option('mg_item_maxwidth', 960);
		$fdata['mg_lb_padding'] = get_option('mg_lb_padding', 20);
		$fdata['mg_lb_border_w'] = get_option('mg_lb_border_w', 0);
		$fdata['mg_item_radius'] = get_option('mg_item_radius', 2);
		$fdata['mg_lb_shadow'] = get_option('mg_lb_shadow', 'soft');
		$fdata['mg_modal_lb'] = get_option('mg_modal_lb'); 
		$fdata['mg_lb_touchswipe'] = get_option('mg_lb_touchswipe');
		$fdata['mg_lb_socials_style'] = get_option('mg_lb_socials_style');
		$fdata['mg_lb_cmd_pos'] = get_option('mg_lb_cmd_pos');
		
		$fdata['mg_audio_autoplay'] = get_option('mg_audio_autoplay');
		$fdata['mg_audio_tracklist'] = get_option('mg_audio_tracklist');
		$fdata['mg_video_autoplay'] = get_option('mg_video_autoplay');		
		
		$fdata['mg_slider_style'] = get_option('mg_slider_style');
		$fdata['mg_slider_fx'] = get_option('mg_slider_fx', 'fadeslide');
		$fdata['mg_slider_fx_time'] = get_option('mg_slider_fx_time', 400);
		$fdata['mg_slider_interval'] = get_option('mg_slider_interval', 3000);
		$fdata['mg_slider_main_w_val'] = get_option('mg_slider_main_w_val', 55);
		$fdata['mg_slider_main_w_type'] = get_option('mg_slider_main_w_type', '%');	
		$fdata['mg_disable_rclick'] = get_option('mg_disable_rclick');
		$fdata['mg_facebook'] = get_option('mg_facebook');
		$fdata['mg_twitter'] = get_option('mg_twitter');  
		$fdata['mg_pinterest'] = get_option('mg_pinterest'); 
		$fdata['mg_googleplus'] = get_option('mg_googleplus'); 
		
		$fdata['mg_integrate_wc'] = get_option('mg_integrate_wc'); 
		$fdata['mg_wc_hide_add_to_cart'] = get_option('mg_wc_hide_add_to_cart'); 
		$fdata['mg_wc_hide_attr'] = get_option('mg_wc_hide_attr'); 
		
		$fdata['mg_preview_pag'] = get_option('mg_preview_pag'); 
		$fdata['mg_disable_dl'] = get_option('mg_disable_dl'); 
		$fdata['mg_force_inline_css'] = get_option('mg_force_inline_css');
		$fdata['mg_ewpt_force'] = get_option('mg_ewpt_force'); 
		$fdata['mg_use_timthumb'] = get_option('mg_use_timthumb'); 
		$fdata['mg_js_head'] = get_option('mg_js_head'); 
		$fdata['mg_enable_ajax'] = get_option('mg_enable_ajax'); 
		$fdata['mg_wpmu_path'] = get_option('mg_wpmu_path'); 
		
		$fdata['mg_loader_color'] = get_option('mg_loader_color', '#888888'); 
		$fdata['mg_cells_border_color'] = get_option('mg_cells_border_color', '#cccccc'); 
		$fdata['mg_img_border_color'] = get_option('mg_img_border_color', '#fdfdfd');  
		$fdata['mg_img_border_opacity'] = get_option('mg_img_border_opacity', 100); 
		$fdata['mg_main_overlay_color'] = get_option('mg_main_overlay_color', '#FFFFFF'); 
		$fdata['mg_main_overlay_opacity'] = get_option('mg_main_overlay_opacity', 80); 
		$fdata['mg_second_overlay_color'] = get_option('mg_second_overlay_color', '#555555');
		$fdata['mg_icons_col'] = get_option('mg_icons_col', '#ffffff'); 
		$fdata['mg_overlay_title_color'] = get_option('mg_overlay_title_color', '#222222');
		$fdata['mg_txt_under_color'] = get_option('mg_txt_under_color', '#333333');
		
		$fdata['mg_filters_txt_color'] = get_option('mg_filters_txt_color', '#444444'); 
		$fdata['mg_filters_bg_color'] = get_option('mg_filters_bg_color', '#ffffff');
		$fdata['mg_filters_border_color'] = get_option('mg_filters_border_color', '#999999'); 
		$fdata['mg_filters_txt_color_h'] = get_option('mg_filters_txt_color_h', '#666666'); 
		$fdata['mg_filters_bg_color_h'] = get_option('mg_filters_bg_color_h', '#ffffff'); 
		$fdata['mg_filters_border_color_h'] = get_option('mg_filters_border_color_h', '#666666');
		$fdata['mg_filters_txt_color_sel'] = get_option('mg_filters_txt_color_sel', '#222222'); 
		$fdata['mg_filters_bg_color_sel'] = get_option('mg_filters_bg_color_sel', '#ffffff'); 
		$fdata['mg_filters_border_color_sel'] = get_option('mg_filters_border_color_sel', '#555555');
		$fdata['mg_filters_radius'] = get_option('mg_filters_radius', 2); 
		
		$fdata['mg_item_overlay_color'] = get_option('mg_item_overlay_color', '#FFFFFF'); 
		$fdata['mg_item_overlay_opacity'] = get_option('mg_item_overlay_opacity', 80); 
		$fdata['mg_item_overlay_pattern'] = get_option('mg_item_overlay_pattern'); 
		$fdata['mg_item_bg_color'] = get_option('mg_item_bg_color', '#FFFFFF'); 
		$fdata['mg_item_border_color'] = get_option('mg_item_border_color', '#e5e5e5'); 
		$fdata['mg_item_txt_color'] = get_option('mg_item_txt_color', '#262626');
		$fdata['mg_item_icons_color'] = get_option('mg_item_icons_color', '#333333');
		
		$fdata['mg_custom_css'] = get_option('mg_custom_css'); 
		
		//// overlay manager add-on
		if(defined('MGOM_DIR')) {$fdata['mg_default_overlay'] = get_option('mg_default_overlay');}
		//////

		// custom options
		foreach($types as $type => $name) {
			$fdata['mg_'.$type.'_opt_icon'] = get_option('mg_'.$type.'_opt_icon');
			$fdata['mg_'.$type.'_opt'] = get_option('mg_'.$type.'_opt'); 
		}
		
		// woocommerce attributes
		if(isset($wc_attr) && is_array($wc_attr)) {
			foreach($wc_attr as $attr) {
				$fdata['mg_wc_attr_'.sanitize_title($attr->attribute_label).'_icon'] = get_option('mg_wc_attr_'.sanitize_title($attr->attribute_label).'_icon');	
			}
		}
		
		// fix for secondary overlay color v2.3 to v2.4
		if(!preg_match('/^#[a-f0-9]{6}$/i', $fdata['mg_icons_col']) && !isset($_POST['mg_icons_col'])) {$fdata['mg_icons_col'] = '#ffffff';}
	}  
	?>


	<br/>
    <div id="tabs">
    <form name="lcwp_admin" method="post" class="form-wrap" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	
    <ul class="tabNavigation">
    	<li><a href="#layout_opt"><?php _e('Main Options', 'mg_ml') ?></a></li>
        <li><a href="#color_opt"><?php _e('Colors', 'mg_ml') ?></a></li>
        <li><a href="#opt_builder"><?php _e('Item Attributes', 'mg_ml') ?></a></li>
        <li><a href="#advanced"><?php _e('Custom CSS', 'mg_ml') ?></a></li>
    </ul>    
        
    
    <div id="layout_opt"> 
    	<h3><?php _e("Predefined Styles", 'mg_ml'); ?></h3>
        
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Choose a style", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e('Select a style', 'mg_ml') ?> .." name="mg_pred_styles" id="mg_pred_styles" class="lcweb-chosen" tabindex="2">
                	<option value="" selected="selected"></option>
                  <?php 
                  $styles = mg_predefined_styles();
                  foreach($styles as $style => $val) { 
				  	echo '<option value="'.$style.'">'.$style.'</option>'; 
				  }
                  ?>
                </select>
            </td>
            <td>
            	<input type="button" name="mg_set_style" id="mg_set_style" value="<?php _e('Set', 'mg_ml') ?>" class="button-secondary" />
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Preview", 'mg_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<?php
				$styles = mg_predefined_styles();
                foreach($styles as $style => $val) { 
				  echo '<img src="'.MG_URL.'/img/pred_styles_demo/'.$val['preview'].'" class="mg_styles_preview" alt="'.$style.'" style="display: none;" />';	
				}
				?>
            </td>
          </tr>
        </table>
        
       
        <h3><?php _e("Grid Layout", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Grid Cells Margin", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="25" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_cells_margin']; ?>" name="mg_cells_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the space between cells', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Size", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_cells_img_border']; ?>" name="mg_cells_img_border" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the cells border size', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Cells Border Radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="25" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_cells_radius']; ?>" name="mg_cells_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the cells border radius', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the outer cell border?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_cells_border'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_cells_border" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the cells external border', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Cell Shadow?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_cells_shadow'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_cells_shadow" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays a soft shadow around cells', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Grid max width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="20" max="1960" min="860"></div>
                <input type="text" value="<?php echo(int)$fdata['mg_maxwidth']; ?>" name="mg_maxwidth" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the maximum width of the grid (used only for thumbnails sharpness, default: 960)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Mobile layout treshold", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="20" max="900" min="500"></div>
                <input type="text" value="<?php echo(int)$fdata['mg_mobile_treshold']; ?>" name="mg_mobile_treshold" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set browser width treshold to use mobile mode (default: 800)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails quality", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="100" min="30"></div>
                <input type="text" value="<?php echo(int)$fdata['mg_thumb_q']; ?>" name="mg_thumb_q" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Set the thumbnail quality. Low value = lighter but fuzzier images (default: 85%)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Title under images - custom padding", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo $fdata['mg_tu_custom_padding'][0]; ?>" name="mg_tu_custom_padding[]" class="lcwp_slider_input" />
                <input type="text" value="<?php echo $fdata['mg_tu_custom_padding'][1]; ?>" name="mg_tu_custom_padding[]" class="lcwp_slider_input" />
                <input type="text" value="<?php echo $fdata['mg_tu_custom_padding'][2]; ?>" name="mg_tu_custom_padding[]" class="lcwp_slider_input" />
                <input type="text" value="<?php echo $fdata['mg_tu_custom_padding'][3]; ?>" name="mg_tu_custom_padding[]" class="lcwp_slider_input" /> px
            </td>
            <td><span class="info"><?php _e('Custom padding values for title under images (top-left-bottom-right) - leave empty to use default one', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Inline texts padding", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo $fdata['mg_inl_txt_padding'][0]; ?>" name="mg_inl_txt_padding[]" class="lcwp_slider_input" />
                <input type="text" value="<?php echo $fdata['mg_inl_txt_padding'][1]; ?>" name="mg_inl_txt_padding[]" class="lcwp_slider_input" />
                <input type="text" value="<?php echo $fdata['mg_inl_txt_padding'][2]; ?>" name="mg_inl_txt_padding[]" class="lcwp_slider_input" />
                <input type="text" value="<?php echo $fdata['mg_inl_txt_padding'][3]; ?>" name="mg_inl_txt_padding[]" class="lcwp_slider_input" /> px
            </td>
            <td><span class="info"><?php _e('Padding values for inline texts (top-left-bottom-right)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Clean inline text box?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_clean_inl_txt'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_clean_inl_txt" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, remove shadows, borders and background for inline text boxes', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Show items without delay?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_delayed_fx'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_delayed_fx" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, show grid items without delayed effect', 'mg_ml') ?></span></td>
          </tr>

          <?php
          //// overlay manager add-on //////////////
		  //////////////////////////////////////////
		  if(defined('MGOM_DIR')) : ?>
          <tr>
            <td class="lcwp_label_td"><?php _e("Default Overlay", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_default_overlay" class="lcweb-chosen" data-placeholder="<?php _e("Select an overlay", 'mg_ml'); ?> .." tabindex="2">
                  <option value="">(<?php _e('original one', 'mg_ml') ?>)</option>
                  
                  <?php
				  $overlays = get_terms('mgom_overlays', 'hide_empty=0');
				  foreach($overlays as $ol) {
						$sel = ($ol->term_id == $fdata['mg_default_overlay']) ? 'selected="selected"' : '';
						echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
				  }
				  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the default overlay to apply", 'mg_ml'); ?> - overlay manager add-on</span></td>
          </tr>
		  <?php endif;
          //////////////////////////////////////////
          ?>
        </table> 
        
        
        <h3><?php _e("Item filters", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Filtered items behavior", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_filters_behav" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." tabindex="2">
                  <option value="standard"><?php _e('Hide and reorder', 'mg_ml') ?></option>
                  <option value="opacity" <?php if($fdata['mg_filters_behav'] == 'opacity') {echo 'selected="selected"';} ?>><?php _e('Reduce opacity', 'mg_ml') ?></option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select filtered items behavior", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Filters alignment", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_filters_align" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." tabindex="2">
                  <option value="left">Left</option>
                  <option value="center" <?php if($fdata['mg_filters_align'] == 'center') {echo 'selected="selected"';} ?>>Center</option>
                  <option value="right" <?php if($fdata['mg_filters_align'] == 'right') {echo 'selected="selected"';} ?>>Right</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the filters alignment", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use dropdown on mobile mode?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_dd_mobile_filter'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_dd_mobile_filter" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, replace filters with a dropdown on mobile mode', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use old filters style?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_use_old_filters'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_use_old_filters" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, use the old Media Grid filters style', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e('"All" filter text', 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo mg_sanitize_input($fdata['mg_all_filter_txt']) ?>" name="mg_all_filter_txt" />
            </td>
            <td><span class="info"><?php _e('Set a different text for the "ALL" filter (leave empty to use default)', 'mg_ml') ?></span></td>
          </tr>
        </table>
        
        
        <h3><?php _e("Item's Lightbox", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Elastic width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="100" min="30"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_item_width']; ?>" name="mg_item_width" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Width percentage of the lightbox in relation to the screen (default: 70)', 'mg_ml') ?></span></td>
          </tr> 
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Maximum width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo (int)$fdata['mg_item_maxwidth']; ?>" name="mg_item_maxwidth" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Maximum width in pixels for the lightbox (default: 960)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Padding", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_lb_padding']; ?>" name="mg_lb_padding" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set lightbox padding (default 20 - if commands are inside, top padding is 40px)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_lb_border_w']; ?>" name="mg_lb_border_w" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the border lightbox border width', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_item_radius']; ?>" name="mg_item_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the border radius for the lightbox corners', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Shadow style", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_lb_shadow" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." autocomplete="off">
                  <option value="none"><?php _e('No shadow', 'mg_ml') ?></option>
                  <option value="soft" <?php if($fdata['mg_lb_shadow'] == 'soft') echo 'selected="selected"' ?>><?php _e('Soft', 'mg_ml') ?></option>
                  <option value="heavy" <?php if($fdata['mg_lb_shadow'] == 'heavy') echo 'selected="selected"' ?>><?php _e('Heavy', 'mg_ml') ?></option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the lightbox shadow style", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use as modal?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_modal_lb'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_modal_lb" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, only the close button will hide the lightbox', 'mg_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Use touchSwipe?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_lb_touchswipe'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_lb_touchswipe" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, use the touchSwipe navigation on mobile devices', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Socials style", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_lb_socials_style" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." autocomplete="off">
                  <option value="squared"><?php _e('Squared', 'mg_ml') ?></option>
                  <option value="rounded" <?php if($fdata['mg_lb_socials_style'] == 'rounded') echo 'selected="selected"' ?>><?php _e('Rounded', 'mg_ml') ?></option>
                  <option value="minimal" <?php if($fdata['mg_lb_socials_style'] == 'minimal') echo 'selected="selected"' ?>><?php _e('Minimal', 'mg_ml') ?></option>
                  <option value="old" <?php if($fdata['mg_lb_socials_style'] == 'old') echo 'selected="selected"' ?>><?php _e('Old style (images)', 'mg_ml') ?></option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the style for social icons", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Commands position", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_lb_cmd_pos" class="lcweb-chosen" data-placeholder="<?php _e("Select an option", 'mg_ml'); ?> .." autocomplete="off">
                  <option value="inside"><?php _e('Inside lightbbox', 'mg_ml') ?></option>
                  <option value="top" <?php if($fdata['mg_lb_cmd_pos'] == 'top') echo 'selected="selected"' ?>><?php _e('Detached - top of the page', 'mg_ml') ?></option>
                  <option value="side" <?php if($fdata['mg_lb_cmd_pos'] == 'side') echo 'selected="selected"' ?>><?php _e('Detached - on sides', 'mg_ml') ?></option>
                  <option value="ins_hidden" <?php if($fdata['mg_lb_cmd_pos'] == 'ins_hidden') echo 'selected="selected"' ?>><?php _e('Inside - Hidden', 'mg_ml') ?></option>
                  <option value="hidden" <?php if($fdata['mg_lb_cmd_pos'] == 'hidden') echo 'selected="selected"' ?>><?php _e('Detached - Hidden', 'mg_ml') ?></option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select lightbox commands position. On mobile, they will be forced inside lightbox", 'mg_ml'); ?></span></td>
          </tr>
        </table> 
        
       	<h3><?php _e("Audio & video players", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Autoplay tracks?" ); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_audio_autoplay'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_audio_autoplay" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked autoplays the tracks in the audio player', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the full tracklist?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_audio_tracklist'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_audio_tracklist" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked shows the full tracklist in the player', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Autoplay videos?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_video_autoplay'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_video_autoplay" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked autoplays videos', 'mg_ml') ?></span></td>
          </tr>  
        </table>
        
        <h3><?php _e("Slider", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Style", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_slider_style" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." tabindex="2">
                  <option value="light">Light</option>
                  <option value="dark" <?php if($fdata['mg_slider_style'] == 'dark') {echo 'selected="selected"';} ?>>Dark</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the slider style", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Height", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" class="lcwp_slider_input" name="mg_slider_main_w_val" value="<?php echo $fdata['mg_slider_main_w_val']; ?>" maxlength="3">
                <select name="mg_slider_main_w_type" style="width: 50px; margin-left: -5px;">
                  <option value="%">%</option>
                  <option value="px" <?php if($fdata['mg_slider_main_w_type'] == 'px') {echo 'selected="selected"';} ?>>px</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Default slider height (% is related to the width)", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Transition effect", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_slider_fx" class="lcweb-chosen" data-placeholder="<?php _e("Select a transition", 'mg_ml'); ?> .." tabindex="2">
                  <?php	
                  foreach(mg_galleria_fx() as $key => $val) {
					  ($key == $fdata['mg_slider_fx']) ? $sel = 'selected="selected"' : $sel = '';
					  echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the transition effect between slides", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Transition duration", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="50" max="1000" min="100"></div>
                <input type="text" value="<?php echo $fdata['mg_slider_fx_time']; ?>" name="mg_slider_fx_time" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("How much time the transition takes (in milliseconds - default 400)", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Slideshow interval", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="500" max="8000" min="1000"></div>
                <input type="text" value="<?php echo $fdata['mg_slider_interval']; ?>" name="mg_slider_interval" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("How long each slide will be shown (in milliseconds - default 3000)", 'mg_ml'); ?></span></td>
          </tr>
        </table>
        
        <h3><?php _e("Image Protection", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable right click" ); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_disable_rclick'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_disable_rclick" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('Check to disable right click on grid images', 'mg_ml') ?></span></td>
          </tr>
        </table>    
        
        <h3><?php _e("Socials", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Facebook button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_facebook'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_facebook" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Facebook button in lightbox', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Twitter button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_twitter'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_twitter" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Twitter button in lightbox', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Pinterest button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_pinterest'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_pinterest" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Pinterest button in lightbox', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Google+ button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_googleplus'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_googleplus" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Google+ button in lightbox (<strong>only with deeplinking</strong>)', 'mg_ml') ?></span></td>
          </tr>
        </table> 
        
        <?php 
		// woocommerce options
		if($wooc_active) : ?>
            <h3><?php _e("WooCommerce", 'mg_ml'); ?></h3>
            <table class="widefat lcwp_table">
              <tr>
                <td class="lcwp_label_td"><?php _e('Enable WooCommerce integration?', 'mg_ml'); ?></td>
                <td class="lcwp_field_td">
					<?php ($fdata['mg_integrate_wc'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                    <input type="checkbox" value="1" name="mg_integrate_wc" class="ip-checkbox" <?php echo $sel; ?> />
                </td>
                <td><span class="info"><?php _e('If checked allow products usage in grids', 'mg_ml'); ?></span></td>
              </tr>
              <tr>
                <td class="lcwp_label_td"><?php _e('Hide "add to cart" button?', 'mg_ml'); ?></td>
                <td class="lcwp_field_td">
					<?php ($fdata['mg_wc_hide_add_to_cart'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                    <input type="checkbox" value="1" name="mg_wc_hide_add_to_cart" class="ip-checkbox" <?php echo $sel; ?> />
                </td>
                <td><span class="info"><?php _e("If checked hide the AJAX add-to-cart button in lightbox", 'mg_ml'); ?></span></td>
              </tr>
              <tr>
                <td class="lcwp_label_td"><?php _e("Hide product attributes?", 'mg_ml'); ?></td>
                <td class="lcwp_field_td">
					<?php ($fdata['mg_wc_hide_attr'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                    <input type="checkbox" value="1" name="mg_wc_hide_attr" class="ip-checkbox" <?php echo $sel; ?> />
                </td>
                <td><span class="info"><?php _e("If checked hide product attributes in lightbox", 'mg_ml'); ?></span></td>
              </tr>
            </table> 
        <?php endif; ?> 
        
        
        <?php 
		// Timthumb basepath on multisite
		if(is_multisite() && get_option('mg_use_timthumb')) : ?>
            <h3><?php _e("Timthumb basepath", 'mg_ml'); ?> <small>(<?php _e('for MU installations', 'mg_ml') ?>)</small></h3>
            <table class="widefat lcwp_table">
              <tr>
                <td class="lcwp_label_td"><?php _e("Basepath of the WP MU images", 'mg_ml'); ?></td>
                <td>
                    <?php if(!$fdata['mg_wpmu_path'] || trim($fdata['mg_wpmu_path']) == '') { $fdata['mg_wpmu_path'] = mg_wpmu_upload_dir();} ?>
                    <input type="text" value="<?php echo $fdata['mg_wpmu_path'] ?>" name="mg_wpmu_path" style="width: 90%;" />
                    
                    <p class="info" style="margin-top: 3px;">By default is: 
                    	<span style="font-family: Tahoma, Geneva, sans-serif; font-size: 13px; color: #727272;"><?php echo mg_wpmu_upload_dir(); ?></span>
                    </p>
                </td>
              </tr> 
            </table> 
        <?php endif; ?>    
        
        <h3><?php _e("Various", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Preview container", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select name="mg_preview_pag" class="lcweb-chosen" data-placeholder="<?php _e("Select a page", 'mg_ml'); ?> .." tabindex="2">
                  <option value=""></option>
                  <?php
                  foreach(get_pages() as $pag) {
                      ($fdata['mg_preview_pag'] == $pag->ID) ? $selected = 'selected="selected"' : $selected = '';
                      echo '<option value="'.$pag->ID.'" '.$selected.'>'.$pag->post_title.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the page to use as preview container", 'mg_ml'); ?></span></td>
          </tr>
        </table>  
        
        <h3><?php _e("Advanced", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable deeplinking?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_disable_dl'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_disable_dl" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, disable the deeplinking for lightbox and category filter', 'mg_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use custom CSS inline?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_force_inline_css'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_force_inline_css" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, uses custom CSS inline (useful for multisite installations)', 'mg_ml') ?></span>
            </td>
          </tr>
          <tr <?php if($fdata['mg_use_timthumb']) {echo 'style="display: none;"';} ?>>
            <td class="lcwp_label_td"><?php _e("Use Easy WP Thumbs forcing system?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_ewpt_force'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_ewpt_force" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Try forcing thumbnails creation, check it ONLY if you note missing thumbnails', 'mg_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use TimThumb?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_use_timthumb'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_use_timthumb" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, use Timthumb instead of Easy WP Thumbs', 'mg_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Use javascript in the head?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_js_head'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_js_head" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Put javascript in the website head, check it ONLY IF you notice some incompatibilities', 'mg_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Enable AJAX support?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_enable_ajax'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_enable_ajax" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Enable the support for AJAX-loaded grids', 'mg_ml') ?></span>
            </td>
          </tr>
        </table>

        
        <?php if(!get_option('mg_use_timthumb')) {ewpt_wpf_form();} ?>
    </div>

	<div id="color_opt">
    	<h3><?php _e("Grid Items", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Loader Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_loader_color']; ?>;"></span>
                	<input type="text" name="mg_loader_color" value="<?php echo $fdata['mg_loader_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Loading animation color', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Cells Outer Border Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_cells_border_color']; ?>;"></span>
                	<input type="text" name="mg_cells_border_color" value="<?php echo $fdata['mg_cells_border_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('The cells outer border color', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo mg_rgb2hex($fdata['mg_img_border_color']); ?>;"></span>
                	<input type="text" name="mg_img_border_color" value="<?php echo mg_rgb2hex($fdata['mg_img_border_color']); ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('The cells image border color', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Opacity", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_img_border_opacity']; ?>" name="mg_img_border_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Set the CSS3 image border opacity', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_main_overlay_color']; ?>;"></span>
                	<input type="text" name="mg_main_overlay_color" value="<?php echo $fdata['mg_main_overlay_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Color of the main overlay that appears on item mouseover', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Opacity", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_main_overlay_opacity']; ?>" name="mg_main_overlay_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Opacity of the main overlay that appears on item mouseover', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_second_overlay_color']; ?>;"></span>
                	<input type="text" name="mg_second_overlay_color" value="<?php echo $fdata['mg_second_overlay_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Color of the secondary overlay that appears on item mouseover', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Icons Color", 'mg_ml'); ?></td>
           	<td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_icons_col']; ?>;"></span>
                	<input type="text" name="mg_icons_col" value="<?php echo $fdata['mg_icons_col']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Color of the icons in the secondary overlay', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay Title Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_overlay_title_color']; ?>;"></span>
                	<input type="text" name="mg_overlay_title_color" value="<?php echo $fdata['mg_overlay_title_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Color of the item title that appear on the main overlay', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text under images color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_txt_under_color']; ?>;"></span>
                	<input type="text" name="mg_txt_under_color" value="<?php echo $fdata['mg_txt_under_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Text color for "titles under items" mode', 'mg_ml') ?></span></td>
          </tr> 
        </table> 

		<h3><?php _e("Item Filters", 'mg_ml'); ?></h3>
		<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_txt_color']; ?>;"></span>
                	<input type="text" name="mg_filters_txt_color" value="<?php echo $fdata['mg_filters_txt_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters text color - default status', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_bg_color']; ?>;"></span>
                	<input type="text" name="mg_filters_bg_color" value="<?php echo $fdata['mg_filters_bg_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters background color - default status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_border_color']; ?>;"></span>
                	<input type="text" name="mg_filters_border_color" value="<?php echo $fdata['mg_filters_border_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters border color - default status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color (on mouse hover)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_txt_color_h']; ?>;"></span>
                	<input type="text" name="mg_filters_txt_color_h" value="<?php echo $fdata['mg_filters_txt_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters text color - mouse hover status', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color (on mouse hover)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_bg_color_h']; ?>;"></span>
                	<input type="text" name="mg_filters_bg_color_h" value="<?php echo $fdata['mg_filters_bg_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters background color - mouse hover status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color (on mouse hover)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_border_color_h']; ?>;"></span>
                	<input type="text" name="mg_filters_border_color_h" value="<?php echo $fdata['mg_filters_border_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters border color - mouse hover status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color (selected filter)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_txt_color_sel']; ?>;"></span>
                	<input type="text" name="mg_filters_txt_color_sel" value="<?php echo $fdata['mg_filters_txt_color_sel']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters text color - selected status', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color (selected filter)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_bg_color_sel']; ?>;"></span>
                	<input type="text" name="mg_filters_bg_color_sel" value="<?php echo $fdata['mg_filters_bg_color_sel']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters background color - selected status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color (selected filter)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">   
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_filters_border_color_sel']; ?>;"></span>
                	<input type="text" name="mg_filters_border_color_sel" value="<?php echo $fdata['mg_filters_border_color_sel']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters border color - selected status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?> - <?php _e('accept also "transparent" value', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_filters_radius']; ?>" name="mg_filters_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the border radius for each filter', 'mg_ml') ?> (<?php _e('not for old style', 'mg_ml') ?>)</span></td>
          </tr>
        </table>  
        
       	<h3><?php _e("Lightbox", 'mg_ml'); ?></h3>
		<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
				<div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_item_overlay_color']; ?>;"></span>
                	<input type="text" name="mg_item_overlay_color" value="<?php echo $fdata['mg_item_overlay_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Fullscreen lightbox overlay color', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay Opacity", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_item_overlay_opacity']; ?>" name="mg_item_overlay_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Fullscreen lightbox overlay opacity', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay Pattern", 'mg_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<input type="hidden" value="<?php echo $fdata['mg_item_overlay_pattern']; ?>" name="mg_item_overlay_pattern" id="mg_item_overlay_pattern" />
            
            	<div class="mg_setting_pattern <?php if(!$fdata['mg_item_overlay_pattern'] || $fdata['mg_item_overlay_pattern'] == 'none') {echo 'mg_pattern_sel';} ?>" id="mgp_none"> no pattern </div>
                
                <?php 
				foreach(mg_patterns_list() as $pattern) {
					($fdata['mg_item_overlay_pattern'] == $pattern) ? $sel = 'mg_pattern_sel' : $sel = '';  
					echo '<div class="mg_setting_pattern '.$sel.'" id="mgp_'.$pattern.'" style="background: url('.MG_URL.'/img/patterns/'.$pattern.') repeat top left transparent;"></div>';		
				}
				?>
            </td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Background color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_item_bg_color']; ?>;"></span>
                	<input type="text" name="mg_item_bg_color" value="<?php echo $fdata['mg_item_bg_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Lightbox background color (default: #FFFFFF)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_item_border_color']; ?>;"></span>
                	<input type="text" name="mg_item_border_color" value="<?php echo $fdata['mg_item_border_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Lightbox border color (default: #E5E5E5)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_item_txt_color']; ?>;"></span>
                	<input type="text" name="mg_item_txt_color" value="<?php echo $fdata['mg_item_txt_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Lightbox main text color (default: #222222)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Icons color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['mg_item_icons_color']; ?>;"></span>
                	<input type="text" name="mg_item_icons_color" value="<?php echo $fdata['mg_item_icons_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Color for lightbox commands, loader and social icons (default: #333333)', 'mg_ml') ?></span></td>
          </tr>       
        </table>  
    </div>
    
    <div id="opt_builder">
    <?php 
	// WPML sync button
	if(function_exists('icl_register_string')) {
		echo '
		<p id="mg_wpml_opt_sync_wrap">
			<input type="button" value="'. __('Sync with WPML', 'mg_ml').'" class="button-secondary" />
			<span><em>'. __('Save the options before sync', 'mg_ml') .'</em></span>
		</p>';	
	}
	
	if($wooc_active) {unset($types['woocom']);}
	foreach($types as $type => $name) :
	?>
		<h3 style="border: none;">
			<?php echo $name.' '.__('Attributes', 'mg_ml') ?>
            <a id="opt_<?php echo $type; ?>" class="add_option add-opt-h3"><?php _e('Add option', 'mg_ml') ?></a>
        </h3>
        <table class="widefat lcwp_table" id="<?php echo $type; ?>_opt_table" style="width: 100%; max-width: 450px;">
          <thead>
          <tr>
          	<th style="width: 30px;"><?php _e('Icon', 'mg_ml') ?></th>
            <th><?php _e('Attribute Name', 'mg_ml') ?></th>
            <th></th>
          	<th style="width: 20px;"></th>
            <th style="width: 20px;"></th>
          </tr>
          </thead>
          <tbody>
          	<?php
			if(is_array($fdata['mg_'.$type.'_opt'])) {
				$a = 0;
				foreach($fdata['mg_'.$type.'_opt'] as $type_opt) {
					$icon = (isset($fdata['mg_'.$type.'_opt_icon'][$a])) ? $fdata['mg_'.$type.'_opt_icon'][$a] : '';
					
					echo '
					<tr>
						<td class="mg_type_opt_icon_trigger">
							<i class="fa '.mg_sanitize_input($icon).'" title="set option icon"></i>
							<input type="hidden" name="mg_'.$type.'_opt_icon[]" value="'.mg_sanitize_input($icon).'" />
						</td>
						<td class="lcwp_field_td">
							<input type="text" name="mg_'.$type.'_opt[]" value="'.mg_sanitize_input($type_opt).'" maxlenght="150" />
						</td>
						<td></td>
						<td><span class="lcwp_move_row"></span></td>
						<td><span class="lcwp_del_row"></span></td>
					</tr>
					';	
					
					$a++;
				}
			}
			?>
          </tbody>
        </table>
	<?php endforeach; ?>
    
    <?php 
	// WOOCOMMERCE ATTRIBUTES
	if($wooc_active) :
		$type = 'woocom';
	?>
    	<h3 style="border: none;"><?php echo __('WooCommerce Product', 'mg_ml').' '.__('Attributes', 'mg_ml') ?></h3>
        <table class="widefat lcwp_table" id="woocom_opt_table" style="width: 100%; max-width: 450px;">
          <thead>
          <tr>
          	<th style="width: 30px;"><?php _e('Icon', 'mg_ml') ?></th>
            <th><?php _e('Attribute Name', 'mg_ml') ?></th>
          </tr>
          </thead>
          <tbody>
            <?php
			if(is_array($wc_attr)) {
				foreach($wc_attr as $attr) {
					$icon = (isset($fdata['mg_wc_attr_'.sanitize_title($attr->attribute_label).'_icon'])) ? $fdata['mg_wc_attr_'.sanitize_title($attr->attribute_label).'_icon'] : '';
					
					echo '
					<tr>
						<td class="mg_type_opt_icon_trigger">
							<i class="fa '.mg_sanitize_input($icon).'" title="set option icon"></i>
							<input type="hidden" name="mg_wc_attr_'.sanitize_title($attr->attribute_label).'_icon" value="'.mg_sanitize_input($icon).'" />
						</td>
						<td class="lcwp_field_td">
							'. $attr->attribute_label .'
						</td>
					</tr>';	
				}
			}
			?>
          </tbody>
        </table>
    <?php endif; ?>
    
    </div>
    
    <div id="advanced">    
        <h3><?php _e("Custom CSS", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_field_td">
            	<textarea name="mg_custom_css" style="width: 100%" rows="18"><?php echo $fdata['mg_custom_css']; ?></textarea>
            </td>
          </tr>
        </table>
        
        <h3><?php _e("Elements Legend", 'mg_ml'); ?></h3> 
        <table class="widefat lcwp_table">  
          <tr>
            <td class="lcwp_label_td">.mg_filter</td>
            <td><span class="info">Grid filter container (each filter is a <xmp><a></xmp> element, each separator is a <xmp><span></xmp> element)</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_grid_wrap</td>
            <td><span class="info">Grid container</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_box</td>
            <td><span class="info">Single item box</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_overlay_tit</td>
            <td><span class="info">Main overlay title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_title_under</td>
            <td><span class="info">Title under item</span></td>
          </tr>
          		
          <tr>
            <td class="lcwp_label_td">#mg_full_overlay_wrap</td>
            <td><span class="info">Lightbox - Full page overlay</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_item_load</td>
            <td><span class="info">Lightbox - Item loader during the opening</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">#mg_overlay_content</td>
            <td><span class="info">Lightbox - Item body</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">#mg_close</td>
            <td><span class="info">Lightbox - Close item command</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">#mg_nav</td>
            <td><span class="info">Lightbox - Item navigator container</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_item_title</td>
            <td><span class="info">Lightbox - Item title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_item_text</td>
            <td><span class="info">Lightbox - Item text</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_cust_options</td>
            <td><span class="info">Lightbox - Item options container (each option is a <xmp><li></xmp> element)</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_socials</td>
            <td><span class="info">Lightbox - Item socials container (each social is a <xmp><li></xmp> element)</span></td>
          </tr>
          
        </table> 
    </div> 
   
   	<input type="hidden" name="pg_nonce" value="<?php echo wp_create_nonce(__FILE__) ?>" /> 
    <input type="submit" name="lcwp_admin_submit" value="<?php _e('Update Options', 'mg_ml' ) ?>" class="button-primary" />  
    
	</form>
    </div>
</div>  

<?php // ICONS LIST CODE ?>
<div id="mg_icons_list" style="display: none;">
	<div class="mg_lb_icons_wizard">
		<?php 
        include_once(MG_DIR . '/classes/font-awesome-list.php');
        $fa = new Smk_FontAwesome;
        $icons = $fa->getArray(MG_DIR . '/css/font-awesome/css/font-awesome.css');
        $names = $fa->readableName($icons);
       	
		echo '<p rel="" class="mgtoi_no_icon"><a>'. __('no icon', 'mg_ml') .'</a></p>';
		
		foreach($icons as $id => $code) {
			echo '<i rel="'.$id.'" class="fa '.$id.'" title="'.$names[$id].'"></i>';	
		}
        ?>
	</div>
</div>



<?php // SCRIPTS ?>
<script src="<?php echo MG_URL; ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/colpick/js/colpick.min.js" type="text/javascript"></script>

<script type="text/javascript" charset="utf8" >
jQuery(document).ready(function($) {
	<?php 
	// WPML sync button
	if(function_exists('icl_register_string')) :
	?>
	jQuery('body').delegate('#mg_wpml_opt_sync_wrap input', 'click', function() {
		jQuery('#mg_wpml_opt_sync_wrap span').html('<div style="width: 30px;" class="lcwp_loading"></div>');
		
		var data = {action: 'mg_options_wpml_sync'};
		jQuery.post(ajaxurl, data, function(response) {
			var resp = jQuery.trim(response);
			
			if(resp == 'success') {jQuery('#mg_wpml_opt_sync_wrap span').html('<?php _e('Options synced succesfully', 'mg_ml'); ?>!');}
			else {jQuery('#mg_wpml_opt_sync_wrap span').html('<?php _e('Error syncing', 'mg_ml'); ?> ..');}
		});	
	});
	<?php endif; ?>
	
	
	// set a predefined style 
	jQuery('body').delegate('#mg_set_style', 'click', function() {
		var sel_style = jQuery('#mg_pred_styles').val();
		
		if(confirm('<?php _e('It will overwrite your current settings, continue?', 'mg_ml') ?>') && sel_style != '') {
			var data = {
				action: 'mg_set_predefined_style',
				style: sel_style,
				lcwp_nonce: '<?php echo wp_create_nonce('lcwp_nonce') ?>'
			};
			
			jQuery(this).parent().html('<div style="width: 30px; height: 30px;" class="lcwp_loading"></div>');
			
			jQuery.post(ajaxurl, data, function(response) {
				window.location.href = location.href;
			});	
		}
	});
	
	
	// predefined style  preview toggle
	jQuery('body').delegate('#mg_pred_styles', "change", function() {
		var sel = jQuery('#mg_pred_styles').val();
		
		jQuery('.mg_styles_preview').hide();
		jQuery('.mg_styles_preview').each(function() {
			if( jQuery(this).attr('alt') == sel ) {jQuery(this).fadeIn();}
		});
	});
	
	
	// select a pattern
	jQuery('body').delegate('.mg_setting_pattern', 'click', function() {
		var pattern = jQuery(this).attr('id').substr(4);
		
		jQuery('.mg_setting_pattern').removeClass('mg_pattern_sel');
		jQuery(this).addClass('mg_pattern_sel'); 
		
		jQuery('#mg_item_overlay_pattern').val(pattern);
	});
	
	///////////////////////////////////////////////////////
	
	// launch option icon wizard
	var $sel_type_opt = false;
	jQuery('body').delegate('.mg_type_opt_icon_trigger i', "click", function() {
		$sel_type_opt = jQuery(this).parent();
		
		tb_show('Type option - choose an icon' , '#TB_inline?inlineId=mg_icons_list');
		setTimeout(function() {
			jQuery('#TB_ajaxContent').css('width', 'auto');
			jQuery('#TB_ajaxContent').css('height', (jQuery('#TB_window').height() - 47) );
		}, 50);
	});
	jQuery(window).resize(function() {
		if( jQuery('#TB_ajaxContent .mg_lb_icons_wizard').size() > 0 ) {
			jQuery('#TB_ajaxContent').css('height', (jQuery('#TB_window').height() - 47) );	
		}
	});
	
	
	// select icon
	jQuery('body').delegate('#TB_ajaxContent .mg_lb_icons_wizard > *', "click", function() {
		var val = jQuery(this).attr('rel');
		
		$sel_type_opt.find('input').val(val);
		$sel_type_opt.find('i').attr('class', 'fa '+val);
		
		tb_remove();
		$sel_type_opt = false;
	});
	
	
	// add options
	jQuery('.add_option').click(function(){
		var type_subj = jQuery(this).attr('id').substr(4);
		
		var optblock = '<tr>\
			<td class="mg_type_opt_icon_trigger">\
				<i class="fa" title="set option icon"></i>\
				<input type="hidden" name="mg_'+type_subj+'_opt_icon[]" value="" />\
			</td>\
			<td class="lcwp_field_td"><input type="text" name="mg_'+type_subj+'_opt[]" maxlenght="150" /></td>\
			<td></td>\
		    <td><span class="lcwp_move_row"></span></td>\
			<td><span class="lcwp_del_row"></span></td>\
		</tr>';

		jQuery('#'+type_subj + '_opt_table tbody').append(optblock);
	});
	
	// remove opt 
	jQuery('body').delegate('.lcwp_del_row', "click", function() {
		if(confirm('<?php _e('Delete the option', 'mg_ml') ?>?')) {
			jQuery(this).parent().parent().slideUp(function() {
				jQuery(this).remove();
			});	
		}
	});
	
	// sort opt
	jQuery('#opt_builder table').each(function() {
        jQuery(this).children('tbody').sortable({ handle: '.lcwp_move_row' });
		jQuery(this).find('.lcwp_move_row').disableSelection();
    });

	
	// tabs
	jQuery("#tabs").tabs();
});
</script>
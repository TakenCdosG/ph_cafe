<?php
// SHORCODE DISPLAYING THE GRID

// [mediagrid] 
function mg_shortcode( $atts, $content = null ) {
	include_once(MG_DIR . '/functions.php');
	include_once(MG_DIR . '/classes/overlay_manager.php');
	
	extract( shortcode_atts( array(
		'cat' => '',
		'filter' => 0,
		'r_width' => 'auto',
		'title_under' => 0,
		'hide_all' => 0,
		'def_filter' => 0,
		'overlay'	=> 'default'
	), $atts ) );

	if($cat == '') {return '';}
	
	// deeplinking class
	$dl_class = (get_option('mg_disable_dl')) ? '' : 'mg_deeplink'; 
	
	// init
	$grid = '';
	
	// filter
	if($filter) {
		$filter_code = mg_grid_terms_data($cat, 'html', $def_filter, $hide_all);
		
		if($filter_code) {
			$filter_type = (get_option('mg_use_old_filters')) ? 'mg_old_filters' : 'mg_new_filters';
			$grid .= '<div id="mgf_'.$cat.'" class="mg_filter '.$filter_type.'">'. $filter_code .'</div>';
		}
		
		// mobile dropdown 
		if(get_option('mg_dd_mobile_filter')) {
			$filter_code = mg_grid_terms_data($cat, 'dropdown', $def_filter, $hide_all);
			
			if($filter_code) {
				$grid .= '<div id="mgmf_'.$cat.'" class="mg_mobile_filter">'. $filter_code .'<i></i></div>';
			}
		}
	}
	
	// title under - wrap class
	$tit_under_class = ($title_under == 1) ? 'mg_grid_title_under' : '';
	
	// image overlay code 
	$ol_man = new mg_overlay_manager($overlay, $title_under);
	
	$grid .= '
	<div class="mg_grid_wrap '.$dl_class.'">
	  '.mg_preloader().'
      <div id="mg_grid_'.$cat.'" class="mg_container '.$tit_under_class.' '.$ol_man->txt_vis_class.'" rel="'.$r_width.'" '.$ol_man->img_fx_attr.'>';
	
	/////////////////////////
	// grid contents
		
	$term = get_term_by('id', $cat, 'mg_grids');
	$grid_data = (empty($term->description)) ? array('items' => array(), 'cats' => array()) : unserialize($term->description); 
	if(count($grid_data['items']) == 0) {return '';}
	
	$max_width = get_option('mg_maxwidth', 1200);
	$mobile_treshold = get_option('mg_mobile_treshold', 800);
	$thumb_q = get_option('mg_thumb_q', 85);

	foreach($grid_data['items'] as $item) {
		$post_id = $item['id'];
		
		// check post status
		if(get_post_status($post_id) != 'publish') {continue;}
		
		// WPML - check translation
		if(function_exists('icl_object_id')) {
			$post_id = icl_object_id($post_id, 'mg_items', true);	
		}
		
		$main_type = (get_post_type($post_id) == 'product') ? 'woocom' : get_post_meta($post_id, 'mg_main_type', true);
		$item_layout = get_post_meta($post_id, 'mg_layout', true);

		// image-based operations
		if($main_type != 'spacer') {
			// thumbs image size
			$thb_w = ceil($max_width * mg_size_to_perc($item['w']));
			$thb_h = ceil($max_width * mg_size_to_perc($item['h']));
			
			if(!isset($item['m_w'])) {
				$item['m_w'] = $item['w'];
				$item['m_h'] = $item['h'];
			}
			$m_thb_w = ceil($mobile_treshold * mg_size_to_perc($item['m_w']));
			$m_thb_h = ceil($mobile_treshold * mg_size_to_perc($item['m_h']));
			
			
			if(!in_array($main_type, array('inl_slider', 'inl_text'))) {
				// thumb url and center
				$img_id = get_post_thumbnail_id($post_id);
				$thumb_center = (get_post_meta($post_id, 'mg_thumb_center', true)) ? get_post_meta($post_id, 'mg_thumb_center', true) : 'c'; 
				
				if($img_id) {
					// main thumb
					if($item['h'] != 'auto') {
						$thumb_url = mg_thumb_src($img_id, $thb_w, $thb_h, $thumb_q, $thumb_center);
					} else {
						$thumb_url = mg_thumb_src($img_id, $thb_w, false, $thumb_q, $thumb_center);
					}
					
					// mobile thumb
					if($item['m_h'] != 'auto') {
						$mobile_url = mg_thumb_src($img_id, $m_thb_w, $m_thb_h, $thumb_q, $thumb_center);
					} else {
						$mobile_url = mg_thumb_src($img_id, $m_thb_w, false, $thumb_q, $thumb_center);
					}
				}
				else {
					$thumb_url = '';
					$mobile_url = '';	
				}
			}
			
			
			// item title
			$item_title = get_the_title($post_id);
			
			// image ALT attribute
			$img_alt = strip_tags( mg_sanitize_input($item_title) );
			
			// title under switch
			if($title_under == 1) {
				$img_ol = '<div class="overlays">' . $ol_man->get_img_ol($post_id) . '</div>';
				$txt_under = $ol_man->get_txt_under($post_id);
			} 
			else {
				$img_ol = '<div class="overlays">' . $ol_man->get_img_ol($post_id) . '</div>';
				$txt_under = '';
			}
			
			// image proportions for the "auto" height
			if(($item['h'] == 'auto' || $item['m_h'] == 'auto') && $main_type != 'inl_text') {
				$img_info = wp_get_attachment_image_src($img_id, 'full');
				$ratio_val = (float)$img_info[2] / (float)$img_info[1];
				$ratio = 'ratio="'.$ratio_val.'"';
			} else {
				$ratio = '';	
			}
		}
		
		
		////////////////////////////
		/*** item types ***/
		
		// type class
		switch($main_type) {
			case 'single_img'	: $type_class = 'mg_image'; break;	
			case 'img_gallery'	: $type_class = 'mg_gallery'; break;	
			case 'simple_img'	: $type_class = 'mg_static_img'; break;
			default 			: $type_class = 'mg_' . $main_type; break;	 
		}
		
		// transitions class
		$trans_class = (!in_array($main_type, array('inl_slider','inl_video','inl_text','spacer'))) ? 'mg_transitions' : '';
		// lightbox trigger class
		$lb_class = (!in_array($main_type, array('simple_img','link','inl_slider','inl_video','inl_text','spacer'))) ? 'mg_closed' : '';
		// no overlay class fot static items
		$static_img_no_ol = ($type_class != 'mg_static_img' || ($type_class == 'mg_static_img' && get_post_meta($post_id, 'mg_static_show_overlay', true))) ? '' : 'mg_static_no_ol';
		
		// classes variable
		$add_classes = 'mgi_'.$post_id.' '.$type_class.' '.$trans_class.' '.$lb_class.' '.$static_img_no_ol.' '.mg_item_terms_classes($post_id);
		
		
		////////////////////////////
		/*** items custom css ***/
		
		// inline texts custom colors
		if($main_type == 'inl_text') {
			$img_wrap_css = 'style="';
			
			if(get_post_meta($post_id, 'mg_inl_txt_color', true)) {$img_wrap_css .= 'color: '.get_post_meta($post_id, 'mg_inl_txt_color', true).';';}
			if(get_post_meta($post_id, 'mg_inl_txt_box_bg', true)) {$img_wrap_css .= 'background-color: '.get_post_meta($post_id, 'mg_inl_txt_box_bg', true).';';}
			if((int)get_post_meta($post_id, 'mg_inl_txt_bg_alpha', true)) {
				$alpha = (int)get_post_meta($post_id, 'mg_inl_txt_bg_alpha', true) / 100; 
				$img_wrap_css .= 'background-color: '.mg_hex2rgba( get_post_meta($post_id, 'mg_inl_txt_box_bg', true), $alpha).';';
			}
			
			$img_wrap_css .= '"';
		}
		else {$img_wrap_css = '';}
		
		
		
		/*** item block ***/
		// first part
		$grid .= '
		<div id="'.uniqid().'" class="mg_box mg_pre_show col'.$item['w'].' row'.$item['h'].' m_col'.$item['m_w'].' m_row'.$item['m_h'].' '.$add_classes.'" rel="pid_'.$post_id.'" '.$ratio.'
			mgi_w="'.mg_size_to_perc($item['w'], 1).'" mgi_h="'.mg_size_to_perc($item['h'], 1).'" mgi_mw="'.mg_size_to_perc($item['m_w'], 1).'" mgi_mh="'.mg_size_to_perc($item['m_h'], 1).'">';
		
			if($main_type != 'spacer') {
				$grid .= '
				<div class="mg_shadow_div">
					<div class="img_wrap" '.$img_wrap_css.'>
						<div>';
						
						// link type - start tag
						if($main_type == 'link') {
							$nofollow = (get_post_meta($post_id, 'mg_link_nofollow', true) == '1') ? 'rel="nofollow"' : '';
							$grid .= '<a href="'.get_post_meta($post_id, 'mg_link_url', true).'" target="_'.get_post_meta($post_id, 'mg_link_target', true).'" '.$nofollow.' class="mg_link_elem">';
						}

							/*** inner contents for lightbox types ***/
							// inline slider
							if($main_type == 'inl_slider') {
								$slider_img = get_post_meta($post_id, 'mg_slider_img', true);
								$autoplay = (get_post_meta($post_id, 'mg_inl_slider_autoplay', true)) ? 'mg_autoplay_slider' : '';
								$captions = get_post_meta($post_id, 'mg_inl_slider_captions', true);

								$grid .= '
								<div id="'.uniqid().'" class="mg_inl_slider_wrap '.$autoplay.'">';
								  
								if(is_array($slider_img)) {
									if(get_post_meta($post_id, 'mg_inl_slider_random', true)) {
										shuffle($slider_img);	
									}
									
									$seo_slider = '<noscript>'; 
									foreach($slider_img as $img_id) {
										
										// WPML integration - get translated ID
										if(function_exists('icl_object_id')) {
											$img_id = icl_object_id($img_id, 'attachment', true);	
										}
										
										$src = wp_get_attachment_image_src($img_id, 'full');
										
										// resize if is not an animated gif
										if(substr(strtolower($src[0]), -4) != '.gif' && substr(strtolower($src[0]), -4) != '.png') {
											$sizes = mg_inl_slider_img_sizes($src, $max_width, $item);
											$slider_thumb = mg_thumb_src($img_id, $sizes['w'], $sizes['h'], $thumb_q);
										}
										else {$slider_thumb = $src[0];}
										
										if($captions == 1) {
										   $img_data = get_post($img_id);
										   $caption = trim($img_data->post_content);
										   $caption_code = ($caption == '') ? '' : $caption;
										}
										else {$caption_code = '';}

										$grid .= '<img src="" lazy-slider-img="'.$slider_thumb.'" data-description="'.mg_sanitize_input($caption_code).'">';
										$seo_slider .= '<img src="'.$slider_thumb.'" alt="'.mg_sanitize_input($caption_code).'">';
									}
								}
						
								// slider wrap closing + seo slider
								$grid .= '</div>' . $seo_slider . '</noscript>'; 
							}
							
							// inline video
							if($main_type == 'inl_video') {
								$poster = (get_post_meta($post_id, 'mg_video_use_poster', true) == 1 && $thumb_url) ? true : false;
								$autoplay = (empty($poster)) ? '' : true;
								
								$visibility = ($poster) ? 'style="display: none;"' : '';
								$video_url = lcwp_video_embed_url(get_post_meta($post_id, 'mg_video_url', true), $autoplay);
								$url_to_use = ($poster && $autoplay) ? lcwp_video_embed_url(get_post_meta($post_id, 'mg_video_url', true), false) : $video_url; // fix for chrome autoplay
								$grid .= '<iframe class="mg_video_iframe" width="100%" height="100%" src="'.$url_to_use.'" frameborder="0" allowfullscreen '.$visibility.'></iframe>';	
							}
							
							// inline text
							if($main_type == 'inl_text') {
								$grid .= '<table class="mg_inl_txt_table"><tbody><tr>
									<td class="mg_inl_txt_td" style="vertical-align: '.get_post_meta($post_id, 'mg_inl_txt_vert_align', true).';">
										'. do_shortcode(wpautop(get_post_field('post_content', $post_id))) .'
									</td>
								</tr></tbody></table>';	
							}
							
							// standard lightbox types and inline video with poster
							if(!in_array($main_type, array('inl_slider', 'inl_video', 'inl_text')) || ($main_type == 'inl_video' && $poster)) {
								
								// video poster attribute if autoplay
								$poster_attr = ($main_type == 'inl_video' && $autoplay) ? 'autoplay-url="'.$video_url.'"' : '';
								
								
								$grid .= '
								<img src="" class="thumb" alt="'.$img_alt.'" fullurl="'.$thumb_url.'" mobileurl="'.$mobile_url.'" '.$poster_attr.' />
								<noscript>
									<img src="'.$thumb_url.'" alt="'.$img_alt.'" '.$poster_attr.' />
								</noscript>';
								
								// overlays
								if($main_type != 'simple_img' || ($main_type == 'simple_img' && empty($static_img_no_ol))) {
									$grid .= $img_ol;
								}
							} 
							
							// SEO deeplink trick
							if(!empty($dl_class) && !in_array($main_type, array('simple_img','inl_slider','inl_video','inl_text','link')) ) {
								$grid .= '<a href="'.lcwp_curr_url().'#!mg_ld_'.$post_id.'" class="mg_seo_dl_link">\'</a>';
							}

						
						// link type - end tag	
						if($main_type == 'link') {	
							$grid .= '</a>'; 
						}

					$grid .= '
						</div>
					</div>';
					
					// overlays under
					if(($main_type != 'inl_text' && $main_type != 'simple_img') || ($main_type == 'simple_img' && empty($static_img_no_ol))) {
						$grid .= $txt_under;
					}
			
				$grid .= '</div>';		
			}
			
		// close main div
		$grid .= '</div>';	
		
	} // end foreach and close grid
	$grid .= '</div></div>';
	
	
	//////////////////////////////////////////////////
	// OVERLAY MANAGER ADD-ON
	if(defined('MGOM_URL')) {
		$grid .= '
		<script type="text/javascript">
		jQuery(document).ready(function($) { 
			if(typeof(mgom_hub) == "function" ) {
				mgom_hub('.$cat.');
			}
		});
		</script>
		';	
	}
	//////////////////////////////////////////////////
	
	
	// Ajax init
	if(get_option('mg_enable_ajax')) {
		$grid .= '
		<script type="text/javascript">
		jQuery(document).ready(function($) { 
			if(typeof(mg_ajax_init) == "function" ) {
				mg_ajax_init('.$cat.');
			}
		});
		</script>
		';
	}
	
	return str_replace(array("\r", "\n", "\t", "\v"), '', $grid);
}
add_shortcode('mediagrid', 'mg_shortcode');

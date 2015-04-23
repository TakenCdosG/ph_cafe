<?php
// ajax lightbox trigger
function mg_ajax_lightbox() {
	if(isset($_POST['mg_lb']) && $_POST['mg_lb'] == 'mg_lb_content') {
		include_once(MG_DIR . '/functions.php');
		header('Content-Type: text/html; charset=utf-8');
	
		if(!isset($_POST['pid']) || !filter_var($_POST['pid'], FILTER_VALIDATE_INT)) {die('item id is missing');}
		$pid = addslashes($_POST['pid']);
		
		$prev = (isset($_POST['prev_id'])) ? (int)$_POST['prev_id'] : false;
		$next = (isset($_POST['next_id'])) ? (int)$_POST['next_id'] : false;
		mg_lightbox($pid, $prev, $next);
		die();
	}
}
add_action('init', 'mg_ajax_lightbox', 999);



// lightbox code
function mg_lightbox($post_id, $prev_item = false, $next_item = false) {
	include_once(MG_DIR . '/functions.php');

	$post_data = get_post($post_id);
	$GLOBALS['post'] = $post_data; 

	// post type and layout
	if($post_data->post_type == 'product') {
		// simulate standard type and add flag	
		$wc_prod = new WC_Product($post_id);
		$wc_gallery = $wc_prod->get_gallery_attachment_ids();
		
		$type = (is_array($wc_gallery) && count($wc_gallery) > 0) ? 'img_gallery' : 'single_img';
	}
	else {
		$type = get_post_meta($post_id, 'mg_main_type', true);
		$wc_prod = false;
	}
	
	// layout
	$layout = get_post_meta($post_id, 'mg_layout', true);
	$touchswipe = (get_option('mg_lb_touchswipe')) ? 'mg_touchswipe' : '';
	$img_max_h = (int)get_post_meta($post_id, 'mg_img_maxheight', true);
	$item_title = $post_data->post_title;
	
	// canvas color for TT
	$tt_canvas = substr(get_option('mg_item_bg_color', '#ffffff'), 1);
	
	// maxwidth control
	$lb_max_w = (int)get_option('mg_item_maxwidth', 960);
	if($lb_max_w == 0) {$lb_max_w = 960;}

	// Thumb center
	$tt_center = (get_post_meta($post_id, 'mg_thumb_center', true)) ? get_post_meta($post_id, 'mg_thumb_center', true) : 'c'; 
	
	// lightbox max width for the item
	$fc_max_w = (int)get_post_meta($post_id, 'mg_lb_max_w', true);
	if(!$fc_max_w || $fc_max_w < 280) {$fc_max_w = false;} 
	
	// item featured image for socials
	$fi_img_id = get_post_thumbnail_id($post_id);
	$fi_src = wp_get_attachment_image_src($fi_img_id, 'medium');
	$fi_src_pt = wp_get_attachment_image_src($fi_img_id, 'full'); // pinterest - use full one
	
	
	///////////////////////////
	// TYPES
	
	if($type == 'single_img') {
		$img_id = get_post_thumbnail_id($post_id);
		$featured = mg_preloader().'<img src="'. mg_lb_image_optimizer($img_id, $layout, $img_max_h, $tt_center, $resize = 3) .'" alt="'.mg_sanitize_input(strip_tags($item_title)).'" />';
	}
	
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif($type == 'img_gallery') {
		$slider_img = (isset($wc_gallery)) ? $wc_gallery : get_post_meta($post_id, 'mg_slider_img', true);
		$style = get_option('mg_slider_style', 'light');
		$unique_id = uniqid();
		$autoplay = (get_post_meta($post_id, 'mg_slider_autoplay', true)) ? 'true' : 'false';
		
		// slider height
		$def_h_val = get_option('mg_slider_main_w_val', 55);
		$def_h_type = get_option('mg_slider_main_w_type', '%');
		$h_val = get_post_meta($post_id, 'mg_slider_w_val', true);
		$h_type = get_post_meta($post_id, 'mg_slider_w_type', true);
		
		if(!$h_val) {$h_val =  $def_h_val;}
		if(!$h_type) {$h_type =  $def_h_type;}
		$height = $h_val.$h_type;
		
		// slider proportions parameter
		if(strpos($height, '%') !== false) {
			$val = (int)str_replace("%", "", $height) / 100;
			$proportions_param = 'asp-ratio="'.$val.'"';
			$proportions_class = "mg_galleria_responsive";
			
			$slider_h = '';
			$stage_max_h = $val;
		} 
		else {
			$proportions_param = '';	
			$proportions_class = "";
			
			$slider_h = 'height: '.$height.';';
			$stage_max_h = $h_val;
		}
		
		// images management
		$crop = get_post_meta($post_id, 'mg_slider_crop', true);
		if(!$crop) {$crop = 'true';}
		
		// slider thumbs visibility
		$thumbs_visibility = get_post_meta($post_id, 'mg_slider_thumbs', true);
		$thumbs_class = ($thumbs_visibility == 'yes' || $thumbs_visibility == 'always') ? 'mg_galleria_slider_show_thumbs' : '';
		
		// thumbs CSS code
		if($thumbs_visibility == 'always' || $thumbs_visibility == 'never') {
			$css_code = '.mg_galleria_slider_wrap .galleria-mg-toggle-thumb {display: none !important;}';	
		} else {
			$css_code = '';	
		}
		if(!$thumbs_visibility || $thumbs_visibility == 'no' || $thumbs_visibility == 'never') {
			$css_code .= '.mg_galleria_slider_wrap .galleria-thumbnails-container {opacity: 0; filter: alpha(opacity=0);}';	
		}
		
		
		$featured = '
		<style type="text/css">
			'.$css_code.'
			.mg_item_featured {max-height: 0px; overflow: hidden;}
		</style>
		
		<script type="text/javascript"> 
		mg_galleria_img_crop = "'.$crop.'";
		mg_slider_autoplay["#'.$unique_id.'"] = '.$autoplay.';
		</script>	
		
		<div id="'.$unique_id.'" 
			class="mg_galleria_slider_wrap mg_show_loader mg_galleria_slider_'.$style.' '.$thumbs_class.' '.$proportions_class.' mgs_'.$post_id.' noSwipe" 
			style="width: 100%; '.$slider_h.'" '.$proportions_param.'
		>';
		  
		if(is_array($slider_img)) {
			if(get_post_meta($post_id, 'mg_slider_random', true)) {
				shuffle($slider_img);	
			}
			
			// woocommerce - if prepend first image
			if(isset($wc_gallery) && get_post_meta($post_id, 'mg_slider_add_featured', true)) {
				array_unshift($slider_img, $fi_img_id);
			}
			
			// compose slider structure
			foreach($slider_img as $img_id) {
				
				// WPML integration - get translated ID
				if(function_exists('icl_object_id')) {
					$img_id = icl_object_id($img_id, 'attachment', true);	
				}
				
				if(get_post_meta($post_id, 'mg_slider_captions', true) == 1) {
					$img_data = get_post($img_id);
				   	$caption = trim(strip_tags(apply_filters('the_content', $img_data->post_content), 'br'));
				   
				   	($caption == '') ? $caption_code = '' :  $caption_code = $caption;
				}
				else {$caption_code = '';}
					 
				$img_url = mg_lb_image_optimizer($img_id, $layout, false, 'c', $resize = 3);
				$thumb = mg_thumb_src($img_id, 100, 69, $thumb_q = 85, 'c');	
					  
				$featured .= '
				<a href="'.$img_url.'">
					<img src="'.mg_sanitize_input($thumb).'" data-big="'.$img_url.'" data-description="'.mg_sanitize_input($caption_code).'" />
				</a>';
			  }
		  }

		  $featured .= '<div style="clear: both;"></div>
		  </div>'; // slider wrap closing
		  
		  // slider init
		  $featured .= '<script type="text/javascript"> 
		  jQuery(document).ready(function($) {
			  if(typeof(mg_galleria_init) == "function") { 
				  mg_galleria_show("#'.$unique_id.'");
				  
				  setTimeout(function() {
				  	mg_galleria_init("#'.$unique_id.'");
				  }, 150);
			  }
		  });
		  </script>';
	}
		
		
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////		
	elseif($type == 'video') {
		$src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
		$video_url = get_post_meta($post_id, 'mg_video_url', true);
		
		$video_w = ($layout == 'full') ?  960 : (960 * 0.675);
		$video_h = $video_w * 0.56;
		
		// poster
		if(get_post_meta($post_id, 'mg_video_use_poster', true) == 1) {
			$img_id = get_post_thumbnail_id($post_id);
			$poster = true;
			
			$poster_h = $lb_max_w * 0.56;
			$poster_img = mg_lb_image_optimizer($img_id, $layout, $poster_h, $tt_center, $resize = 1);
		}
		else {
			$poster = false;
			$poster_img = '';
		}
		
		if(lcwp_video_embed_url($video_url) == 'wrong_url') {
			$autoplay = (get_option('mg_video_autoplay') && !$poster) ? 'autoplay="on"' : '';
			
			// get video sources
			$ok_src = array();
			$allowed = array('mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv');
			$sources = explode(',', $video_url); 
				
			foreach($sources as $v_src) {
				$ext = substr(trim(lcwp_stringToExt($v_src)), 1);
				if(in_array($ext, $allowed)) {
					$ok_src[$ext] = trim($v_src);	
				}
			}
			
			$man_src = array();
			foreach($ok_src as $type => $url) {
				$man_src[] = $type.'="'.$url.'"';	
			}
			
			if(count($ok_src) == 0) {
				$featured = '<p><em>Video extension not supported ..</em></p>';	
			}
			else {
				$poster_attr = (!empty($poster_img)) ? 'poster="'.$poster_img.'"' : ''; 
				$featured = 
				'<div id="mg_wp_video_wrap" class="mg_self-hosted-video">'. 
					do_shortcode('[video '.implode(' ', $man_src).' '.$poster_attr.' '.$autoplay.' preload="auto"][/video]') .
				'</div>';
				
				$featured .= "
				</div>
				
				<link rel='stylesheet' id='mediaelement-css'  href='".includes_url()."js/mediaelement/mediaelementplayer.min.css?ver=2.13.0' type='text/css' media='all' />
				<link rel='stylesheet' id='wp-mediaelement-css'  href='".includes_url()."js/mediaelement/wp-mediaelement.css?ver=3.6' type='text/css' media='all' />
				
				<script type='text/javascript'>
				/* <![CDATA[ */
				var mejsL10n = {'language':'en-US','strings':{
					'Close':'Close',
					'Fullscreen': '". __('Fullscreen', 'mg_ml') ."',
					'Download File': '". __('Download File', 'mg_ml') ."',
					'Download Video': '". __('Download Video', 'mg_ml') ."',
					'Play\/Pause': '". __('Play\/Pause', 'mg_ml') ."',
					'Mute Toggle': '". __('Mute Toggle', 'mg_ml') ."',
					'None': '". __('None', 'mg_ml') ."',
					'Turn off Fullscreen': '". __('Turn off Fullscreen', 'mg_ml') ."',
					'Go Fullscreen': '". __('Go Fullscreen', 'mg_ml') ."',
					'Unmute': '". __('Unmute', 'mg_ml') ."',
					'Mute': '". __('Mute', 'mg_ml') ."',
					'Captions\/Subtitles': '". __('Captions\/Subtitles', 'mg_ml') ."'
				}};
				/* ]]> */
				</script>
				<script type='text/javascript' src='".includes_url()."js/mediaelement/mediaelement-and-player.min.js'></script>
				<script type='text/javascript'>
				/* <![CDATA[ */
				var _wpmejsSettings = {'pluginPath':'". str_replace('/', '\/', includes_url()) ."js\/mediaelement\/'};
				/* ]]> */
				</script>
				
				<script type='text/javascript' src='".includes_url()."js/mediaelement/wp-mediaelement.js'></script>";
			}
		} 
		else {
			if($poster) {
				$autop_url = lcwp_video_embed_url($video_url, true);
				$v_url =  lcwp_video_embed_url($video_url, false);
				
				$ifp = mg_preloader() . '<div id="mg_ifp_ol" class="fa fa-play" style="display: none;"></div>
					<img src="'. $poster_img .'" alt="'.mg_sanitize_input(strip_tags($item_title)).'" autoplay-url="'.$autop_url.'" style="display: none;" class="mg_lb_video_poster" />';
				$poster_bg = 'style="background-image: url('. $poster_img .')"';
				$vis = 'style="display: none;"';
			}
			else {
				$ifp = '';
				$autoplay = '';
				$vis = '';
				$poster_bg = '';
				$v_url = lcwp_video_embed_url($video_url);
			}
			
			$featured = '
			<div id="mg_lb_video_wrap" '.$poster_bg.'>
				'.$ifp.'
				<iframe class="mg_video_iframe" width="'.$video_w.'" height="'.$video_h.'" src="'. $v_url .'" frameborder="0" allowfullscreen '.$vis.'></iframe>
			</div>
			';
		}
	}
	
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif($type == 'audio') {
		// check for soundcloud embedding
		$soundcloud = get_post_meta($post_id, 'mg_soundcloud_url', true);
		if(!empty($soundcloud)) {
			$featured =  mg_get_soundcloud_embed($soundcloud);	
		}
		else {
			$img_id = get_post_thumbnail_id($post_id);
			$tracklist = get_post_meta($post_id, 'mg_audio_tracks', true);
			$tot = (is_array($tracklist)) ? count($tracklist) : 0;
			
			($tot == 1 || !get_option('mg_audio_tracklist')) ? $tl_class = 'jp_hide_tracklist' : $tl_class = 'jp_full_tracklist';
			
			// inlude jplayer script
			$script_name = 'mg_pack.js'; //(get_option('mg_old_js_mode')) ? 'mg_pack.js' : 'mg_pack_old_js.js';
			$featured = '<script type="text/javascript" src="'.MG_URL.'/js/jPlayer/'.$script_name.'"></script>';
			
			$featured .= mg_preloader() . '<img src="'. mg_lb_image_optimizer($img_id, $layout, $img_max_h, $tt_center, $resize = 3) .'" alt="'.mg_sanitize_input(strip_tags($item_title)).'" />';
			$featured .= '
			<div id="mg_audio_player_'.$post_id.'" class="jp-jplayer"></div>
		
			<div id="mg_audio_wrap_'.$post_id.'" class="jp-audio noSwipe" style="display: none;">
				<div class="jp-type-playlist">
					<div class="jp-gui jp-interface">
						<div class="jp-cmd-wrap">';
						
							if($tot > 1) {$featured .= '<a href="javascript:;" class="jp-previous">previous</a>';}
						
							$featured .= '
							<a href="javascript:;" class="jp-play">play</a>
							<a href="javascript:;" class="jp-pause">pause</a>';
							
							if($tot > 1) {$featured .= '<a href="javascript:;" class="jp-next">next</a>';}
	
							$featured .= '
							<div class="jp-time-holder">
								<div class="jp-current-time"></div> 
								<span>/</span> 
								<div class="jp-duration"></div>
							</div>
							
							<div class="jp-progress">
								<div class="jp-seek-bar">
									<div class="jp-play-bar"></div>
								</div>
							</div>';	
							
							
							$featured .= '
							<div class="jp-volume-group">
								<a href="javascript:;" class="jp-mute" title="mute">mute</a>
								<a href="javascript:;" class="jp-unmute" title="unmute">unmute</a>
								
								<div class="jp-volume-bar">
									<div class="jp-volume-bar-value"></div>
								</div>
							</div>
						</div>';	
	
					$featured .= '
						<div class="jp-track-title">
							<div class="jp-playlist '.$tl_class.'">
								<ul>
									<li></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>';
			
			if(is_array($tracklist) && count($tracklist) > 0) {
				// js code
				$featured .= '
				<script type="text/javascript">
				jQuery(function(){
					mg_lb_jplayer = function() {
						new jPlayerPlaylist({
							jPlayer: "#mg_audio_player_'.$post_id.'",
							cssSelectorAncestor: "#mg_audio_wrap_'.$post_id.'",
						}, [';
						
						$a = 1;
						foreach($tracklist as $track) {
							$track_data = get_post($track);
							
							($tot > 1) ? $counter = '<em>'.$a.'/'.$tot.'</em>) ' : $counter = ''; 
							
							$track_json[] = '
							{
								title:"'.$counter.addslashes($track_data->post_title).'",
								mp3:"'.$track_data->guid.'"
							}
							';
							
							$a++;
						}
			
						$featured .= implode(',', $track_json) . '
						], {';
						
						// autoplay
						$autoplay = (get_option('mg_audio_autoplay')) ? 'autoPlay: true,' : '';

						$featured .= '
							playlistOptions: {
								'.$autoplay.'
								displayTime: 0,
							},
							swfPath: "'.MG_URL.'/js/jPlayer/",
							supplied: "mp3"
						});
					}
				}); 
				</script>
				';
			}
		}
	}


	// force the layout for the lightbox custom contents
	if($type == 'lb_text') {$layout = 'full';}
	
	
	///////////////////////////
	// custom CSS to manage image's height
	if(($type == 'single_img' || $type == 'audio') && isset($img_max_h) && $img_max_h > 0) {
		?>
        <style type="text/css">
		.mg_item_featured {
			text-align: center;	
		}
		.mg_item_featured .jp-audio {
			text-align: left;	
		}
		.mg_item_featured > img {
			display: inline-block;
			margin: auto;
			width: auto !important;
			max-height: <?php echo $img_max_h ?>px;
		}
		</style>
        <?php
	}
	
	
	///////////////////////////
	// builder	
 
	/*** lightbox command codes ***/ 
	$cmd_mode = get_option('mg_lb_cmd_pos', 'inside');	
	?>
    <div id="mg_inside_close" class="mg_close_lb" <?php if($cmd_mode != 'inside' && $cmd_mode != 'ins_hidden') {echo 'style="display: none;"';} ?>></div>
    
    <div id="mg_lb_inside_nav" class="noSwipe" <?php if($cmd_mode != 'inside') {echo 'style="display: none;"';} ?>>
    	<?php echo mg_lb_nav_code(array('prev' => $prev_item, 'next' => $next_item), 'inside'); ?>
    </div>
    
    <?php 
	if($cmd_mode != 'inside' && $cmd_mode != 'ins_hidden') {
		if($cmd_mode == 'top') {
			$code = '
			<div id="mg_top_close" class="mg_close_lb" style="display: none;"></div>
			<div id="mg_lb_top_nav" style="display: none;">'. mg_lb_nav_code(array('prev' => $prev_item, 'next' => $next_item), $cmd_mode) .'</div>';
		} else {
			$code = '
			<div id="mg_top_close" class="mg_close_lb" style="display: none;"></div>'.
			mg_lb_nav_code(array('prev' => $prev_item, 'next' => $next_item), $cmd_mode);	
		}
		
		echo '
		<script type="text/javascript">
		jQuery("#mg_overlay_content").before("'. str_replace(array("\r", "\n", "\t", "\v"), '', str_replace('"', '\"', $code)) .'");
		jQuery("#mg_lb_top_nav, .mg_side_nav, #mg_top_close").fadeIn(350);
		
		if(navigator.appVersion.indexOf("MSIE 8.") != -1) {
			jQuery(".mg_side_nav > div").css("top", 0);	
		}
		</script>';	
	}
	?>
    
    
	<?php /*** internal contents ***/ ?>
    <div class="mg_layout_<?php echo $layout; ?> mg_lb_<?php echo $type; ?>">
      <div>
      	<?php if($type != 'lb_text') : ?>
		<div class="mg_item_featured" <?php if($fc_max_w) echo 'rel="'.$fc_max_w.'px"'; ?>>
			<?php echo $featured; ?>
		</div>
        <?php endif; ?>
		
		<div class="mg_item_content">
			<?php 
			/* custom options - woocommerce attributes */
			$opts = mg_lb_cust_opts_code($post_id, $type, $wc_prod);

			/* title and options wrap */
			if($layout == 'full' && !empty($opts)) {echo '<div class="mg_content_left">';} 
				echo '<h1 class="mg_item_title">'. apply_filters('the_title', $item_title) .'</h1>';
            	echo $opts;
            if($layout == 'full' && !empty($opts)) {echo '</div>';}
			?>
            
            
			<div class="mg_item_text <?php if($layout == 'full' && empty($cust_opt)) {echo 'mg_widetext';} ?>">
				<?php echo do_shortcode( wpautop( apply_filters('the_content', $post_data->post_content) )); ?>
                
                <?php if($wc_prod && !get_option('mg_wc_hide_add_to_cart')) {
					echo do_shortcode('[add_to_cart id="'.$post_id.'" style=""]');
				} ?>
            </div>
            
            
            <?php 
			// SOCIALS
			if(get_option('mg_facebook') || get_option('mg_twitter') || get_option('mg_pinterest')) : 
			  $dl_part = (get_option('mg_disable_dl')) ? '' : '#!mg_ld_'.$post_id; 
			?>
              <div id="mg_socials" class="mgls_<?php echo get_option('mg_lb_socials_style', 'squared') ?>">
            	<ul>
                  <?php if(get_option('mg_facebook')): ?>
                  <li id="mg_fb_share">
					<a onClick="window.open('https://www.facebook.com/dialog/feed?app_id=425190344259188&display=popup&name=<?php echo urlencode(get_the_title($post_id)); ?>&description=<?php echo urlencode(substr(strip_tags(strip_shortcodes(get_post_field('post_content', $post_id))), 0, 1000)); ?>&nbsp;&picture=<?php echo urlencode($fi_src[0]); ?>&link=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>&redirect_uri=http://www.lcweb.it/lcis_redirect.php','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"><span title="<?php _e('Share it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                  
                  
                  <?php if(get_option('mg_twitter')): ?>
                  <li id="mg_tw_share">
					<a onClick="window.open('https://twitter.com/share?text=<?php echo urlencode('Check out "'.get_the_title($post_id).'" on '.get_bloginfo('name')); ?>&url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"><span title="<?php _e('Tweet it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                  
                  
                  <?php if(get_option('mg_pinterest')): ?>
                  <?php // $pt_resized_img = mg_thumb_src($fi_src_pt[0], 736, false); ?>
                  <li id="mg_pn_share">
                  	<a onClick="window.open('http://pinterest.com/pin/create/button/?url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>&media=<?php echo urlencode($fi_src_pt[0]); ?>&description=<?php echo urlencode(get_the_title($post_id)); ?>','sharer','toolbar=0,status=0,width=575,height=330');" href="javascript: void(0)"><span title="<?php _e('Pin it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                  
                  
                  <?php if(get_option('mg_googleplus') && !get_option('mg_disable_dl')): ?>
                  <li id="mg_gp_share">
                  	<a onClick="window.open('https://plus.google.com/share?url=<?php echo urlencode(lcwp_curr_url().$dl_part); ?>','sharer','toolbar=0,status=0,width=490,height=360');" href="javascript: void(0)"><span title="<?php _e('Share it!', 'mg_ml') ?>"></span></a>
                  </li>
                  <?php endif; ?>
                </ul>
                
              </div>
            <?php endif; ?>
            
			<br style="clear: both;" />
		</div>
      </div>
	</div> 
	<?php
}
?>
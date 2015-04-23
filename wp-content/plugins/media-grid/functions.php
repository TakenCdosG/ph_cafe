<?php

// get the current URL
function lcwp_curr_url() {
	$pageURL = 'http';
	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://" . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

	return $pageURL;
}
	

// get file extension from a filename
function lcwp_stringToExt($string) {
	$pos = strrpos($string, '.');
	$ext = strtolower(substr($string,$pos));
	return $ext;	
}


// get filename without extension
function lcwp_stringToFilename($string, $raw_name = false) {
	$pos = strrpos($string, '.');
	$name = substr($string,0 ,$pos);
	if(!$raw_name) {$name = ucwords(str_replace('_', ' ', $name));}
	return $name;	
}


// string to url format // NEW FROM v1.11 for non-latin characters 
function lcwp_stringToUrl($string){
	
	// if already exist at least an option, use the default encoding
	if(!get_option('mg_non_latin_char')) {
		$trans = array("à" => "a", "è" => "e", "é" => "e", "ò" => "o", "ì" => "i", "ù" => "u");
		$string = trim(strtr($string, $trans));
		$string = preg_replace('/[^a-zA-Z0-9-.]/', '_', $string);
		$string = preg_replace('/-+/', "_", $string);	
	}
	
	else {$string = trim(urlencode($string));}
	
	return $string;
}


// normalize a url string
function lcwp_urlToName($string) {
	$string = ucwords(str_replace('_', ' ', $string));
	return $string;	
}


// remove a folder and its contents
function lcwp_remove_folder($path) {
	if($objs = @glob($path."/*")){
		foreach($objs as $obj) {
			@is_dir($obj)? lcwp_remove_folder($obj) : @unlink($obj);
		}
	 }
	@rmdir($path);
	return true;
}


// convert HEX to RGB
function mg_hex2rgb($hex) {
   	// if is RGB or transparent - return it
   	$pattern = '/^#[a-f0-9]{6}$/i';
	if(empty($hex) || $hex == 'transparent' || !preg_match($pattern, $hex)) {return $hex;}
  
	$hex = str_replace("#", "", $hex);
   	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
  
	return 'rgb('. implode(",", $rgb) .')'; // returns the rgb values separated by commas
}


// convert RGB to HEX
function mg_rgb2hex($rgb) {
   	// if is hex or transparent - return it
   	$pattern = '/^#[a-f0-9]{6}$/i';
	if(empty($rgb) || $rgb == 'transparent' || preg_match($pattern, $rgb)) {return $rgb;}

  	$rgb = explode(',', str_replace(array('rgb(', ')'), '', $rgb));
  	
	$hex = "#";
	$hex .= str_pad(dechex( trim($rgb[0]) ), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex( trim($rgb[1]) ), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex( trim($rgb[2]) ), 2, "0", STR_PAD_LEFT);

	return $hex; 
}


// hex color to RGBA
function mg_hex2rgba($hex, $alpha) {
	$rgba = str_replace(array('rgb', ')'), array('rgba', ', '.$alpha.')'), mg_hex2rgb($hex));
	return $rgba;	
}


// create youtube and vimeo embed url
function lcwp_video_embed_url($raw_url, $manual_autoplay = '') {
	if(strpos($raw_url, 'vimeo') !== false) {
		$code = substr($raw_url, (strrpos($raw_url, '/') + 1));
		$url = '//player.vimeo.com/video/'.$code.'?title=0&amp;byline=0&amp;portrait=0';
	}
	elseif(strpos($raw_url, 'youtu.be') !== false) {
		$code = substr($raw_url, (strrpos($raw_url, '/') + 1));
		$url = '//www.youtube.com/embed/'.$code.'?rel=0';	
	}
	elseif(strpos($raw_url, 'dailymotion.com') !== false || strpos($raw_url, 'dai.ly') !== false) {
		if(substr($raw_url, -1) == '/') {$raw_url = substr($raw_url, 0, -1);}
		$parts = explode('/', $raw_url);
		$arr = explode('_', end($parts));
		$url = '//www.dailymotion.com/embed/video/'.$arr[0];	
	}
	else {return 'wrong_url';}
	
	// autoplay
	if( (get_option('mg_video_autoplay') && $manual_autoplay !== false) || $manual_autoplay === true ) {
		$url .= (strpos($raw_url, 'dailymotion.com') !== false) ? '?autoPlay=1' : '&amp;autoplay=1';
	}
	
	return $url;
}


// get soundcloud embed code
function mg_get_soundcloud_embed($track_url) {
	include_once(MG_DIR . '/classes/soundcloud-api/Services/Soundcloud.php');
	
	$pub = '69c06a70f88e8ec80a414ae55dab369c';
	$sec = 'f7fe291ae99ef71d3fd81c084897309e';
	$callback = 'http://www.lcweb.it/';
	
	$client = new Services_Soundcloud($pub, $sec, $callback);
	$client->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));
	$track = json_decode($client->get('resolve', array('url' => $track_url)));

	$autoplay = (get_option('mg_audio_autoplay')) ? 'true' : 'false';

	return '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="//w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$track->id.'&amp;color=ff5500&amp;auto_play='.$autoplay.'&amp;hide_related=true&amp;show_artwork=true" style="margin-top: 7px;"></iframe>';
}


// know if woocommerce is active
function mg_woocomm_active() {
	return (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option( 'active_plugins' )))) ? true : false;
}

///////////////////////////////////////////////////////

// get translated option name - WPML integration
function mg_wpml_string($type, $original_val) {
	if(function_exists('icl_t')){
		$typename = ($type == 'img_gallery') ? 'Image Gallery' : ucfirst($type);
		$index = $typename.' Attributes - '.$original_val;
		
		return icl_t('Media Grid - Item Attributes', $index, $original_val);
	}
	else{
		return $original_val;
	}
}


// mq/qtranslate - implement item categories translation
function mg_check_qtranslate() {
	if(is_plugin_active('mqtranslate/mqtranslate.php') || is_plugin_active('qtranslate/qtranslate.php')) {
	  add_action( 'mg_item_categories_add_form', 'qtrans_modifyTermFormFor');
	  add_action( 'mg_item_categories_edit_form', 'qtrans_modifyTermFormFor');
	}
}
add_action('admin_init', 'mg_check_qtranslate');


///////////////////////////////////////////////////////

// sanitize input field values
function mg_sanitize_input($val) {
	return trim(
		str_replace(array('\'', '"', '<', '>'), array('&apos;', '&quot;', '&lt;', '&gt;'), (string)$val)
	);	
}


// preloader code
function mg_preloader() {
	return '
	<div class="mg_loader">
	  <div class="mgl_1"></div>
	  <div class="mgl_2"></div>
	  <div class="mgl_3"></div>
	  <div class="mgl_4"></div>
	</div>';	
}


// custom type options - indexes 
function mg_main_types() {
	return array(
		'image' => __('Image', 'mg_ml'), 
		'img_gallery' => __('Image Gallery', 'mg_ml'), 
		'video' => __('Video', 'mg_ml'), 
		'audio' => __('Audio', 'mg_ml')
	);	
}


// given the item main type slug - return the name
function mg_item_types($type = false) {
	$types = array(
		'simple_img' 	=> __('Static Image', 'mg_ml'),
		'single_img' 	=> __('Single Image', 'mg_ml'),
		'img_gallery' 	=> __('Images Slider', 'mg_ml'),
		'inl_slider' 	=> __('Inline Slider', 'mg_ml'),
		'video' 		=> __('Video', 'mg_ml'),
		'inl_video' 	=> __('Inline Video', 'mg_ml'),
		'audio'			=> __('Audio', 'mg_ml'),
		'link'			=> __('Link', 'mg_ml'),
		'lb_text'		=> __('Custom Content', 'mg_ml'),
		'inl_text'		=> __('Inline Text', 'mg_ml'),
		'spacer'		=> __('Spacer', 'mg_ml'),
		'woocom'		=> __('WooCommerce', 'mg_ml'),
	);
	return (!$type) ? $types : $types[$type];
}


// slider cropping methods
function mg_galleria_crop_methods($type = false) {
	$types = array(
		'true' 		=> __('Fit, center and crop', 'mg_ml'),
		'false' 	=> __('Scale down', 'mg_ml'),
		'height'	=> __('Scale to fill the height', 'mg_ml'),
		'width'		=> __('Scale to fill the width', 'mg_ml'),
		'landscape'	=> __('Fit images with landscape proportions', 'mg_ml'),
		'portrait' 	=> __('Fit images with portrait proportions', 'mg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider effects
function mg_galleria_fx($type = false) {
	$types = array(
		'fadeslide' => __('Fade and slide', 'mg_ml'),
		'fade' 		=> __('Fade', 'mg_ml'),
		'flash'		=> __('Flash', 'mg_ml'),
		'pulse'		=> __('Pulse', 'mg_ml'),
		'slide'		=> __('Slide', 'mg_ml'),
		''			=> __('None', 'mg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider thumbs visibility options
function mg_galleria_thumb_opts($type = false) {
	$types = array(
		'always'	=> __('Always', 'mg_ml'),
		'yes' 		=> __('Yes with toggle button', 'mg_ml'),
		'no' 		=> __('No with toggle button', 'mg_ml'),
		'never' 	=> __('Never', 'mg_ml'),
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// image ID to path
function mg_img_id_to_path($img_src) {
	if(is_numeric($img_src)) {
		$wp_img_data = wp_get_attachment_metadata((int)$img_src);
		if($wp_img_data) {
			$upload_dirs = wp_upload_dir();
			$img_src = $upload_dirs['basedir'] . '/' . $wp_img_data['file'];
		}
	}
	
	return $img_src;
}


// thumbnail source switch between timthumb and ewpt
function mg_thumb_src($img_id, $width = false, $height = false, $quality = 80, $alignment = 'c', $resize = 1, $canvas_col = 'FFFFFF', $fx = array()) {
	if(!$img_id) {return false;}
	
	if(get_option('mg_use_timthumb')) {
		$thumb_url = MG_TT_URL.'?src='.mg_img_id_to_path($img_id).'&w='.$width.'&h='.$height.'&a='.$alignment.'&q='.$quality.'&zc='.$resize.'&cc='.$canvas_col;
	} else {
		$thumb_url = easy_wp_thumb($img_id, $width, $height, $quality, $alignment, $resize, $canvas_col , $fx);
	}	
	
	return $thumb_url;
}
 


// get the patterns list 
function mg_patterns_list() {
	$patterns = array();
	$patterns_list = scandir(MG_DIR."/img/patterns");
	
	foreach($patterns_list as $pattern_name) {
		if($pattern_name != '.' && $pattern_name != '..') {
			$patterns[] = $pattern_name;
		}
	}
	return $patterns;	
}


// check if there is at leat one custom option
function mg_cust_opt_exists() {
	$types = mg_main_types();
	$exists = false;
	
	foreach($types as $type => $name) {
		if(get_option('mg_'.$type.'_opt') && count(get_option('mg_'.$type.'_opt')) > 0) {$exists = true; break;}	
	}
	return $exists;
}


// sizes array
function mg_sizes() {
	return array(
		'1_1',
		'1_2',
		
		'1_3',
		'2_3',
		
		'1_4',
		'3_4',
		
		'1_5',
		'2_5',
		'3_5',
		'4_5',
		
		'1_6',
		'5_6',
		
		'1_8',
		'1_9',
		'1_10'
	);
}

// mobile sizes array
function mg_mobile_sizes() {
	return array(
		'1_1',
		'1_2',	
		
		'1_3',
		'2_3',
		
		'1_4',
		'3_4',
	);
}


// sizes to percents
function mg_size_to_perc($size, $leave_auto = false) {
	if($leave_auto && $size == 'auto') {return 'auto';}
	
	switch($size) {
		case '1_10': $perc = 0.1; break;
		case '1_9': $perc = 0.111; break;
		case '1_8': $perc = 0.125; break;
		
		case '5_6': $perc = 0.83; break;
		case '1_6': $perc = 0.166; break;
		
		case '4_5': $perc = 0.80; break;
		case '3_5': $perc = 0.60; break;
		case '2_5': $perc = 0.40; break;
		case '1_5':
		case 'auto':$perc = 0.20; break;
		
		case '3_4': $perc = 0.75; break;
		case '1_4': $perc = 0.25; break;
		
		case '2_3': $perc = 0.666; break;
		case '1_3': $perc = 0.333; break;
		
		case '1_2': $perc = 0.50; break;
		default :	$perc = 1; break;
	}
	
	return $perc;
}


// get image sizes for inline slider || $wp_data[1] = w / $wp_data[2] = h 
function mg_inl_slider_img_sizes($wp_data, $grid_max_width, $grid_item) {
	$mobile_tres = get_option('mg_mobile_treshold', 800);
	
	// find item max width
	$nw = $grid_max_width * mg_size_to_perc($grid_item['w']);
	$mw = $mobile_tres * mg_size_to_perc($grid_item['m_w']);
	$item_max_w = max($nw, $mw);
	
	// find item max height
	$nh = $grid_max_width * mg_size_to_perc($grid_item['h']);
	$mh = $mobile_tres * mg_size_to_perc($grid_item['m_h']);
	$item_max_h = max($nh, $mh);
	
	$img_sizes = array();
	$img_sizes['w'] = ($item_max_w < $wp_data[1]) ? $item_max_w : $wp_data[1];
	$img_sizes['h'] = ($item_max_h < $wp_data[2]) ? $item_max_h : $wp_data[2];
	
	return $img_sizes; 	
}


// print type attribute fields
function mg_get_type_opt_fields($type, $post) {
	if(!get_option('mg_'.$type.'_opt')) {return false;}
	$icons = get_option('mg_'.$type.'_opt_icon');
	
	$copt = '
	<h4>'. __('Custom Attributes', 'mg_ml') .'</h4>
	<table class="widefat lcwp_table lcwp_metabox_table mg_user_opt_table">';	
	
	$a = 0;
	foreach(get_option('mg_'.$type.'_opt') as $opt) {
		$val = get_post_meta($post->ID, 'mg_'.$type.'_'.strtolower(lcwp_stringToUrl($opt)), true);
		$icon = (isset($icons[$a])) ? '<i class="mg_item_builder_opt_icon fa '.$icons[$a].'"></i> ' : '';
		
		$copt .= '
		<tr>
          <td class="lcwp_label_td">'.$icon . mg_wpml_string($type, $opt).'</td>
          <td class="lcwp_field_td">
		  	<input type="text" name="mg_'.$type.'_'.strtolower(lcwp_stringToUrl($opt)).'" value="'.mg_sanitize_input($val).'" />
          </td>     
          <td><span class="info"></span></td>
        </tr>';
		
		$a++;
	}
	
	$copt .= '</table>';
	return $copt;
}


// metabox types options
function mg_types_meta_opt($type) {
	
	// static image
	if($type == 'simple_img') {
		$opt_arr = array(
			array(
				'label' 	=> __('Display overlays?', 'mg_ml'),
				'name'		=> 'mg_static_show_overlay',
				'descr'		=> __('If checked displays the overlays also for this static item', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_static_show_overlay', 'label'=>'Static Overlays')
			),
		);
	}
	
	// img slider
	elseif($type == 'img_gallery') {
		$opt_arr = array(
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_w_val', 'label'=>'Slider height value')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_w_type', 'label'=>'Slider height type')
			),
			array(
				'label' 	=> __('Crop Method', 'mg_ml'),
				'name'		=> 'mg_slider_crop',
				'descr'		=> __('Select the cropping method for the shown image', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> mg_galleria_crop_methods(),
				'validate'	=> array('index'=>'mg_slider_crop', 'label'=>'Crop Method')
			),
			array(
				'label' 	=> __('Autoplay slideshow?', 'mg_ml'),
				'name'		=> 'mg_slider_autoplay',
				'descr'		=> __('If checked autoplay the slider slideshow', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_slider_autoplay', 'label'=>'Autoplay slideshow')
			),
			array(
				'label' 	=> __('Show thumbnails?', 'mg_ml'),
				'name'		=> 'mg_slider_thumbs',
				'descr'		=> __('Select if and how the thumbs will be shown', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> mg_galleria_thumb_opts(),
				'validate'	=> array('index'=>'mg_slider_thumbs', 'label'=>'Show thumbnails')
			),
			array(
				'label' 	=> __('Display captions?', 'mg_ml'),
				'name'		=> 'mg_slider_captions',
				'descr'		=> __('If checked displays the captions in the slider', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_slider_captions', 'label'=>'Slider Captions')
			),
			array(
				'label' 	=> __('Random images?', 'mg_ml'),
				'name'		=> 'mg_slider_random',
				'descr'		=> __('If checked, randomize slider images', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_slider_random', 'label'=>'Slider randomize')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_img', 'label'=>'Slider Images')
			)
		);
	}
	
	// inline slider
	elseif($type == 'inl_slider') {
		$opt_arr = array(
			array(
				'label' 	=> __('Autoplay slideshow?', 'mg_ml'),
				'name'		=> 'mg_inl_slider_autoplay',
				'descr'		=> __('If checked autoplay the slider slideshow', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_inl_slider_autoplay', 'label'=>'Autoplay slideshow')
			),
			array(
				'label' 	=> __('Display captions?', 'mg_ml'),
				'name'		=> 'mg_inl_slider_captions',
				'descr'		=> __('If checked displays the captions in the slider', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_inl_slider_captions', 'label'=>'Slider Captions')
			),
			array(
				'label' 	=> __('Random images?', 'mg_ml'),
				'name'		=> 'mg_inl_slider_random',
				'descr'		=> __('If checked, randomize slider images', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_inl_slider_random', 'label'=>'Slider randomize')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_img', 'label'=>'Slider Images')
			)
		);
	}
	
	// video
	elseif($type == 'video' || $type == 'inl_video') {
		$opt_arr = array(
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_video_url', 'label'=>'Video URL')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_video_use_poster', 'label'=>'Use featured image as poster (HTML 5 video)')
			)
		);
	}
	
	// audio
	elseif($type == 'audio') {
		$opt_arr = array(
				 array(
					'type' 		=> 'empty',
					'validate'	=> array('index'=>'mg_soundcloud_url', 'label'=>'Soundcloud URL')
				),
				 array(
				 	'type' 		=> 'empty',
					'validate'	=> array('index'=>'mg_audio_tracks', 'label'=>'Tracklist')
			)
		);	
	}
		
	// link
	elseif($type == 'link') {
		$opt_arr = array(
			array(
				'label' 	=> __('Link URL', 'mg_ml'),
				'name'		=> 'mg_link_url',
				'descr'		=> '',
				'type' 		=> 'text',
				'validate'	=> array('index'=>'mg_link_url', 'label'=>'Link URL')
			),
			array(
				'label' 	=> __('Link Target', 'mg_ml'),
				'name'		=> 'mg_link_target',
				'descr'		=> __('where the link will be opened', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> array('top' => __('In the same page', 'mg_ml'), 'blank' => __('In a new page', 'mg_ml')),
				'validate'	=> array('index'=>'mg_link_target', 'label'=>'Link target')
			),
			array(
				'label' 	=> __('Use nofollow?', 'mg_ml'),
				'name'		=> 'mg_link_nofollow',
				'descr'		=> __('if enabled, use the rel="nofollow"', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> array('0' => __('No', 'mg_ml'), '1' => __('Yes', 'mg_ml')),
				'validate'	=> array('index'=>'mg_link_nofollow', 'label'=>'Link nofollow')
			)
		);
	}
	
	// inline text
	elseif($type == 'inl_text') {
		$opt_arr = array(
			array(
				'label' 	=> __('Box background', 'mg_ml'),
				'name'		=> 'mg_inl_txt_box_bg',
				'descr'		=> __('Leave blank to use the default one (<em>transparent</em> allowed)', 'mg_ml'),
				'type' 		=> 'color',
				'validate'	=> array('index'=>'mg_inl_txt_box_bg', 'label'=>'Box background')
			),
			array(
				'label' 	=> __('Background opacity', 'mg_ml'),
				'name'		=> 'mg_inl_txt_bg_alpha',
				'descr'		=> __('Background opacity', 'mg_ml'),
				'type' 		=> 'slider',
				'min_val' 	=> '0',
				'max_val'	=> '100',
				'step' 		=> '5',
				'value' 	=> '%',
				'def' 		=> get_option('mg_img_border_opacity', 100),
				'validate'	=> array('index'=>'mg_inl_txt_bg_alpha', 'label'=>'Background opacity')
			),
			array(
				'label' 	=> __('Text main color', 'mg_ml'),
				'name'		=> 'mg_inl_txt_color',
				'descr'		=> __('Leave blank to use the default one', 'mg_ml'),
				'type' 		=> 'color',
				'validate'	=> array('index'=>'mg_inl_txt_color', 'label'=>'Text color')
			),
			array(
				'label' 	=> __('Vertical alignment', 'mg_ml'),
				'name'		=> 'mg_inl_txt_vert_align',
				'descr'		=> __('text vertical alignment in the box', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> array('middle' => __('middle', 'mg_ml'), 'top' => __('top', 'mg_ml'), 'bottom' => __('bottom', 'mg_ml')),
				'validate'	=> array('index'=>'mg_inl_txt_vert_align', 'label'=>'Vertical alignment')
			)
		);
	}
	else {return false;}
	
	return $opt_arr;	
}


// metabox option generator 
function mg_meta_opt_generator($type, $post) {
	$opt_arr = mg_types_meta_opt($type);
	$opt_data = '<table class="widefat lcwp_table lcwp_metabox_table">';
	
	foreach($opt_arr as $opt) {
		if($opt['type'] != 'empty') {
			$val = get_post_meta($post->ID, $opt['name'], true);
			if(!$val && isset($opt['def'])) {$val = $opt['def'];}
			
			$opt_data .= '
			<tr>
			  <td class="lcwp_label_td">'.$opt['label'].'</td>
			  <td class="lcwp_field_td">';
			  
			if($opt['type'] == 'text') {  
				$opt_data .= '<input type="text" name="'.$opt['name'].'" value="'.$val.'" />';
			}
			
			elseif($opt['type'] == 'select') {
				$opt_data .= '<select data-placeholder="'. __('Select an option', 'mg_ml') .' .." name="'.$opt['name'].'" class="lcweb-chosen" autocomplete="off">';
				
				foreach($opt['options'] as $key=>$name) {
					($key == $val) ? $sel = 'selected="selected"' : $sel = '';
					$opt_data .= '<option value="'.$key.'" '.$sel.'>'.$name.'</option>';	
				}
				
				$opt_data .= '</select>';
			} 
			
			elseif($opt['type'] == 'checkbox') {
				($val) ? $sel = 'checked="checked"' : $sel = '';
				$opt_data .= '<input type="checkbox" name="'.$opt['name'].'" value="1" class="ip-checkbox" '.$sel.' autocomplete="off" />';	
			}
			
			elseif($opt['type'] == 'slider') {
				$opt_data .= '
				<div class="lcwp_slider" step="'.$opt['step'].'" max="'.$opt['max_val'].'" min="'.$opt['min_val'].'"></div>
				<input type="text" value="'.$val.'" name="'.$opt['name'].'" class="lcwp_slider_input" />
				<span>'.$opt['value'].'</span>';
			}
			
			elseif($opt['type'] == 'color') {
				$opt_data .= '
				<div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: '.$val.';"></span>
                	<input type="text" name="'.$opt['name'].'" value="'.$val.'" />
                </div>';	
			}
			  
			$opt_data .= ' 
			  </td>     
			  <td><span class="info">'.$opt['descr'].'</span></td>
			</tr>
			';
		}
	}
	
	return $opt_data . '</table>';
}


// get type options indexes from the main type
function mg_get_type_opt_indexes($type) {
	if($type == 'simple_img' || $type == 'link') {return false;}
	
	if($type == 'single_img') {$copt_id = 'image';}
	else {$copt_id = $type;}

	if(!get_option('mg_'.$copt_id.'_opt')) {return false;}
	
	$indexes = array();
	foreach(get_option('mg_'.$copt_id.'_opt') as $opt) {
		$indexes[] = 'mg_'.$copt_id.'_'.strtolower(lcwp_stringToUrl($opt));
	}
	
	return $indexes;	
}


// prepare the array of custom options not empty for an item
function mg_item_copts_array($type, $post_id) {
	if($type == 'single_img') {$type = 'image';}
	$copts = get_option('mg_'.$type.'_opt');
	
	$arr = array();
	if(is_array($copts)) {
		foreach($copts as $copt) {
			$val = get_post_meta($post_id, 'mg_'.$type.'_'.strtolower(lcwp_stringToUrl($copt)), true);
			
			if($val && $val != '') {
				$arr[$copt] = $val;	
			}
		}
	}
	return $arr;
}


// woocommerce integration - get product attributes
function mg_wc_prod_attr($prod_obj){
    $attributes = $prod_obj->get_attributes();
 	
	$prod_attr = array();
    if (!$attributes) {return $prod_attr;}
 
    foreach ($attributes as $attribute) {

        // skip variations
        if ( $attribute['is_variation'] ) {continue;}

        if ( $attribute['is_taxonomy'] ) {
            $terms = wp_get_post_terms($prod_obj->id, $attribute['name'], 'all');
 
            // get the taxonomy
            $tax = $terms[0]->taxonomy;
 
            // get the tax object
            $tax_object = get_taxonomy($tax);
 
            // get tax label
            if ( isset ($tax_object->labels->name) ) {
                $tax_label = $tax_object->labels->name;
            } elseif ( isset( $tax_object->label ) ) {
                $tax_label = $tax_object->label;
            }
 
            foreach ($terms as $term) {
            	if(isset($prod_attr[$tax_label])) {
					$prod_attr[$tax_label][] = $term->name;
				} else {
					$prod_attr[$tax_label] = array($term->name);	
				}
			}
        } else {
 			if(isset($prod_attr[ $attribute['name'] ])) {
				$prod_attr[ $attribute['name'] ][] = $attribute['value'];
			} else {
				$prod_attr[ $attribute['name'] ] = array($attribute['value']);	
			}
        }
    }

    return $prod_attr;
}


// return lightbox custom options / attributes code
function mg_lb_cust_opts_code($post_id, $type, $wc_prod = false) {
	if($type == 'single_img') {$type = 'image';}
	$code = '';
	
	if(!$wc_prod) {
		$type_opts = get_option('mg_'.$type.'_opt');
		$cust_opt = mg_item_copts_array($type, $post_id); 
		$icons = get_option('mg_'.$type.'_opt_icon');
	
		if(count($cust_opt) > 0) {
			$code .= '<ul class="mg_cust_options">';
			
			$a=0;
			foreach($type_opts as $opt) {
				if(isset($cust_opt[$opt])) {				
					$icon = (isset($icons[$a]) && !empty($icons[$a])) ? '<i class="mg_cust_opt_icon fa '.$icons[$a].'"></i> ' : '';
					$code .= '<li>'.$icon.'<span>'.mg_wpml_string($type, $opt).'</span> '.do_shortcode(str_replace(array('&lt;', '&gt;'), array('<', '>'), $cust_opt[$opt])).'</li>';
				}
				$a++;
			}
			
			$code .= '</ul>';
		}
	}
	
	// woocomm attributes
	else {
		$prod_attr = mg_wc_prod_attr($wc_prod);
		if(is_array($prod_attr) && count($prod_attr) > 0 && !get_option('mg_wc_hide_attr')) {
			$code .= '<ul class="mg_cust_options">';
					
			foreach($prod_attr as $attr => $val) {					
				$icon = get_option('mg_wc_attr_'.sanitize_title($attr).'_icon');
				$icon_code = (!empty($icon)) ? '<i class="mg_cust_opt_icon fa '.$icon.'"></i> ' : '';
				
				$code .= '<li>'.$icon_code.'<span>'.$attr.'</span> '.do_shortcode(implode(', ', $val)).'</li>';
			}
					
			// add rating if allowed and there's any
			if(get_post_field('comment_status', $post_id) != 'closed' && $wc_prod->get_rating_count() > 0) {
				$rating = round((float)$wc_prod->get_average_rating());
				$empty_stars = 5 - $rating;
			
				$code .= '<li class="mg_wc_rating">';
				for($a=0; $a < $rating; $a++) 		{$code .= '<i class="fa fa-star"></i>';}
				for($a=0; $a < $empty_stars; $a++) 	{$code .= '<i class="fa fa-star-o"></i>';}
				$code .= '</li>';
			}
			
			$code .= '</ul>';
		}
	}
	
	return $code;
}


// giving an array of items categories, return the published items
function mg_get_cat_items($cat) {
	if(!$cat) {return false;}
	
	// post types
	$post_types = array('mg_items');
	if(mg_woocomm_active()) {$post_types[] = 'product';}
	
	// meta query to allow selective WC products
	$not_in = array('wc_no');
	if(!mg_woocomm_active() || !get_option('mg_integrate_wc')) {$not_in = array_merge($not_in, array(false, 'wc_auto'));}
	
	$args = array(
		'post_type' => $post_types,
		'post_status' => 'publish', 
		'posts_per_page' => 3000, 
		'orderby' => 'title',
		'order' => 'ASC',
		'meta_query' => array( 
			array(
				'key' => 'mg_main_type',
				'value' => $not_in,
				'compare' => 'NOT IN'
			)
		)
	);
	
	// filter by cat
	if($cat != 'all') {
		$term_data = get_term_by( 'id', $cat, 'mg_item_categories');	
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'mg_item_categories', 
				'field' => 'slug', //(string) - Select taxonomy term by ('id' or 'slug')
				'terms' => $term_data->slug,
				'operator' => 'IN'
			)
		);   	
	}	
	
	$query = new WP_Query($args);
	$items = $query->posts;

	
	$items_list = array();
	foreach($items as $item) {
		$post_id = $item->ID;
		$img_id = get_post_thumbnail_id($post_id);
		$type = ($item->post_type  == 'product') ? 'woocom' : get_post_meta($post_id, 'mg_main_type', true);
		
		// show only items with featured image
		if(!empty($img_id) || ($type == 'spacer' || $type == 'inl_slider' || $type == 'inl_video' || $type == 'inl_text')) {
			$items_list[] = array(
				'id'	=> $post_id, 
				'title'	=> $item->post_title, 
				'type' 	=> $type,
				'img' => $img_id
			);
		}
	}
	return $items_list;
}


// given an array of post_id, retrieve the data for the builder
function mg_grid_builder_items_data($items) {
	if(!is_array($items) || count($items) == 0) {return false;}
	
	$items_data = array();
	foreach($items as $item_id) {	
		$items_data[] = array(
			'id'	=> $item_id, 
			'title'	=> get_the_title($item_id), 
			'type' 	=> get_post_meta($item_id, 'mg_main_type', true)
		);
	}
	
	return $items_data;
}


// get the images from the WP library
function mg_library_images($page = 1, $per_page = 15, $search = '') {
	$query_images_args = array(
		'post_type' => 'attachment', 
		'post_mime_type' =>'image', 
		'post_status' => 'inherit', 
		'posts_per_page' => $per_page, 
		'paged' => $page
	);
	if(isset($search) && !empty($search)) {
		$query_images_args['s'] = $search;	
	}
	
	$query_images = new WP_Query( $query_images_args );
	$images = array();
	
	foreach ( $query_images->posts as $image) { 
		$images[] = $image->ID;
	}
	
	// global images number
	$img_num = $query_images->found_posts;
	
	// calculate the total
	$tot_pag = ceil($img_num / $per_page);
	
	// can show more?
	$shown = $per_page * $page;
	($shown >= $img_num) ? $more = false : $more = true; 
	
	return array('img' => $images, 'pag' => $page, 'tot_pag' =>$tot_pag, 'more' => $more, 'tot' => $img_num);
}


// get the audio files from the WP library
function mg_library_audio($page = 1, $per_page = 15, $search = '') {
	$query_audio_args = array(
		'post_type' => 'attachment', 
		'post_mime_type' =>'audio', 
		'post_status' => 'inherit', 
		'posts_per_page' => $per_page, 
		'paged' => $page
	);
	if(isset($search) && !empty($search)) {
		$query_audio_args['s'] = $search;	
	}
	
	$query_audio = new WP_Query( $query_audio_args );
	$tracks = array();
	
	foreach ( $query_audio->posts as $audio) { 
		$tracks[] = array(
			'id'	=> $audio->ID,
			'url' 	=> $audio->guid, 
			'title' => $audio->post_title
		);
	}
	
	// global images number
	$track_num = $query_audio->found_posts;
	
	// calculate the total
	$tot_pag = ceil($track_num / $per_page);
	
	// can show more?
	$shown = $per_page * $page;
	($shown >= $track_num) ? $more = false : $more = true; 
	
	return array('tracks' => $tracks, 'pag' => $page, 'tot_pag' =>$tot_pag  ,'more' => $more, 'tot' => $track_num);
}


// given an array of selected images or tracks - returns only existing ones
function mg_existing_sel($media) {
	if(is_array($media)) {
		$new_array = array();
		
		foreach($media as $media_id) {
			if( get_the_title($media_id)) {	
				$new_array[] = $media_id;
			}
		}
		
		if(count($new_array) == 0) {return false;}
		else {return $new_array;}
	}
	else {return false;}	
}


// update grid terms once the item is updated
function mg_upd_item_upd_grids($item_id) {
	$grids = get_terms('mg_grids', 'hide_empty=0');
	if(!is_array($grids)) {return false;}
	
	foreach($grids as $grid) {
		$grid_data = (empty($grid->description)) ? array('items' => array()) : unserialize($grid->description); 
		
		// check if item is part of the grid
		$exists = false;
		foreach($grid_data['items'] as $item) {
			if($item_id == $item['id']) {
				$exists = true;
				break;	
			}
		}
		
		// if the item is in the grid
		if($exists) {
			// save the terms list for the posts
			$terms_array = array();
			foreach($grid_data['items'] as $item) {
				$pid_terms = wp_get_post_terms($item['id'], 'mg_item_categories', array("fields" => "ids"));
				foreach($pid_terms as $pid_term) { $terms_array[] = $pid_term; }	
			}
			$terms_array = array_unique($terms_array);
			$grid_data['cats'] = $terms_array;
			
			// update grid term
			wp_update_term($grid->term_id, 'mg_grids', array('description' => serialize($grid_data)));
		}
	}
}


// return the grid categories by the chosen order
function mg_order_grid_cats($terms) {
	$ordered = array();
	
	foreach($terms as $term_id) {
		$ord = (int)get_option("mg_cat_".$term_id."_order");
		
		// check the final order
		while( isset($ordered[$ord]) ) {
			$ord++;	
		}
		
		$ordered[$ord] = $term_id;
	}
	
	ksort($ordered, SORT_NUMERIC);
	return $ordered;	
}


// get the grid terms data
function mg_grid_terms_data($grid_id, $return = 'html', $selected = false, $hide_all = false) {
	$grid = get_term_by('id', $grid_id, 'mg_grids');
	$data = (empty($grid->description)) ? array('items' => array(), 'cats' => array()) : unserialize($grid->description); 
	$terms = $data['cats'];
	
	if(!$terms) { return false; }
	else {
		$terms = mg_order_grid_cats($terms);
		$terms_data = array();
		$all_txt = (get_option('mg_all_filter_txt') != '') ? get_option('mg_all_filter_txt') : __('All', 'mg_ml');
		
		$a = 0;
		foreach($terms as $term) {
			// WPML - check translation
			if(function_exists('icl_object_id')) {
				$term = icl_object_id($term, 'mg_item_categories', true);	
			}
			
			$term_data = get_term_by('id', $term, 'mg_item_categories');
			if(is_object($term_data)) {
				$terms_data[$a] = array('id' => $term, 'name' => $term_data->name, 'slug' => $term_data->slug); 		
				$a++;
			}
		}
		
		if($return == 'array') {return $terms_data;}
		elseif($return == 'dropdown') {
			$code = '<select class="mg_mobile_filter_dd" autocomplete="off">';
			
			if(!$hide_all) {
				$code .= '<option value="*">'. $all_txt .'</option>';	
			}
			
			foreach($terms_data as $term) {
				$selected = ($term['id'] == $selected) ? 'selected="selected"' : '';
				$code .= '<option value="'.$term['id'].'" '.$selected.'>'.$term['name'].'</option>';	
			}
				
			return $code . '</select>';	
		}
		else {
			$all_sel = (!$selected) ? 'mg_cats_selected' : '';
			$grid_terms_list = (!$hide_all) ? '<a class="'.$all_sel.' mgf_all mgf" rel="*" href="javascript:void(0)">'. $all_txt .'</a>' : '';
			$separator = (get_option('mg_use_old_filters')) ? '<span>/</span>' : '';

			$a = 0;
			foreach($terms_data as $term) {
				$true_sep = ($a == 0 && $hide_all) ? '' : $separator; 
				$sel = ($selected == $term['id']) ? 'mg_cats_selected' : '';
				$grid_terms_list .= $true_sep.'<a rel="'.$term['id'].'" class="mgf_id_'.$term['id'].' '.$sel.' mgf" href="javascript:void(0)">'.$term['name'].'</a>';
				
				$a++;	
			}
			return $grid_terms_list;
		}
	}
}


// get the terms of a grid item - return the CSS class
function mg_item_terms_classes($post_id) {
	$pid_classes = array();
	
	$pid_terms = wp_get_post_terms($post_id, 'mg_item_categories', array("fields" => "ids"));
	foreach($pid_terms as $pid_term) { $pid_classes[] = 'mgc_'.$pid_term; }	
	
	return implode(' ', $pid_classes);	
}


// create the frontend css and js
function mg_create_frontend_css() {	
	ob_start();
	require(MG_DIR.'/frontend_css.php');
	
	$css = ob_get_clean();
	if(trim($css) != '') {
		if(!@file_put_contents(MG_DIR.'/css/custom.css', $css, LOCK_EX)) {$error = true;}
	}
	else {
		if(file_exists(MG_DIR.'/css/custom.css'))	{ unlink(MG_DIR.'/css/custom.css'); }
	}
	
	if(isset($error)) {return false;}
	else {return true;}
}


// custom excerpt
function mg_excerpt($string, $max) {
	$num = strlen($string);
	
	if($num > $max) {
		$string = substr($string, 0, $max) . '..';
	}
	
	return $string;
}


// lightbox image optimizer - serve resized images in lightbox
function mg_lb_image_optimizer($img_id, $layout = 'full', $max_h = false, $thumb_center = 'c', $resize = 3) {
	$src = wp_get_attachment_image_src($img_id, 'full');	
	
	// if gif or png - return original image
	if(substr(strtolower($src[0]), -4) == '.gif' || substr(strtolower($src[0]), -4) == '.png') {
		return $src[0];	
	}
	
	$max_w = get_option('mg_item_maxwidth', 960);
	$canvas_color = substr(get_option('mg_item_bg_color', '#ffffff'), 1);
	
	// max-width if text on side
	if($layout == 'side' && ($max_w * 0.68) > 760) {	
		$max_w = ceil($max_w * 0.68);
	}
		
	$img_w = ($max_w > 0 && $src[1] > $max_w) ? $max_w : false;
	$img_h = ($max_h && $src[2] > $max_h) ? $max_h : false;
	
	// if image is smaller - return it
	if(!$img_w && !$img_h) {return $src[0];}
	
	// return thumb
	return  mg_thumb_src($img_id, $img_w, $img_h, $quality = 95, $thumb_center, $resize, $canvas_color);
}


// lightbox navigation code
function mg_lb_nav_code($prev_next = array('prev' => 0, 'next' => 0), $layout = 'inside') {
	if((!$prev_next['prev'] && !$prev_next['next']) || $layout == 'hidden') {return '';}
	
	// thumb sizes for layout
	switch($layout) {
		case 'inside' 	: $ts = array('w'=>60, 'h'=>60); break;	
		case 'top' 		: $ts = array('w'=>150, 'h'=>150); break;
		case 'side' 	: $ts = array('w'=>340, 'h'=>120); break;
	}
	
	$code = '';
	foreach($prev_next as $dir => $item_id) {
		$active = (!empty($item_id)) ? 'mg_nav_active' : '';
		$side_class = ($layout == 'side') ? 'mg_side_nav' : '';
		$side_vis = ($layout == 'side') ? 'style="display: none;"' : '';
		$thumb_center = (get_post_meta($item_id, 'mg_thumb_center', true)) ? get_post_meta($item_id, 'mg_thumb_center', true) : 'c';
		
		$code .= '
		<div class="mg_nav_'.$dir.' mg_'.$layout.'_nav_'.$dir.' '.$active.' '.$side_class.'" rel="'.$item_id.'" '.$side_vis.'>
			<i></i>';
			
			if($layout == 'side') {
				$code .= '<span></span>';	
			}
			
			if(!empty($item_id)) {
				$title = get_the_title($item_id);
				
				if($layout == 'inside') {
					$code .= '<div>'.$title.'</div>';
				}
				elseif($layout == 'top') {
					$thumb = mg_thumb_src(get_post_thumbnail_id($item_id), $ts['w'], $ts['h'], 80, $thumb_center);
					$code .= '<div>'.$title.'<img src="'.$thumb.'" alt="'.mg_sanitize_input($title).'" /></div>';
				}
				elseif($layout == 'side') {
					$thumb = mg_thumb_src(get_post_thumbnail_id($item_id), $ts['w'], $ts['h'], 70, $thumb_center);
					$code .= '<div>'.$title.'</div><img src="'.$thumb.'" alt="'.mg_sanitize_input($title).'" />';
				}
			}
			
		$code .= '</div>';
	}	
	return $code;
}


// get the upload directory (for WP MU)
function mg_wpmu_upload_dir() {
	$dirs = wp_upload_dir();
	$basedir = $dirs['basedir'] . '/YEAR/MONTH';
	 
	 
	return $basedir;	
}

///////////////////////////////////////////////////////////////////


// predefined grid styles 
function mg_predefined_styles($style = '') {
	$styles = array(
		/*** LIGHTS ***/
		'Light - Standard' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 4,
			'mg_cells_radius' => 1,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 3,
			'mg_item_radius' => 3, 
			
			'mg_loader_color' => '#888888',
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => '#ffffff',
			'mg_img_border_opacity' => 100,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => '#ffffff',
			'mg_overlay_title_color' => '#222222',
			'mg_txt_under_color' => '#333333',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_border_color' => '#e2e2e2',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => '#555555',
			'mg_item_icons_color' => '#777777',
			
			'mg_filters_txt_color' => '#666666', 
			'mg_filters_bg_color' => '#ffffff',
			'mg_filters_border_color' => '#bbbbbb', 
			'mg_filters_txt_color_h' => '#535353', 
			'mg_filters_bg_color_h' => '#fdfdfd', 
			'mg_filters_border_color_h' => '#777777',
			'mg_filters_txt_color_sel' => '#333333', 
			'mg_filters_bg_color_sel' => '#e5e5e5', 
			'mg_filters_border_color_sel' => '#aaaaaa',
			
			'preview' => 'light_standard.jpg'
		),
	
		'Light - Minimal' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 3,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 1,
			'mg_cells_shadow' => 0,
			'mg_item_radius' => 2,
			'mg_lb_border_w' => 0,
			'mg_item_radius' => 0,
			
			'mg_loader_color' => '#888888',
			'mg_cells_border_color' => '#CECECE',
			'mg_img_border_color' => '#ffffff',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => '#ffffff',
			'mg_overlay_title_color' => '#222222',
			'mg_txt_under_color' => '#333333',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_border_color' => '#e2e2e2',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => '#444444',
			'mg_item_icons_color' => '#666666',
			
			'mg_filters_txt_color' => '#666666', 
			'mg_filters_bg_color' => '#ffffff',
			'mg_filters_border_color' => '#bbbbbb', 
			'mg_filters_txt_color_h' => '#535353', 
			'mg_filters_bg_color_h' => '#fdfdfd', 
			'mg_filters_border_color_h' => '#777777',
			'mg_filters_txt_color_sel' => '#333333', 
			'mg_filters_bg_color_sel' => '#e5e5e5', 
			'mg_filters_border_color_sel' => '#aaaaaa',
			
			'preview' => 'light_minimal.jpg'
		),
		
		'Light - No Border' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 0,
			'mg_item_radius' => 2,
			
			'mg_loader_color' => '#888888',
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => '#ffffff',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => '#efefef',
			'mg_overlay_title_color' => '#222222',
			'mg_txt_under_color' => '#333333',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_border_color' => '#e2e2e2',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => '#555555',
			'mg_item_icons_color' => '#666666',
			
			'mg_filters_txt_color' => '#666666', 
			'mg_filters_bg_color' => '#ffffff',
			'mg_filters_border_color' => '#bbbbbb', 
			'mg_filters_txt_color_h' => '#535353', 
			'mg_filters_bg_color_h' => '#fdfdfd', 
			'mg_filters_border_color_h' => '#777777',
			'mg_filters_txt_color_sel' => '#333333', 
			'mg_filters_bg_color_sel' => '#e5e5e5', 
			'mg_filters_border_color_sel' => '#aaaaaa',
			
			'preview' => 'light_noborder.jpg'
		),
		
		'Light - Photo Wall' => array(
			'mg_cells_margin' => 0,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 0,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 3,
			'mg_item_radius' => 0,
			
			'mg_loader_color' => '#888888',
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => '#ffffff',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => '#efefef',
			'mg_overlay_title_color' => '#222222',
			'mg_txt_under_color' => '#333333',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_border_color' => '#e2e2e2',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => '#444444',
			'mg_item_icons_color' => '#777777',
			
			'mg_filters_txt_color' => '#666666', 
			'mg_filters_bg_color' => '#ffffff',
			'mg_filters_border_color' => '#bbbbbb', 
			'mg_filters_txt_color_h' => '#535353', 
			'mg_filters_bg_color_h' => '#fdfdfd', 
			'mg_filters_border_color_h' => '#777777',
			'mg_filters_txt_color_sel' => '#333333', 
			'mg_filters_bg_color_sel' => '#e5e5e5', 
			'mg_filters_border_color_sel' => '#aaaaaa',
			
			'preview' => 'light_photowall.jpg'
		),
		
		'Light - Title Under Items' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 3,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 1,
			'mg_cells_shadow' => 0,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 3,
			'mg_item_radius' => 2,
			
			'mg_loader_color' => '#888888',
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => '#ffffff',
			'mg_img_border_opacity' => 100,
			'mg_main_overlay_color' => '#dddddd',
			'mg_main_overlay_opacity' => 0,
			'mg_second_overlay_color' => '#ffffff',
			'mg_icons_col' => '#777777',
			'mg_overlay_title_color' => '#222222',
			'mg_txt_under_color' => '#333333',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_border_color' => '#e2e2e2',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => '#555555',
			'mg_item_icons_color' => '#777777',
			
			'mg_filters_txt_color' => '#666666', 
			'mg_filters_bg_color' => '#ffffff',
			'mg_filters_border_color' => '#bbbbbb', 
			'mg_filters_txt_color_h' => '#535353', 
			'mg_filters_bg_color_h' => '#fdfdfd', 
			'mg_filters_border_color_h' => '#777777',
			'mg_filters_txt_color_sel' => '#333333', 
			'mg_filters_bg_color_sel' => '#e5e5e5', 
			'mg_filters_border_color_sel' => '#aaaaaa',
			
			'preview' => 'light_tit_under.jpg'
		),
	
		/*** DARKS ***/
		'Dark - Standard' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 4,
			'mg_cells_radius' => 1,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 3,
			'mg_item_radius' => 3, 
			
			'mg_loader_color' => '#ffffff',
			'mg_cells_border_color' => '#999999',
			'mg_img_border_color' => '#373737',
			'mg_img_border_opacity' => 80,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => '#ffffff',
			'mg_overlay_title_color' => '#ffffff',
			'mg_txt_under_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_border_color' => '#5f5f5f',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => '#eeeeee',
			'mg_item_icons_color' => '#eeeeee',
			
			'mg_filters_txt_color' => '#efefef', 
			'mg_filters_bg_color' => '#6a6a6a',
			'mg_filters_border_color' => '#666666', 
			'mg_filters_txt_color_h' => '#ffffff', 
			'mg_filters_bg_color_h' => '#5f5f5f', 
			'mg_filters_border_color_h' => '#444444',
			'mg_filters_txt_color_sel' => '#ffffff', 
			'mg_filters_bg_color_sel' => '#4f4f4f', 
			'mg_filters_border_color_sel' => '#424242',
			
			'preview' => 'dark_standard.jpg'
		),
	
		'Dark - Minimal' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 4,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 1,
			'mg_cells_shadow' => 0,
			'mg_item_radius' => 2,
			'mg_lb_border_w' => 0,
			'mg_item_radius' => 0,
			
			'mg_loader_color' => '#ffffff',
			'mg_cells_border_color' => '#555555',
			'mg_img_border_color' => '#373737',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => '#ffffff',
			'mg_overlay_title_color' => '#ffffff',
			'mg_txt_under_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_border_color' => '#5f5f5f',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => '#ffffff',
			'mg_item_icons_color' => '#ffffff',
			
			'mg_filters_txt_color' => '#efefef', 
			'mg_filters_bg_color' => '#6a6a6a',
			'mg_filters_border_color' => '#666666', 
			'mg_filters_txt_color_h' => '#ffffff', 
			'mg_filters_bg_color_h' => '#5f5f5f', 
			'mg_filters_border_color_h' => '#444444',
			'mg_filters_txt_color_sel' => '#ffffff', 
			'mg_filters_bg_color_sel' => '#4f4f4f', 
			'mg_filters_border_color_sel' => '#424242',
			
			'preview' => 'dark_minimal.jpg'
		),
		
		'Dark - No Border' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 0,
			'mg_item_radius' => 2,
			
			'mg_loader_color' => '#ffffff',
			'mg_cells_border_color' => '#999999',
			'mg_img_border_color' => '#373737',
			'mg_img_border_opacity' => 80,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => '#555555',
			'mg_overlay_title_color' => '#ffffff',
			'mg_txt_under_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_border_color' => '#5f5f5f',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => '#f4f4f4',
			'mg_item_icons_color' => '#eeeeee',
			
			'mg_filters_txt_color' => '#efefef', 
			'mg_filters_bg_color' => '#6a6a6a',
			'mg_filters_border_color' => '#666666', 
			'mg_filters_txt_color_h' => '#ffffff', 
			'mg_filters_bg_color_h' => '#5f5f5f', 
			'mg_filters_border_color_h' => '#444444',
			'mg_filters_txt_color_sel' => '#ffffff', 
			'mg_filters_bg_color_sel' => '#4f4f4f', 
			'mg_filters_border_color_sel' => '#424242',
			
			'preview' => 'dark_noborder.jpg'
		),
		
		'Dark - Photo Wall' => array(
			'mg_cells_margin' => 0,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 0,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 3,
			'mg_item_radius' => 0,
			
			'mg_loader_color' => '#ffffff',
			'mg_cells_border_color' => '#999999',
			'mg_img_border_color' => '#373737',
			'mg_img_border_opacity' => 80,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => '#555555',
			'mg_overlay_title_color' => '#ffffff',
			'mg_txt_under_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_border_color' => '#5f5f5f',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => '#f4f4f4',
			'mg_item_icons_color' => '#ffffff',
			
			'mg_filters_txt_color' => '#efefef', 
			'mg_filters_bg_color' => '#6a6a6a',
			'mg_filters_border_color' => '#666666', 
			'mg_filters_txt_color_h' => '#ffffff', 
			'mg_filters_bg_color_h' => '#5f5f5f', 
			'mg_filters_border_color_h' => '#444444',
			'mg_filters_txt_color_sel' => '#ffffff', 
			'mg_filters_bg_color_sel' => '#4f4f4f', 
			'mg_filters_border_color_sel' => '#424242',
			
			'preview' => 'dark_photowall.jpg'
		),
		
		'Dark - Title Under Items' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 3,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 1,
			'mg_cells_shadow' => 0,
			'mg_item_radius' => 4,
			'mg_lb_border_w' => 3,
			'mg_item_radius' => 2,
			
			'mg_loader_color' => '#ffffff',
			'mg_cells_border_color' => '#ffffff',
			'mg_img_border_color' => '#3a3a3a',
			'mg_img_border_opacity' => 100,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 0,
			'mg_second_overlay_color' => '#9b9b9b',
			'mg_icons_col' => '#666666',
			'mg_overlay_title_color' => '#ffffff',
			'mg_txt_under_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_border_color' => '#5f5f5f',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => '#f4f4f4',
			'mg_item_icons_color' => '#eeeeee',
			
			'mg_filters_txt_color' => '#efefef', 
			'mg_filters_bg_color' => '#6a6a6a',
			'mg_filters_border_color' => '#666666', 
			'mg_filters_txt_color_h' => '#ffffff', 
			'mg_filters_bg_color_h' => '#5f5f5f', 
			'mg_filters_border_color_h' => '#444444',
			'mg_filters_txt_color_sel' => '#ffffff', 
			'mg_filters_bg_color_sel' => '#4f4f4f', 
			'mg_filters_border_color_sel' => '#424242',
			
			'preview' => 'dark_tit_under.jpg'
		),
	);
		
		
	if($style == '') {return $styles;}
	else {return $styles[$style];}	
}

?>
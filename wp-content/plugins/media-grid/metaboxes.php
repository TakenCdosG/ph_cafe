<?php
// METABOXES FOR THE ITEMS

// register
function mg_register_metaboxes() {
	add_meta_box('mg_thumb_center_box', __('Thumbnail Center', 'mg_ml'), 'mg_thumb_center_box', 'mg_items', 'side', 'low');
	add_meta_box('mg_item_opt_box', __('Item Options', 'mg_ml'), 'mg_item_opt_box', 'mg_items', 'normal', 'default');
}
add_action('admin_init', 'mg_register_metaboxes');


//////////////////////////
// THUMBNAIL CENTER

function mg_thumb_center_box() {
	require_once(MG_DIR . '/functions.php');	
	global $post;
	
	$tc = get_post_meta($post->ID, 'mg_thumb_center', true);
	if(!$tc) {$tc = 'c';}

	// array of sizes 
	$vals = mg_sizes();
	?>
    <div class="lcwp_sidebox_meta">
        <div class="misc-pub-section">
          <input type="hidden" value="<?php echo $tc; ?>" name="mg_thumb_center" id="mg_thumb_center" />
                
          <table class="mg_sel_thumb_center">
            <tr>
                <td id="mg_tl"></td>
                <td id="mg_t"></td>
                <td id="mg_tr"></td>
            </tr>
            <tr>
                <td id="mg_l"></td>
                <td id="mg_c"></td>
                <td id="mg_r"></td>
            </tr>
            <tr>
                <td id="mg_bl"></td>
                <td id="mg_b"></td>
                <td id="mg_br"></td>
            </tr>
          </table>
        </div>
    </div>

    <script type="text/javascript">
	jQuery(document).ready(function() {
		function mg_thumb_center(position) {
			jQuery('.mg_sel_thumb_center td').removeClass('thumb_center');
			jQuery('.mg_sel_thumb_center #mg_'+position).addClass('thumb_center');
			
			jQuery('#mg_thumb_center').val(position);	
		}
		mg_thumb_center( jQuery('#mg_thumb_center').val() );
		
		jQuery('body').delegate('.mg_sel_thumb_center td', 'click', function() {
			var new_position = jQuery(this).attr('id').substr(3);
			mg_thumb_center(new_position);
		});		
	});
    </script>
 
	<?php
	return true;	
}



//////////////////////////
// ITEM OPTIONS

function mg_item_opt_box() {
	require_once(MG_DIR . '/functions.php');
	global $post;
	
	$main_type = get_post_meta($post->ID, 'mg_main_type', true);
	if(!$main_type) {$main_type = '';}
	
	$item_layout = get_post_meta($post->ID, 'mg_layout', true);
	$lb_maxwidth = get_post_meta($post->ID, 'mg_lb_max_w', true);
	$img_maxheight = get_post_meta($post->ID, 'mg_img_maxheight', true);
	?>
    <div class="lcwp_mainbox_meta">
      <table class="widefat lcwp_table lcwp_metabox_table">
        <tr>
          <td class="lcwp_label_td"><?php _e("Item Type", 'mg_ml'); ?></td>
          <td class="lcwp_field_td">
              <select data-placeholder="<?php _e('Select a type', 'mg_ml') ?> .." name="mg_main_type" id="mg_main_type" class="lcweb-chosen" autocomplete="off">
                <?php 
                $types = mg_item_types();
				if(isset($types['woocom'])) {unset( $types['woocom'] );}
				
				foreach($types as $key => $val) {
                    ($key == $main_type) ? $sel = 'selected="selected"' : $sel = '';
                    echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>'; 
                }
                ?>
              </select>
          </td>     
          <td><span class="info"><?php _e('Choose the item type', 'mg_ml') ?></span></td>
        </tr>
      </table>  
      
      <div id="mg_layout_wrap" <?php if(!$main_type || in_array($main_type, array('simple_img','link','lb_text','inl_slider','inl_video','inl_text','spacer'))) echo 'style="display: none;"' ?>>
        <table class="widefat lcwp_table lcwp_metabox_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Lightbox Layout", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e('Select a layout', 'mg_ml') ?> .." name="mg_layout" id="mg_layout" class="lcweb-chosen" autocomplete="off">
                  <option value="full"><?php _e('Full Width', 'mg_ml') ?></option>
                  <option value="side" <?php if($item_layout == 'side') echo 'selected="selected"';?>><?php _e('Text on side', 'mg_ml') ?></option>
                </select>
            </td>     
            <td><span class="info"></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Lightbox content max-width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" name="mg_lb_max_w" value="<?php echo ((int)$lb_maxwidth == 0) ? '' : $lb_maxwidth; ?>" maxlength="4" style="width: 50px;"  /> px
            </td>     
            <td><span class="info"><?php _e('Leave blank to fit the content to the lightbox size', 'mg_ml') ?></span></td>
          </tr>
        </table>  
      </div>
      
      <div id="mg_img_maxheight_wrap" <?php if(!in_array($main_type, array('single_img', 'audio'))) echo 'style="display: none;"' ?>>
      	<h4><?php _e('Full-size image control', 'mg_ml') ?></h4>
        <table class="widefat lcwp_table lcwp_metabox_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Full image max-height", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="text" name="mg_img_maxheight" value="<?php echo ((int)$img_maxheight == 0) ? '' : $img_maxheight; ?>" maxlength="4" style="width: 50px;"  /> px
            </td>     
            <td><span class="info"><?php _e('Leave blank to use the full-size image', 'mg_ml') ?></span></td>
          </tr>
        </table>  
      </div>
      
      
     <?php // USER CUSTOM OPTIONS ////////// ?> 
      <div id="mg_cust_opt_wrap">
      	<div id="mg_cust_opt_img" <?php if($main_type != 'single_img') {echo 'style="display: none;"';} ?>>
        	<?php echo mg_get_type_opt_fields('image', $post); ?>
        </div>
        
        <div id="mg_cust_opt_img_gallery" <?php if($main_type != 'img_gallery') {echo 'style="display: none;"';} ?>>
        	<?php echo mg_get_type_opt_fields('img_gallery', $post); ?>
        </div>
        
        <div id="mg_cust_opt_video" <?php if($main_type!='video') {echo 'style="display: none;"';} ?>>
        	<?php echo mg_get_type_opt_fields('video', $post); ?>
        </div>
        
        <div id="mg_cust_opt_audio" <?php if($main_type!='audio') {echo 'style="display: none;"';} ?>>
        	<?php echo mg_get_type_opt_fields('audio', $post); ?>
        </div>
      </div>
      
      
      <?php // TYPE OPTIONS ////////// ?> 
      <div id="mg_builder_wrap">
          
          <?php // static image options ?>
          <div id="mg_builder_simple_img" class="lcwp_form" <?php if($main_type && $main_type != 'simple_img') echo 'style="display: none;"' ?>>	
          	<?php echo mg_meta_opt_generator('simple_img', $post); ?>
          </div>
          
		  <?php // image gallery builder ?>
          <div id="mg_builder_img_gallery" <?php if(!in_array($main_type, array('img_gallery', 'inl_slider'))) echo 'style="display: none;"' ?>>	
			  <?php 
              $images = mg_existing_sel(get_post_meta($post->ID, 'mg_slider_img', true)); 
              
              $h_val = get_post_meta($post->ID, 'mg_slider_w_val', true);
              $h_type = get_post_meta($post->ID, 'mg_slider_w_type', true);
              
              if(!$h_val) {$h_val = get_option('mg_slider_main_w_val', 55);}
              if(!$h_type) {$h_type = $def_h_type = get_option('mg_slider_main_w_type', '%');}
              ?>
              
              <div id="mg_lb_slider_opt" <?php if($main_type != 'img_gallery') echo 'style="display: none;"' ?>>
                  <h4><?php _e('Slider Options', 'mg_ml') ?></h4>
                  <table class="widefat lcwp_table lcwp_metabox_table" style="margin-bottom: 0px;">
                    <tr>
                      <td class="lcwp_label_td"><?php _e("Height", 'mg_ml'); ?></td>
                      <td class="lcwp_field_td">
                          <input type="text" class="lcwp_slider_input" name="mg_slider_w_val" value="<?php echo $h_val; ?>" maxlength="3" style="width: 50px; display: inline-block; text-align: center;" >
                          <select name="mg_slider_w_type" style="width: 50px; margin-left: -5px;" autocomplete="off">
                            <option value="%">%</option>
                            <option value="px" <?php if($h_type == 'px') {echo 'selected="selected"';} ?>>px</option>
                          </select>  
                      </td>
                      <td><span class="info"><?php _e("Slider height (% is related to its width)", 'mg_ml'); ?></span></td>
                    </tr>
                  </table>
                  
                  <div style="border-bottom: 1px solid #DFDFDF; border-top: 1px solid #DFDFDF; margin-bottom: 17px;" class="lcwp_form">
                  <?php echo mg_meta_opt_generator('img_gallery', $post); ?>
                  </div>
              </div>
              
              
              <div id="mg_inl_slider_opt" <?php if($main_type != 'inl_slider') echo 'style="display: none;"' ?> class="lcwp_form">
                  <h4><?php _e('Slider Options', 'mg_ml') ?></h4>
                  <?php echo mg_meta_opt_generator('inl_slider', $post); ?>
              </div>
              
              
              <h4><?php _e('Slider Images', 'mg_ml') ?></h4>
              <div id="gallery_img_wrap">
                  <ul>
                  <?php 
                  if(is_array($images)) {
                      foreach($images as $img_id) {
                          echo '
                          <li>
                              <input type="hidden" name="mg_slider_img[]" value="'.$img_id.'" />
                              <img src="'. mg_thumb_src($img_id, 90, 90) .'" />
                              <span title="remove image"></span>
                          </li>';			
                      }
                  }
                  else {echo '<p>'. __('No images selected', 'mg_ml') .' .. </p>';}
                  ?>
                  </ul>	
                  <br class="lcwp_clear">
              </div>
              <div style="clear: both; height: 20px;"></div>
              
              <div id="mg_img_search_wrap">
                  <input type="text" placeholder="<?php _e('search', 'mg_ml') ?> .." class="mg_search_field"  />
                  <span class="mg_search_btn" title="search"></span>
              </div>
              <h4><?php _e('Choose images', 'mg_ml') ?> <span class="mg_TB mg_upload_img add-new-h2"><?php _e('Manage Images', 'mg_ml') ?></span></h4>
              <div id="gallery_img_picker"></div>	
          </div>
          
          <?php // video builder ?>
          <div id="mg_builder_video" class="lcwp_form" <?php if($main_type != 'video' && $main_type != 'inl_video') echo 'style="display: none;"' ?>>
              <h4><?php _e('Video Options', 'mg_ml') ?></h4>
              <table class="widefat lcwp_table lcwp_metabox_table">
                <tr>
                  <td class="lcwp_label_td"><?php _e('Video URL', 'mg_ml') ?></td>
                  <td colspan="2">
                  	<input type="text" value="<?php echo get_post_meta($post->ID, 'mg_video_url', true); ?>" name="mg_video_url" style="width: 60%; min-width: 217px;" /> 
					
					<?php // self hosted video - lightbox trigger
					if((float)substr(get_bloginfo('version'), 0, 3) >= 3.6 && $main_type == 'video') : ?>
                    	<img src="<?php echo MG_URL ?>/img/media-library-src.png" title="search in media library" style="margin: 0 0 -6px 1px; cursor: pointer;" id="mg_video_src" />
					<?php endif; ?><br/>
                    
                    <span class="info"><?php _e('Insert Youtube (<strong>http://youtu.be</strong>), Vimeo or Dailymotion clean video url', 'mg_ml') ?>.</span> 
					<span class="info lb_video_info" <?php if($main_type != 'video') echo 'style="display: none;"' ?>>
						<?php if((float)substr(get_bloginfo('version'), 0, 3) >= 3.6) { _e('Otherwise select a video from your media library', 'mg_ml'); }; ?>
                    </span>
                  </td>
                </tr>
                <tr>
                  <td class="lcwp_label_td"><?php _e("Use featured image as video poster?", 'mg_ml'); ?></td>
                  <td class="lcwp_field_td">
                      <?php (get_post_meta($post->ID, 'mg_video_use_poster', true) == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                      <input type="checkbox" value="1" name="mg_video_use_poster" class="ip-checkbox" <?php echo $sel; ?> />
                  </td>
                  <td>
                      <span class="info"><?php _e('If checked, set the featured image as poster', 'mg_ml') ?></span>
                  </td>
                </tr>
              </table>
          </div>
          
          <?php // audio builder ?>
          <div id="mg_builder_audio" <?php if($main_type != 'audio') {echo 'style="display: none;"';} ?>>
			 <h4><?php _e('Audio Options', 'mg_ml') ?></h4>
              <table class="widefat lcwp_table lcwp_metabox_table">
                <tr>
                  <td class="lcwp_label_td"><?php _e('Soundcloud track URL', 'mg_ml') ?></td>
                  <td class="lcwp_field_td">
                  	<input type="text" value="<?php echo get_post_meta($post->ID, 'mg_soundcloud_url', true); ?>" name="mg_soundcloud_url" /> 
                  </td>
                  <td><span class="info"><?php _e('Using this field, the selected tracklist <strong>will be ignored</strong>', 'mg_ml') ?></span></td>
                </tr>
              </table>
          
			  <?php $tracks = mg_existing_sel(get_post_meta($post->ID, 'mg_audio_tracks', true)); ?>
              
              <h4><?php _e('Tracklist', 'mg_ml') ?></h4>
              <div id="audio_tracks_wrap">
                  <ul>
                  <?php
                  if(is_array($tracks)) {
                      foreach($tracks as $track_id) {
						  $track_title =  html_entity_decode(get_the_title($track_id), ENT_NOQUOTES, 'UTF-8');
                          
						  // if WP > 3.9 use iconic font
						  if( (float)substr(get_bloginfo('version'), 0, 3) >= 3.9) {
							  $icon = '<div class="mg_audio_icon dashicons-media-audio dashicons"></div>';
						  } else {
							  $icon = '<img src="'.MG_URL . '/img/audio_icon.png" />';	
						  }

						  echo '
						  <li id="mgtl_'. $track_id .'">
							  <input type="hidden" name="mg_audio_tracks[]" value="'. $track_id .'" />
							  '.$icon.'
							  <span title="remove track"></span>
							  <p title="'.$track_title.'">'.mg_excerpt($track_title, 25).'</p>
						  </li>';			
                      }
                  }
				  else {echo '<p>'. __('No tracks selected', 'mg_ml') .' .. </p>';}
                  ?>
                  </ul>	
                  <br class="lcwp_clear" />
              </div>
              <div style="clear: both; height: 20px;"></div>
              
              <div id="mg_audio_search_wrap">
                <input type="text" placeholder="<?php _e('search', 'mg_ml') ?> .." class="mg_search_field"  />
                <span class="mg_search_btn" title="search"></span>
              </div>
              <h4><?php _e('Choose tracks', 'mg_ml') ?> <span class="mg_TB mg_upload_audio add-new-h2"><?php _e('Manage Tracks', 'mg_ml') ?></span></h4>
              <div id="audio_tracks_picker"></div>	
          </div>
          
          
          <?php // link builder ?>
          <div id="mg_builder_link" <?php if($main_type != 'link') {echo 'style="display: none;"';} ?>>
              <h4><?php _e('Link Options', 'mg_ml') ?></h4>
              <?php echo mg_meta_opt_generator('link', $post); ?>
          </div>
          
          
          <?php // inline text options ?>
          <div id="mg_builder_inl_text" class="lcwp_form" <?php if($main_type != 'inl_text') {echo 'style="display: none;"';} ?>>
              <h4><?php _e('Inline Text Options', 'mg_ml') ?></h4>
              <?php echo mg_meta_opt_generator('inl_text', $post); ?>
          </div>
      </div>
    </div>
    
    <?php // security nonce ?>
    <input type="hidden" name="mg_item_noncename" value="<?php echo wp_create_nonce('lcwp_nonce') ?>" />
    
    <?php // ////////////////////// ?>
    
    <?php // SCRIPTS ?>
    <script src="<?php echo MG_URL; ?>/js/colpick/js/colpick.min.js" type="text/javascript"></script>
    <script src="<?php echo MG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo MG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">
	jQuery(document).ready(function($) {
		
		///////////////////////////
		// live preview link
		<?php 
		if(!get_option('mg_disable_dl') && !empty($main_type) && !in_array($main_type, array('simple_img','link','inl_slider','inl_video','inl_text','spacer'))) : 
		?>
			var mg_live_preview = '<div class="misc-pub-section-last">\
				<a href="<?php echo site_url(); ?>?_escaped_fragment_=mg_ld=<?php echo $post->ID; ?>" target="_blank" id="mg_item_preview_link"><?php echo mg_sanitize_input( __("Item's lightbox preview", 'mg_ml')) ?> &raquo;</a></div>';
			
			jQuery('#submitpost').parent().append(mg_live_preview);
			jQuery('#major-publishing-actions').addClass('misc-pub-section');
		<?php endif; ?>
		
			
		// video upload and select
		jQuery(document).delegate('#mg_video_src', "click", function (e) {
			e.preventDefault();
			
			var wp_selector = wp.media({
				title: 'WP Videos Management',
				button: { text: '<?php echo mg_sanitize_input( __('Select')) ?>' },
				library : { type : 'video'},
				multiple: false
			})
			.on('select', function() {
				var selection = wp_selector.state().get('selection').first().toJSON();

				var itemurl = selection.url;
				var video_pattern = /(^.*\.mp4|m4v|webm|ogv|wmv|flv*)/gi;
	  
				if(itemurl.match(video_pattern) ) {
				  jQuery('#mg_video_src').siblings('input[type=text]').val(itemurl);
				}
				else { alert('<?php echo mg_sanitize_input( __('Please select a valid video file for the WP player. Supported extensions:', 'mg_ml')); ?> mp4, m4v, webm, ogv, wmv, flv'); }
			})
			.open();
		});
		
		
		////////////////////////
		// custom file uploader for gallery and audio
		mg_TB = 0;
		
		// open tb and hide tabs
		jQuery('body').delegate('.mg_TB', 'click', function(e) {
			
			if( jQuery(this).hasClass('mg_upload_img') ) {mg_TB_type = 'img';}
			else {mg_TB_type = 'audio';}
			
			// thickBox
			if(typeof(wp.media) == 'undefined') {
				mg_TB = 1;
				post_id = jQuery('#post_ID').val();
				
				if(mg_TB_type == 'img') {
					tb_show('', '<?php echo admin_url(); ?>media-upload.php?post_id='+post_id+'&amp;type=image&amp;TB_iframe=true');
				}
				else {
					tb_show('', '<?php echo admin_url(); ?>media-upload.php?post_id='+post_id+'&amp;type=audio&amp;TB_iframe=true');	
				}
				
				setInterval(function() {
					if(mg_TB == 1) {
						if( jQuery('#TB_iframeContent').contents().find('#tab-type_url').is('hidden') ) { return false;	}
						
						jQuery('#TB_iframeContent').contents().find('#tab-type_url').hide();
						jQuery('#TB_iframeContent').contents().find('#tab-gallery').hide();
					}
				}, 1);
			}
			
			// new lightbox management
			else {
				e.preventDefault();
				var title = (mg_TB_type == 'img') ? 'Images' : 'Audio';
				var subj = (mg_TB_type == 'img') ? 'image' : 'audio';
				
				var custom_uploader = wp.media({
					title: 'WP '+ title +' Management',
					button: { text: 'Ok' },
					library : { type : subj},
					multiple: false
				})
				.on('select close', function() {
					if(mg_TB_type == 'img') { 
						mg_load_img_picker(1); 
						mg_sel_img_reload();
					}
					else {
						mg_load_audio_picker(1);	
						mg_sel_tracks_reload();
					}
				})
				.open();	
			}
		});

		// reload picker on thickbox unload
		jQuery(window).bind('tb_unload', function() {
			if(mg_TB == 1) {
				if(mg_TB_type == 'img') { 
					mg_load_img_picker(1); 
					mg_sel_img_reload();
				}
				else {
					mg_load_audio_picker(1);	
					mg_sel_tracks_reload();
				}
				
				mg_TB = 0;		
			}
		});

		
		////////////////////////
		// audio
		mg_audio_pp = 15;
		mg_load_audio_picker(1);
		
		// reload the selected tracks to refresh their titles
		function mg_sel_tracks_reload() {
			var sel_tracks = jQuery.makeArray();	
			
			jQuery('#audio_tracks_wrap li').each(function() {
                var track_id = jQuery(this).children('input').val();
           		sel_tracks.push(track_id);
			});
			
			jQuery('#audio_tracks_wrap ul').html('<div style="height: 30px;" class="lcwp_loading"></div>');
			
			var data = {
				action: 'mg_sel_audio_reload',
				tracks: sel_tracks
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#audio_tracks_wrap ul').html(response);
			});	
		}
		
		// change tracks picker page
		jQuery('body').delegate('.mg_audio_pick_back, .mg_audio_pick_next', 'click', function() {
			var page = jQuery(this).attr('id').substr(4);
			mg_load_audio_picker(page);
		});
		
		// change tracks per page
		jQuery('body').delegate('#mg_audio_pick_pp', 'change', function() {
			var pp = jQuery(this).val();
			
			if( pp.length >= 2 ) {
				if( parseInt(pp) < 15 ) { mg_audio_pp = 15;}
				else {mg_audio_pp = pp;}
				
				mg_load_audio_picker(1);
			}
		});
		
		// on search
		jQuery('body').delegate('#mg_audio_search_wrap .mg_search_btn', 'click', function() {
			mg_load_audio_picker(1);
		});
		
		// load audio tracks picker
		function mg_load_audio_picker(page) {
			var data = {
				action: 'mg_audio_picker',
				page: page,
				per_page: mg_audio_pp,
				mg_search: jQuery('#mg_audio_search_wrap .mg_search_field').val()
			};
			
			jQuery('#audio_tracks_picker').html('<div style="height: 30px;" class="lcwp_loading"></div>');
			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#audio_tracks_picker').html(response);
			});	
			
			return true;
		}
		
		// add audio track
		jQuery('body').delegate('#audio_tracks_picker li', 'click', function() {
			var track_id = jQuery(this).attr('id').substr(5);
			var track_tit = jQuery(this).children('p').text();	
			
			if( jQuery('#audio_tracks_wrap ul > p').size() > 0 ) {jQuery('#audio_tracks_wrap ul').empty();}
			
			<?php 
			// if WP > 3.9 use iconic font
			if( (float)substr(get_bloginfo('version'), 0, 3) >= 3.9) {
				$icon = '<div class="mg_audio_icon dashicons-media-audio dashicons"></div>';
			} else {
				$icon = '<img src="'.MG_URL . '/img/audio_icon.png" />';	
			}
			?>
			
			if( jQuery('#audio_tracks_wrap li#mgtl_'+ track_id).size() == 0) { 
				jQuery('#audio_tracks_wrap ul').append('\
				<li id="mgtl_'+ track_id +'">\
					<input type="hidden" name="mg_audio_tracks[]" value="'+ track_id +'" />\
					<?php echo $icon ?>\
					<span title="remove track"></span>\
					<p>'+ track_tit +'</p>\
				</li>');
				
				mg_sort();
			}
		});
		

		////////////////////////
		// images
		mg_img_pp = 15;
		mg_load_img_picker(1);
		
		// reload the selected images to check changes
		function mg_sel_img_reload() {
			var sel_img = jQuery.makeArray();	
			
			jQuery('#gallery_img_wrap li').each(function() {
                var img_id = jQuery(this).children('input').val();
           		sel_img.push(img_id);
			});
			
			jQuery('#gallery_img_wrap ul').html('<div style="height: 30px;" class="lcwp_loading"></div>');
			
			var data = {
				action: 'mg_sel_img_reload',
				images: sel_img
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#gallery_img_wrap ul').html(response);
			});	
		}
		
		// change slider imges picker page
		jQuery('body').delegate('.mg_img_pick_back, .mg_img_pick_next', 'click', function() {
			var page = jQuery(this).attr('id').substr(4);
			mg_load_img_picker(page);
		});
		
		// change images per page
		jQuery('body').delegate('#mg_img_pick_pp', 'change', function() {
			var pp = jQuery(this).val();
			
			if( pp.length >= 2 ) {
				if( parseInt(pp) < 15 ) { mg_img_pp = 15;}
				else {mg_img_pp = pp;}
				
				mg_load_img_picker(1);
			}
		});
		
		// on search
		jQuery('body').delegate('#mg_img_search_wrap .mg_search_btn', 'click', function() {
			mg_load_img_picker(1);
		});
		
		// load slider images picker
		function mg_load_img_picker(page) {
			var data = {
				action: 'mg_img_picker',
				page: page,
				per_page: mg_img_pp,
				mg_search: jQuery('#mg_img_search_wrap .mg_search_field').val()
			};
			
			jQuery('#gallery_img_picker').html('<div style="height: 30px;" class="lcwp_loading"></div>');
			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#gallery_img_picker').html(response);
			});	
			
			return true;
		}
		
		// add slider images
		jQuery('body').delegate('#gallery_img_picker li', 'click', function() {
			var img_id = jQuery(this).children('img').attr('id');
			var img_url = jQuery(this).children('img').attr('src');
			
			if( jQuery('#gallery_img_wrap ul > p').size() > 0 ) {jQuery('#gallery_img_wrap ul').empty();}
			
			jQuery('#gallery_img_wrap ul').append('\
			<li>\
				<input type="hidden" name="mg_slider_img[]" value="'+ img_id +'" />\
				<img src="'+ img_url +'" />\
				<span title="remove image"></span>\
			</li>');
			
			mg_sort();
		});

		
		////////////////////////
		// images & audio
		// remove
		jQuery('body').delegate('#gallery_img_wrap ul li span, #audio_tracks_wrap ul li span', 'click', function() {
			jQuery(this).parent().remove();	
			
			if( jQuery('#gallery_img_wrap ul li').size() == 0 ) {jQuery('#gallery_img_wrap ul').html('<p><?php echo mg_sanitize_input( __('No images selected', 'mg_ml')) ?>  .. </p>');}
			if( jQuery('#audio_tracks_wrap ul li').size() == 0 ) {jQuery('#audio_tracks_wrap ul').html('<p><?php echo mg_sanitize_input( __('No tracks selected', 'mg_ml')) ?> .. </p>');}
		});
		
		
		// sort
		function mg_sort() { 
			jQuery( "#gallery_img_wrap ul, #audio_tracks_wrap ul" ).sortable();
			jQuery( "#gallery_img_wrap ul, #audio_tracks_wrap ul" ).disableSelection();
		}
		mg_sort();
		
		
		
		////////////////////////
		// toggle elements
		jQuery('body').delegate('#mg_main_type', "change", function() {
			var main_type = jQuery(this).val();
			
			// layout toggle
			if( jQuery.inArray(main_type, ['simple_img','link','lb_text','inl_slider','inl_video','inl_text','spacer']) == -1 ) { jQuery('#mg_layout_wrap').slideDown(); }
			else { jQuery('#mg_layout_wrap').slideUp(); }
			
			// full img max-height toggle
			if( jQuery.inArray(main_type, ['single_img', 'audio']) != -1) { jQuery('#mg_img_maxheight_wrap').slideDown(); }
			else { jQuery('#mg_img_maxheight_wrap').slideUp(); }
			
			// main opt toggle
			jQuery('#mg_cust_opt_wrap > div').each(function() {
				if(main_type == 'single_img') {var copt_id = 'img';}
				else {var copt_id = main_type;}

				if( jQuery(this).attr('id') == 'mg_cust_opt_' + copt_id) { jQuery(this).slideDown(); }
				else { jQuery(this).slideUp(); }
			});
				
			// type builder toggle
			jQuery('#mg_builder_wrap > div').each(function() {
                if( jQuery(this).attr('id') == 'mg_builder_' + main_type) { jQuery(this).slideDown(); }
				else { jQuery(this).slideUp(); }
            });
			
			// slider lightbox option
			if(main_type == 'img_gallery') { jQuery('#mg_lb_slider_opt').slideDown(); }
			else { jQuery('#mg_lb_slider_opt').slideUp(); }
			
			// self-hosted video trigger
			if(main_type == 'video') { jQuery('#mg_video_src, .lb_video_info').show(); }
			
			//// exception for inline video
			if(main_type == 'inl_video') {
				jQuery('#mg_builder_video').slideDown();
				jQuery('#mg_video_src, .lb_video_info').hide();
			}
			
			//// exception for inline slider
			if(main_type == 'inl_slider') {
				jQuery('#mg_builder_img_gallery, #mg_inl_slider_opt').slideDown();
			}
			else {jQuery('#mg_inl_slider_opt').slideUp();}
		});
		
		
		// fix for chosen overflow
		jQuery('#wpbody, #wpbody-content').css('overflow', 'visible');
		
		// fix for subcategories
		jQuery('#mg_item_categories-adder').remove();
	});
	</script>
       
    <?php	
	return true;	
}



//////////////////////////
// SAVING METABOXES

function mg_items_meta_save($post_id) {
	if(isset($_POST['mg_item_noncename'])) {
		// authentication checks
		if (!wp_verify_nonce($_POST['mg_item_noncename'], 'lcwp_nonce')) return $post_id;

		// check user permissions
		if ($_POST['post_type'] == 'page') {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		}
		else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		
		require_once(MG_DIR.'/functions.php');
		require_once(MG_DIR.'/classes/simple_form_validator.php');
				
		$validator = new simple_fv;
		$indexes = array();
		
		// thumb center
		$indexes[] = array('index'=>'mg_thumb_center', 'label'=>'Thumbnail Center');

		// main type and layout
		$indexes[] = array('index'=>'mg_main_type', 'label'=>'Item Type');
		$indexes[] = array('index'=>'mg_layout', 'label'=>'Display Mode');
		$indexes[] = array('index'=>'mg_lb_max_w', 'label'=>'Lightbox Max-width');
		$indexes[] = array('index'=>'mg_img_maxheight', 'label'=>'Full size image max-height');

		// user custom options
		if(is_array(mg_get_type_opt_indexes($_POST['mg_main_type']))) {
			foreach(mg_get_type_opt_indexes($_POST['mg_main_type']) as $copt) {
				$indexes[] = array('index'=>$copt, 'label'=>$copt);
			}
		}

		// types options
		$type_opt = mg_types_meta_opt($_POST['mg_main_type']);
		if($type_opt) {
			foreach($type_opt as $opt) {
				$indexes[] = $opt['validate'];	
			}
		}
		
		$validator->formHandle($indexes);
		
		$fdata = $validator->form_val;
		$error = $validator->getErrors();

		// clean data
		foreach($fdata as $key=>$val) {
			if(!is_array($val)) {
				$fdata[$key] = stripslashes($val);
			}
			else {
				$fdata[$key] = array();
				foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
			}
		}

		// save data
		foreach($fdata as $key=>$val) {
			delete_post_meta($post_id, $key);
			add_post_meta($post_id, $key, $fdata[$key], true); 
		}
		
		// update the grid categories
		mg_upd_item_upd_grids($post_id);
	}

    return $post_id;
}
add_action('save_post','mg_items_meta_save');



//////////////////////////
// WARNING IF FEATURED IMAGE IS NOT SET

add_action('admin_notices', 'mg_item_featured_image');
function mg_item_featured_image(){
	global $current_screen;
	
	if ($current_screen->id == 'mg_items' && $current_screen->parent_base == 'edit') {
     	global $post;
		$main_type = get_post_meta($post->ID, 'mg_main_type', true);

		if(!in_array($main_type, array('inl_slider','inl_video','inl_text','spacer')) && get_the_post_thumbnail($post->ID) == '') {
			echo '<div class="error"><p>'. __('Warning - This item has not a featured image', 'mg_ml') .'</p></div>';		
		}
		else {return '';}
	}
	else {return '';}
}
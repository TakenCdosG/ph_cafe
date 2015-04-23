<?php require_once(MG_DIR . '/functions.php'); ?>

<div class="wrap lcwp_form">  
	<div class="icon32"><img src="<?php echo MG_URL.'/img/mg_icon.png'; ?>" alt="mediagrid" /><br/></div>
    <?php echo '<h2 class="lcwp_page_title" style="border: none;">' . __( 'Grid Builder', 'mg_ml') . "</h2>"; ?>  

	<div id="ajax_mess"></div>
	
    
    <div id="poststuff" class="metabox-holder has-right-sidebar" style="overflow: hidden;">
    	
        <?php // SIDEBAR ?>
        <div id="side-info-column" class="inner-sidebar">
          <form class="form-wrap">	
           
            <div id="add_grid_box" class="postbox lcwp_sidebox_meta">
            	<h3 class="hndle"><?php _e('Add Grid', 'mg_ml') ?></h3> 
				<div class="inside">
                  <div class="misc-pub-section-last">
					<label><?php _e('Grid Name', 'mg_ml') ?></label>
                	<input type="text" name="mg_cells_margin" value="" id="add_grid" maxlenght="100" style="width: 180px;" />
                    <input type="button" name="add_grid_btn" id="add_grid_btn" value="<?php _e('Add', 'mg_ml') ?>" class="button-primary" style="width: 30px; margin-left: 5px;" />
                  </div>  
                </div>
            </div>
            
            <div id="man_grid_box" class="postbox lcwp_sidebox_meta">
            	<h3 class="hndle"><?php _e('Grid List', 'mg_ml') ?></h3> 
                <div id="mg_src_grid_wrap">
                	<span id="mg_src_grid_btn"></span>
                	<input type="text" name="src_grid" id="mg_src_grid" value="" />
                </div>
				<div class="inside"></div>
            </div>
            
            <div id="save_grid_box" class="postbox lcwp_sidebox_meta" style="display: none; background: none; border: none;">
            	<input type="button" name="save-grid" value="<?php _e('Save grid', 'mg_ml') ?>" class="button-primary" />
                
                <?php if(get_option('mg_preview_pag')) : ?>
                <input type="button" id="preview_grid" value="<?php _e('Preview', 'mg_ml') ?>" class="button-secondary" pv-url="<?php echo get_permalink(get_option('mg_preview_pag')) ?>" style="margin-left: 18px;" />
                <?php endif; ?>
                
                <div style="width: 30px; padding: 0 0 0 7px; float: right;"></div>
            </div>
          </form>	
            
        </div>
    	
        <?php // PAGE CONTENT ?>
        <form class="form-wrap" id="grid_items_list">  
          <div id="post-body">
          <div id="post-body-content" class="mg_grid_content">
              <p><?php _e('Select a grid', 'mg_ml') ?> ..</p>
          </div>
          </div>
        </form>
        
        <br class="clear">
    </div>
    
</div>  

<?php // SCRIPTS ?>
<script src="<?php echo MG_URL; ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/jquery.masonry.min.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>

<script type="text/javascript" charset="utf8" >
jQuery(document).ready(function($) {
	
	// var for the selected grid
	mg_sel_grid = 0;
	mg_grid_pag = 1;
	mg_mobile = false;
	
	mg_load_grids();

	boxMargin = 0;
	imgPadding = 0;
	imgBorder = 0;
	
	// set search part on load
	var src_str = '';
	jQuery('#mg_src_grid').val('');
	
	
	// preview grid
	jQuery('body').delegate('#preview_grid', "click", function() {
		var url = jQuery(this).attr('pv-url') + '?mg_preview=' + mg_sel_grid;
		window.open(url,'_blank');
	});
	
	
	// items dropdown thumbnails toggle
	jQuery('body').delegate('#mh_grid_item', "change", function() {
		var sel = jQuery(this).val();
		
		jQuery('.mg_dd_items_preview img').hide();
		jQuery('.mg_dd_items_preview img').each(function() {
			if( jQuery(this).attr('alt') == sel ) {jQuery(this).fadeIn();}
		});	
	});
	
	
	// add item
	jQuery('body').delegate('#add_item_btn', "click", function() {
		var new_item_id = jQuery('#mh_grid_item').val();	
		jQuery('#add_item_btn div').html('<div style="height: 30px;" class="lcwp_loading"></div>');
		
		var data = {
			action: 'mg_add_item_to_builder',
			item_id: new_item_id,
			mg_mobile: (mg_mobile) ? 1 : 0 
		};
		jQuery.post(ajaxurl, data, function(response) {
			if( jQuery('#visual_builder_wrap ul .mg_box').size() == 0 ) {jQuery('#visual_builder_wrap ul').empty();}
			
			jQuery('#add_item_btn div').empty();
			jQuery('#visual_builder_wrap ul').append( response );

			size_boxes('.mg_box');
			$container.masonry( 'reload' );
		});
	});
	
	
	// remove item
	jQuery('body').delegate('.del_item', "click", function() {
		if(confirm('<?php echo mg_sanitize_input( __('Remove the item?', 'mg_ml')) ?>')) {
			jQuery(this).parent().parent().fadeOut('fast', function() {
				$container.masonry( 'remove', jQuery(this) );
				$container.masonry( 'reload' );	
			});
		}
	});
	
	
	// items cat choose
	jQuery('body').delegate('#mh_grid_cats', "change", function() {
		var item_cats = jQuery(this).val();	
		var data = {
			action: 'mg_item_cat_posts',
			item_cats: item_cats
		};
		
		jQuery('.mg_dd_items_preview').remove();
		jQuery('#terms_posts_list').html('<div style="height: 30px;" class="lcwp_loading"></div>');
		
		jQuery.post(ajaxurl, data, function(response) {
			if( jQuery.trim(response) != '0' ) {
			
				var data = jQuery.parseJSON(response);

				jQuery('#terms_posts_list').html(data.dd);
				jQuery('#add_item_btn').parent().prepend(data.img);
				
				jQuery('#add_item_btn').fadeIn();
				
				mg_live_chosen();
				masonerize();
			}
			else {
				jQuery('#terms_posts_list').html('<span><?php echo mg_sanitize_input( __('No items found', 'mg_ml')) ?> ..</span>');
				jQuery('#add_item_btn').fadeOut();	
				
				if( jQuery('.mg_dd_items_preview').size() > 0 ) {
					jQuery('.mg_dd_items_preview').fadeOut(function() {
						jQuery(this).remove();	
					});
				}
			}
		});	
	});
	

	// select the grid
	jQuery('body').delegate('#man_grid_box input[type=radio]', 'click', function() {
		mg_sel_grid = parseInt(jQuery(this).val());
		var grid_title = jQuery(this).parent().siblings('.mg_grid_tit').text();

		jQuery('.mg_grid_content').html('<div style="height: 30px;" class="lcwp_loading"></div>');

		var data = {
			action: 'mg_grid_builder',
			grid_id: mg_sel_grid 
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.mg_grid_content').html(response);
			
			// add the title
			jQuery('.mg_grid_content > h2').html(grid_title);
			
			// savegrid box
			jQuery('#save_grid_box').fadeIn();
			
			mg_live_chosen();
			
			masonerize();
			size_boxes('.mg_box');
			$container.masonry('reload');
			mg_mobile = false;	
		});	
	});
	
	
	// add grid
	jQuery('#add_grid_btn').click(function() {
		var grid_name = jQuery('#add_grid').val();
		
		if( jQuery.trim(grid_name) != '' ) {
			var data = {
				action: 'mg_add_grid',
				grid_name: grid_name,
				lcwp_nonce: '<?php echo wp_create_nonce('lcwp_nonce') ?>'
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				var resp = jQuery.trim(response); 
				
				if(resp == 'success') {
					jQuery('#ajax_mess').empty().append('<div class="updated"><p><strong><?php echo mg_sanitize_input( __('Grid added', 'mg_ml')) ?></strong></p></div>');	
					jQuery('#add_grid').val('');
					
					mg_grid_pag = 1;
					mg_load_grids();
					mg_hide_wp_alert();
				}
				else {
					jQuery('#ajax_mess').empty().append('<div class="error"><p>'+resp+'</p></div>');
				}
			});	
		}
	});
	
	
	// search grids in list
	jQuery('body').delegate('#mg_src_grid_btn', 'click', function() {
		src_str = jQuery('#mg_src_grid').val(); 
		mg_grid_pag = 1;
		mg_load_grids();
	});
	jQuery('#mg_src_grid').keypress(function(event){
		if(event.keyCode === 13){
			src_str = jQuery('#mg_src_grid').val(); 
			mg_grid_pag = 1;
			mg_load_grids();
		}
		
		event.cancelBubble = true;
		if(event.stopPropagation) event.stopPropagation();
   	});
	
	
	// manage grids pagination
	// prev
	jQuery('body').delegate('#mg_prev_grids', 'click', function() {
		mg_grid_pag = mg_grid_pag - 1;
		mg_load_grids();
	});
	// next
	jQuery('body').delegate('#mg_next_grids', 'click', function() {
		mg_grid_pag = mg_grid_pag + 1;
		mg_load_grids();
	});
	
	
	// load grid list
	function mg_load_grids() {
		jQuery('#man_grid_box .inside').html('<div style="height: 30px;" class="lcwp_loading"></div>');
		
		var data = {
			'action': 'mg_get_grids',
			'grid_page': mg_grid_pag,
			'grid_src': src_str,
			'lcwp_nonce': '<?php echo wp_create_nonce('lcwp_nonce') ?>'
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			dataType: "json",
			success: function(response){	
				jQuery('#man_grid_box .inside').empty();
				
				// get elements
				mg_grid_pag = response.pag;
				var mg_grid_tot_pag = response.tot_pag;
				var mg_grids = response.grids;	

				var a = 0;
				jQuery.each(mg_grids, function(k, v) {	
					if( mg_sel_grid == v.id) {var sel = 'checked="checked"';}
					else {var sel = '';}
				
					jQuery('#man_grid_box .inside').append('<div class="misc-pub-section-last">\
						<span><input type="radio" name="gl" value="'+ v.id +'" '+ sel +' /></span>\
						<span class="mg_grid_tit" style="padding-left: 7px;" title="Grid ID #'+ v.id +'">'+ v.name +'</span>\
						<span class="mg_del_grid" id="gdel_'+ v.id +'"></span>\
					</div>');
					
					a = a + 1;
				});
				
				if(a == 0) {
					jQuery('#man_grid_box .inside').html('<p><?php echo mg_sanitize_input( __('No existing grids', 'mg_ml')) ?></p>');
					jQuery('#man_grid_box h3.hndle').html('<?php echo mg_sanitize_input( __('Grid List', 'mg_ml')) ?>');
				}
				else {
					// manage pagination elements
					if(mg_grid_tot_pag > 1) {
						jQuery('#man_grid_box h3.hndle').html('<?php echo mg_sanitize_input( __('Grid List', 'mg_ml')) ?> (<?php echo mg_sanitize_input( __('pag', 'mg_ml')) ?> '+mg_grid_pag+' <?php echo mg_sanitize_input( __('of', 'mg_ml')) ?> '+mg_grid_tot_pag+')\
						<span id="mg_next_grids">&raquo;</span><span id="mg_prev_grids">&laquo;</span>');
					} else {
						jQuery('#man_grid_box h3.hndle').html('<?php echo mg_sanitize_input( __('Grid List', 'mg_ml')) ?>');	
					}
					
					// different cases
					if(mg_grid_pag <= 1) { jQuery('#mg_prev_grids').hide(); }
					if(mg_grid_pag >= mg_grid_tot_pag) {jQuery('#mg_next_grids').hide();}	
				}
			}
		});	
	}
	
	
	// delete grid
	jQuery('body').delegate('.mg_del_grid', 'click', function() {
		$target_grid_wrap = jQuery(this).parent(); 
		var grid_id  = jQuery(this).attr('id').substr(5);
		
		if(confirm('<?php echo mg_sanitize_input( __('Delete definitively the grid?', 'mg_ml')) ?>')) {
			var data = {
				action: 'mg_del_grid',
				grid_id: grid_id
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				var resp = jQuery.trim(response); 
				
				if(resp == 'success') {
					// if is this one opened
					if(mg_sel_grid == grid_id) {
						jQuery('.mg_grid_content').html('<p><?php echo mg_sanitize_input( __('Select a grid', 'mg_ml')) ?> ..</p>');
						mg_sel_grid = 0;
						
						// savegrid box
						jQuery('#save_grid_box').fadeOut();
					}
					
					$target_grid_wrap.slideUp(function() {
						jQuery(this).remove();
						
						if( jQuery('#man_grid_box .inside .misc-pub-section-last').size() == 0) {
							jQuery('#man_grid_box .inside').html('<p><?php echo mg_sanitize_input( __('No existing grids', 'mg_ml')) ?></p>');
						}
					});	
				}
				else {alert(resp);}
			});
		}
	});
	
	
	// save grid
	jQuery('body').delegate('#save_grid_box input', 'click', function() {
		var items_list = jQuery.makeArray();
		var items_width = jQuery.makeArray();
		var items_height = jQuery.makeArray();
		var items_m_width = jQuery.makeArray();
		var items_m_height = jQuery.makeArray();
		
		// catch data
		jQuery('#visual_builder_wrap .mg_box').each(function() {
			var item_id = jQuery(this).children('input').val();
            items_list.push(item_id);
			
            items_width.push( jQuery(this).find('.select_w').val() );
            items_height.push( jQuery(this).find('.select_h').val() );
			
			items_m_width.push( jQuery(this).find('.select_m_w').val() );
            items_m_height.push( jQuery(this).find('.select_m_h').val() );
        });
		
		// ajax
		var data = {
			action: 'mg_save_grid',
			grid_id: mg_sel_grid,
			items_list: items_list,
			items_width: items_width,
			items_height: items_height,
			items_m_width: items_m_width,
			items_m_height: items_m_height
		};
		
		jQuery('#save_grid_box div').html('<div style="height: 30px;" class="lcwp_loading"></div>');
		
		jQuery.post(ajaxurl, data, function(response) {
			var resp = jQuery.trim(response); 
			jQuery('#save_grid_box div').empty();
			
			if(resp == 'success') {
				jQuery('#ajax_mess').empty().append('<div class="updated"><p><strong><?php echo mg_sanitize_input( __('Grid saved', 'mg_ml')) ?></strong></p></div>');	
				mg_hide_wp_alert();
			}
			else {
				jQuery('#ajax_mess').empty().append('<div class="error"><p>'+resp+'</p></div>');
			}
		});	
	});
	
	
	<!-- masonerize the preview -->
	
	// masonry init
	function masonerize() {
		$container = jQuery('#visual_builder_wrap');
		
		$cont_width = $container.width();
		$container.css('min-height', $cont_width+'px').css('height', 'auto');
		
		$container.masonry({
			isAnimated: true,
			columnWidth: 1,
			itemSelector: '.mg_box'
		});
		
		sortable_masonry();
		
		return true;	
	}
	
	// cells class to decimal percentage
	function get_size(shape) {
		switch(shape) {
		  case '1_10': var perc = 0.1; break;
		  case '1_9': var perc = 0.111; break;
		  case '1_8': var perc = 0.125; break;
		  
		  case '5_6': var perc = 0.8333333; break;
		  case '1_6': var perc = 0.1666666; break;
		  
		  case '4_5': var perc = 0.80; break;
		  case '3_5': var perc = 0.60; break;
		  case '2_5': var perc = 0.40; break;
		  case '1_5': 
		  case 'auto':var perc = 0.20; break;
		  
		  case '3_4': var perc = 0.75; break;
		  case '1_4': var perc = 0.25; break;
		  
		  case '2_3': var perc = 0.6666666; break;
		  case '1_3': var perc = 0.3333333; break;
		  
		  case '1_2': var perc = 0.50; break;
		  default   : var perc = 1; break;
		}
		return perc; 	
	}
	
	
	function get_width() {
		<?php foreach(mg_sizes() as $size) : ?> 
		if( $target.hasClass('col<?php echo $size ?>') ) { 
			var size = get_size('<?php echo $size ?>');
			var wsize = Math.round($container.width() * size);
		}
		<?php endforeach; ?>
		
		// max width control
		var cols = Math.round( 1 / size );
		if( (wsize * cols) > $container.width() ) {
			wsize = wsize - 1;	
		}
		
		return wsize;
	}
	
	
	function get_height() {
		<?php 
		$sizes = mg_sizes();
		$sizes[] = 'auto';
		
		foreach($sizes as $size) : ?> 
		if( $target.hasClass('row<?php echo $size ?>') ) { 
			var size = get_size('<?php echo $size ?>');
			var hsize = Math.round($container.width() * size);
		}
		<?php endforeach; ?>	 

		// max width control - to follow width algorithm
		var cols = Math.round( 1 / size );
		if( (hsize * cols) > $container.width() ) {
			hsize = hsize - 1;	
		}

		return hsize;
	}

	
	// apply sizes to boxes
	function size_boxes(target) {
		jQuery(target).each(function(index) {
			$target = jQuery(this);
			
			// boxes
			jQuery(this).css('width', get_width());
			jQuery(this).css('height', get_height());
		});
		return true;	
	}
	
	/*** standard layout - live sizing ***/
	// box resize width
	jQuery('body').delegate('#mg_sortable .select_w', 'change', function() {
		$focus_box = jQuery(this).parents('.mg_box');
		
		var orig_w = $focus_box.attr('mg-width');
		var new_w = jQuery(this).val();
		
		$focus_box.removeClass('col'+orig_w);
		$focus_box.addClass('col'+new_w);
		$focus_box.attr('mg-width', new_w);
		
		size_boxes('.mg_box');
		$container.masonry( 'reload' );
	});
	
	
	// box resize height
	jQuery('body').delegate('#mg_sortable .select_h', 'change', function() {
		$focus_box = jQuery(this).parents('.mg_box');
		
		var orig_h = $focus_box.attr('mg-height');
		var new_h = jQuery(this).val();
		
		$focus_box.removeClass('row'+orig_h);
		$focus_box.addClass('row'+new_h);
		$focus_box.attr('mg-height', new_h);
		
		size_boxes('.mg_box');
		$container.masonry('reload');
	});
	
	
	/*** mobile layout - live sizing ***/
	// box resize width
	jQuery('body').delegate('#mg_sortable .select_m_w', 'change', function() {
		$focus_box = jQuery(this).parents('.mg_box');
		
		var orig_w = $focus_box.attr('mg-m-width');
		var new_w = jQuery(this).val();
		
		$focus_box.removeClass('col'+orig_w);
		$focus_box.addClass('col'+new_w);
		$focus_box.attr('mg-m-width', new_w);
		
		size_boxes('.mg_box');
		$container.masonry('reload');
	});
	
	
	// box resize height
	jQuery('body').delegate('#mg_sortable .select_m_h', 'change', function() {
		$focus_box = jQuery(this).parents('.mg_box');
		
		var orig_h = $focus_box.attr('mg-m-height');
		var new_h = jQuery(this).val();
		
		$focus_box.removeClass('row'+orig_h);
		$focus_box.addClass('row'+new_h);
		$focus_box.attr('mg-m-height', new_h);
		
		size_boxes('.mg_box');
		$container.masonry( 'reload' );
	});
	/*************************************/
	
	
	// mobile mode toggle 
	jQuery('body').delegate('#mg_mobile_view_toggle', 'click', function() {
		if(jQuery('#visual_builder_wrap').hasClass('mg_mobile_builder')) {
			jQuery(this).removeClass('mg_active');
			jQuery(this).find('span').text('OFF');
			jQuery('#visual_builder_wrap').removeClass('mg_mobile_builder');
			
			// change items sizing classes from mobile to standard
			jQuery('#mg_sortable .mg_box').each(function() {
                var $s = jQuery(this);
				$s.removeClass('col'+ $s.attr('mg-m-width')).removeClass('row'+ $s.attr('mg-m-height'))
					.addClass('col'+ $s.attr('mg-width')).addClass('row'+ $s.attr('mg-height'));
            });
			
			// bulk sizes 
			jQuery('#mg_bulk_mw_chosen, #mg_bulk_mh_chosen').hide();
			jQuery('#mg_bulk_w_chosen, #mg_bulk_h_chosen').show();
			
			mg_mobile = false;
			jQuery('#mg_sortable .select_w').first().trigger('change');	
		}
		else {
			jQuery(this).addClass('mg_active');
			jQuery(this).find('span').text('ON');
			jQuery('#visual_builder_wrap').addClass('mg_mobile_builder');	
			
			// change items sizing classes from standard to mobile
			jQuery('#mg_sortable .mg_box').each(function() {
                var $s = jQuery(this);

				$s.removeClass('col'+ $s.attr('mg-width')).removeClass('row'+ $s.attr('mg-height'))
					.addClass('col'+ $s.attr('mg-m-width')).addClass('row'+ $s.attr('mg-m-height'));
            });
			
			// bulk sizes 
			jQuery('#mg_bulk_mw_chosen, #mg_bulk_mh_chosen').show();
			jQuery('#mg_bulk_w_chosen, #mg_bulk_h_chosen').hide();
			
			mg_mobile = true;	
			jQuery('#mg_sortable .select_m_w').first().trigger('change');
		}
	});
	
	
	//// bulk sizing system
	// width
	jQuery('body').delegate('#mg_bulk_w_btn', 'click', function() {
		if(confirm("<?php _e('Every grid item will be affected, continue?') ?>")) {
			var val = (mg_mobile) ? jQuery('#mg_bulk_mw').val() : jQuery('#mg_bulk_w').val();
			var dd_class = (mg_mobile) ? '.select_m_w' : '.select_w';
			
			jQuery('#mg_sortable .mg_box '+dd_class+' option').attr('selected', false);
			jQuery('#mg_sortable .mg_box '+dd_class+' option[value="'+val+'"]').attr('selected', 'selected');
			
			jQuery('#mg_sortable '+dd_class).trigger('change');
		}
	});
	
	// height
	jQuery('body').delegate('#mg_bulk_h_btn', 'click', function() {
		if(confirm("<?php _e('Every grid item will be affected, continue?') ?>")) {
			var val = (mg_mobile) ? jQuery('#mg_bulk_mh').val() : jQuery('#mg_bulk_h').val();
			var dd_class = (mg_mobile) ? '.select_m_h' : '.select_h';
			
			if(val == 'auto') {
				jQuery('#mg_sortable .mg_box').not('.mg_inl_slider_type, .mg_inl_video_type').find(dd_class+' option').attr('selected', false);
				jQuery('#mg_sortable .mg_box').not('.mg_inl_slider_type, .mg_inl_video_type').find(dd_class+' option[value="'+val+'"]').attr('selected', 'selected');
			} else {
				jQuery('#mg_sortable .mg_box '+dd_class+' option').attr('selected', false);
				jQuery('#mg_sortable .mg_box '+dd_class+' option[value="'+val+'"]').attr('selected', 'selected');
			}
				
			jQuery('#mg_sortable '+dd_class).trigger('change');
		}
	});
	
	
	// sortable masonry
	function sortable_masonry() {
		
		jQuery('#mg_sortable').sortable({
			placeholder: {
		        element: function(currentItem) {
					return jQuery("<li class='mg_box masonry mg_placeholder' style='height: " + (currentItem.height()) + "px; width: " + (currentItem.width()) +"px; background-color: #97dd52;'></li>")[0];
		        },
		        update: function(container, p) {
					return;
		        }
		    },
			tolerance: 'intersect',
			items: 'li',
			handle: 'h3',
			opacity: 0.8,
			scrollSensivity: 50,
			helper: function(event, element) {
				var clone = $(element).clone();
				clone.removeClass('mg_box');
				element.removeClass('mg_box');
				return clone;
			},
			start: function() {
				$container.masonry( 'reload' );
			},
			stop: function(event,ui){
				ui.item.addClass("mg_box");
				$container.masonry( 'reload' );
			},
			change: function(){
				$container.masonry( 'reload' );
			}
		});
                                          
	};
	
	
	// on page resize
	var mg_is_resizing = false;
	jQuery(window).resize(function() {
		if(jQuery('.mg_box').size() > 0 && mg_is_resizing == false) {
			mg_is_resizing = true;
			
			setTimeout(function() {
				size_boxes('.mg_box');
			}, 50);
			
			mg_is_resizing = false;
		}
	});

	
	<!-- other -->
	
	// init chosen for live elements
	function mg_live_chosen() {
		jQuery('.lcweb-chosen').each(function() {
			var w = jQuery(this).css('width');
			jQuery(this).chosen({width: w}); 
		});
		jQuery(".lcweb-chosen-deselect").chosen({allow_single_deselect:true});
	}

	// hide message after 3 sec
	function mg_hide_wp_alert() {
		setTimeout(function() {
		 jQuery('#ajax_mess').empty();
		}, 3500);	
	}
	
});
</script>

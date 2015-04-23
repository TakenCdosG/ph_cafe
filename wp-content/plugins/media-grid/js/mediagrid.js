(function($) {
	var $mg_sel_grid = false; // set displayed item's grid id
	var mg_mobile_mode = false;
	var mg_lb_shown = false; 
	mg_slider_autoplay = jQuery.makeArray();
	
	// body/html style vars
	var mg_html_style = '';
	var mg_body_style = '';
	
	// lightbox top margin
	var mg_lb_topmargin = (jQuery(window).width() < 750) ? 20 : 60;
	
	// CSS3 loader code
	mg_loader = 
	'<div class="mg_loader">'+
		'<div class="mgl_1"></div><div class="mgl_2"></div><div class="mgl_3"></div><div class="mgl_4"></div>'+
	'</div>';
	
	// event for touch devices that are not webkit
	var mg_generic_touch_event = (!("ontouchstart" in document.documentElement) || navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) ? '' : ' touchstart'; 
	
	// first init
	jQuery(document).ready(function() {
		mg_item_img_switch(true);
		mg_append_lightbox();
		mg_get_deeplink();
		
		jQuery('.mg_container').each(function() {
			var mg_cont_id = jQuery(this).attr('id');
			mg_size_boxes(mg_cont_id, false);
		});
	});
	
	
	// Grid handling for AJAX pages
	mg_ajax_init = function(grid_id) {
		mg_item_img_switch(true);
		
		var mg_cont_id = 'mg_grid_'+ grid_id;
		mg_size_boxes(mg_cont_id, false);
		
		mg_display_grid(mg_cont_id);
		
		if(jQuery('#mg_full_overlay').size() == 0) {
			mg_append_lightbox();
		}
	};
	
	
	// append the lightbox code to the website
	mg_append_lightbox = function() {
		if(typeof(mg_lightbox_mode) != 'undefined') {
			
			// leave the lightbox code in case of google crawler
			if(jQuery('#mg_full_overlay.google_crawler').size() > 0) {
				$mg_item_content = jQuery('#mg_overlay_content');	
				mg_lb_shown = true;
				return true;
			}
			
			/// remove existing one
			if(jQuery('#mg_full_overlay').size() > 0) {
				jQuery('#mg_full_overlay, #mg_full_overlay_wrap').remove();
			}
			
			// touchswipe class
			var ts_class = (mg_lb_touchswipe) ? 'class="mg_touchswipe"' : '';
			
			jQuery('body').append(''+
			'<div id="mg_full_overlay" '+ts_class+'>'+ 
				'<div class="mg_item_load">'+ mg_loader + '</div>' +
				'<div id="mg_overlay_content"></div>'+
				'<div id="mg_lb_scroll_helper" class="'+ mg_lightbox_mode +'"></div>'+
			'</div>'+
			'<div id="mg_full_overlay_wrap" class="'+ mg_lightbox_mode +'"></div>');
			
			$mg_item_content = jQuery('#mg_overlay_content');	
		}
	};
	
	
	// image manager for mobile mode
	mg_item_img_switch = function(first_init) {
		var safe_mg_mobile = (typeof(mg_mobile) == 'undefined') ? 760 : mg_mobile;

		//mg_mobile_mode
		if(jQuery(window).width() < safe_mg_mobile && !mg_mobile_mode) {
			jQuery('.mg_box .thumb').each(function() {
                jQuery(this).attr('src', jQuery(this).attr('mobileurl'));
            });
			
			if(typeof(first_init) == 'undefined') {mg_inl_slider_init();}

			mg_mobile_mode = true;	
			return true;	
		}
		
		// standard
		if(jQuery(window).width() >= safe_mg_mobile && (mg_mobile_mode || typeof(first_init) != 'undefined')) {
			jQuery('.mg_box .thumb').each(function() {
                jQuery(this).attr('src', jQuery(this).attr('fullurl'));
            });
			
			if(typeof(first_init) == 'undefined') {mg_inl_slider_init();}

			mg_mobile_mode = false;	
			return true;
		}
	};
	
	
	// get cell width
	mg_get_w_size = function(box_id, mg_wrap_w) {
		var size = (mg_mobile_mode) ?  jQuery(box_id).attr('mgi_mw') : jQuery(box_id).attr('mgi_w');
		var wsize = Math.round(mg_wrap_w * parseFloat(size.replace(',', '.')) );

		// max width control
		var cols = Math.round( 1 / size );
		if( (wsize * cols) > mg_wrap_w ) {
			wsize = wsize - 1;	
		}
		
		return wsize;
	};
	
	
	// get cell height
	mg_get_h_size = function(box_id, mg_wrap_w, mg_box_w) {
		var hsize = 0;
		var size = (mg_mobile_mode) ? jQuery(box_id).attr('mgi_mh') : jQuery(box_id).attr('mgi_h');
		
		// standard fractions
		if(size != 'auto') {
			hsize = Math.round(mg_wrap_w * parseFloat(size.replace(',', '.')) );
			
			// max width control - to follow width algorithm
			var cols = Math.round( 1 / size );
			if( (hsize * cols) > mg_wrap_w) {
				hsize = hsize - 1;	
			}
		}
		
		// "auto" height calculation
		else {
			var add_space = (mg_boxMargin * 2) + (mg_boxBorder * 2) + (mg_imgPadding * 2);
			
			// if is inline text - return only additional space
			if(jQuery(box_id).hasClass('mg_inl_text')) {
				hsize = add_space;
			}
			
			// image aspect ratio
			else {
				var ratio = parseFloat( jQuery(box_id).attr('ratio') );
				var img_w = mg_box_w - add_space;
				
				hsize = Math.round(img_w * ratio) + add_space;
			}
		}	
		
		return hsize;	
	};

	
	// size boxes
	mg_size_boxes = function(cont_id, is_resizing) {
		if( jQuery('#'+cont_id).attr('rel') == 'auto' || mg_mobile_mode) {
			var mg_wrap_w = jQuery('#'+cont_id).width();
		} else {
			var mg_wrap_w = parseInt(jQuery('#'+cont_id).attr('rel'));
		}
		
		var tot_elem = jQuery('#'+cont_id+' .mg_box').size();
		jQuery('#'+cont_id+' .mg_box').each(function(i) {
			var mg_box_id = '#' + jQuery(this).attr('id');

			// size boxes
			var mg_box_w = mg_get_w_size(mg_box_id, mg_wrap_w);
			var mg_box_h = mg_get_h_size(mg_box_id, mg_wrap_w, mg_box_w);
			
			jQuery(this).css('width', mg_box_w);
			

			// height - calculate the title under and adjust img_wrap
			if( jQuery(this).find('.mg_title_under').size() > 0 ) {
				var tit_under_h = jQuery(this).find('.mg_title_under').outerHeight(true);				
				
				jQuery(this).find('.img_wrap').css('height', (mg_box_h - mg_boxMargin * 2));
				jQuery(this).css('height', mg_box_h + tit_under_h);
			} 
			
			// if inline text and has auto height - get the dynamic value 
			else if( jQuery(this).hasClass('mg_inl_text') && ( (!mg_mobile_mode && jQuery(this).hasClass('rowauto')) || (mg_mobile_mode && jQuery(this).hasClass('m_rowauto'))) ) {
				var h = mg_box_h + parseInt( jQuery(this).find('.img_wrap > div').css('padding-top')) + parseInt( jQuery(this).find('.img_wrap > div').css('padding-bottom'));
				jQuery(this).find('.img_wrap > div > *').each(function() {
                    h = h + jQuery(this).outerHeight(true);
                });
				jQuery(this).css('height', h);
			}
			
			else  {
				jQuery(this).css('height', mg_box_h);	
			}
			
			// overlays control
			if( parseInt(mg_box_w) < 100 || parseInt(mg_box_h) < 100 ) { jQuery(this).find('.cell_type').hide(); }
			else {jQuery(this).find('.cell_type').show();}
			
			if( parseInt(mg_box_w) < 65 || parseInt(mg_box_h) < 65 ) { jQuery(this).find('.cell_more').hide(); }
			else {jQuery(this).find('.cell_more').show();}
			
			
			// masonerize after the sizing
			if(i == (tot_elem - 1)) {
				if(typeof(is_resizing) == 'undefined' || !is_resizing) {
					mg_masonerize(cont_id);	
				} 
				else {
					setTimeout(function() {
						if(typeof(jQuery.Isotope) != 'undefined' && typeof(jQuery.Isotope.prototype.reLayout) != 'undefined') { // old Isotope
							jQuery('#' + cont_id).isotope('reLayout');
						}	
						else { // new
							jQuery('#' + cont_id).isotope('layout');
						}
					}, 710);	
				}
			}
		});	
	};
	
	
	// masonry init
	mg_masonerize = function(cont_id) {
		jQuery('#' + cont_id).isotope({
			masonry: {
				columnWidth: 1
			},
			containerClass: 'mg_isotope',	
			itemClass : 'mg_isotope-item',
			itemSelector: '.mg_box',
			transitionDuration: '0.7s'
		});	
		
		// category deeplink
		var sel_filter = false;
		var hash = location.hash;
		var gid = cont_id.substr(8);
		
		if (hash.indexOf('#!mg_cd') !== -1) {
			var val = hash.substring(hash.indexOf('#!mg_cd')+8, hash.length);

			if( jQuery('#mgf_'+gid+' a.mgf_id_'+val).size() > 0 ) {
				sel_filter = val;
				
				setTimeout(function() {
					jQuery('#mgf_'+gid+' a.mgf_id_'+val).trigger('click');
				}, 50);
			}
		}
		
		// check for default filter
		else {
			var sel = jQuery('#mgf_'+gid+' .mg_cats_selected').attr('rel');

			if(typeof(sel) != 'undefined' && sel != '*') {
				sel_filter = sel;
				
				if(mg_filters_behav == 'standard') {
					jQuery('#' + cont_id).isotope({ filter: '.mgc_' + sel });
				} else {
					jQuery('#' + cont_id).mg_custom_iso_filter({ filter: '.mgc_' + sel });	
				}	
			}
		}
		
		mg_display_grid(cont_id, sel_filter);
	};
	
	
	// grid display
	mg_display_grid = function(grid_id, sel_filter) {
		mg_responsive_txt();
		
		
		// fallback for IE
		if( navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
			mg_ie_fallback(grid_id);	
		}
		
		// if no items have a featured image
		if( jQuery('#'+grid_id+' .mg_box').not('.mg_inl_slider').find('img').size() == 0 ) {
			// selectors in case of selected filter
			if(sel_filter && mg_filters_behav == 'standard') {
				// first filtered
				jQuery('#'+grid_id+' .mg_box.mgc_'+sel_filter).mg_display_boxes(grid_id);
				jQuery('#'+grid_id+' .mg_box:not(.mgc_'+sel_filter+')').mg_display_boxes(grid_id);
			} else {
				jQuery('#'+grid_id+' .mg_box').mg_display_boxes(grid_id);	
			}	
		}
		
		else {
			jQuery('#'+grid_id+' .mg_box').not('.mg_inl_slider').find('img').lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					
					// selectors in case of selected filter
					if(sel_filter && mg_filters_behav == 'standard') {
						// first filtered
						jQuery('#'+grid_id+' .mg_box.mgc_'+sel_filter).mg_display_boxes(grid_id);
						jQuery('#'+grid_id+' .mg_box:not(.mgc_'+sel_filter+')').mg_display_boxes(grid_id);
					} else {
						jQuery('#'+grid_id+' .mg_box').mg_display_boxes(grid_id);	
					}
				}
			});	
		}
	};

	
	// show boxes - to call after allLoaded
	jQuery.fn.mg_display_boxes = function(grid_id) {
		var a = 0;
		this.each(function(i, v) {
			var $subj = jQuery(this);
			var delay = (mg_delayed_fx) ? 170 : 0;
			
			setTimeout(function() {
				if( navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
					$subj.find('.mg_shadow_div').fadeTo(450, 1);	
				}
				$subj.removeClass('mg_pre_show').addClass('mg_shown');
				
				// if inline slider - init
				if( $subj.hasClass('mg_inl_slider') ) {
					var sid = $subj.find('.mg_inl_slider_wrap').attr('id');
					mg_inl_slider_init(sid);	
				}
			}, (delay * a));
			
			a++;
		});
		jQuery('#'+grid_id).parents('.mg_grid_wrap').find('.mg_loader').fadeOut('fast');	
	};


	// IE transitions fallback
	mg_ie_fallback = function(grid_id) {
		if( jQuery('#'+grid_id+' .mgom_layer').size() == 0 ) { // not for overlay manager
			jQuery('.mg_box .overlays').children().hide();
			
			jQuery('.mg_box .img_wrap').hover(
				function() {
					jQuery(this).find('.overlays').children().hide();
					jQuery(this).find('.overlays').children().not('.cell_more').fadeIn(250);
					jQuery(this).find('.overlays .cell_more').fadeIn(150);
				}
			);
		}
	};

	///////////////////////////////////////////////////////////


	// open item trigger
	jQuery(document).ready(function() {
		jQuery('body').delegate('.mg_closed:not(.mg_disabled)', 'click', function(e){
			// elements to ignore -> mgom socials
			var $e = jQuery(e.target);
			if(!$e.hasClass('mgom_fb') && !$e.hasClass('mgom_tw') && !$e.hasClass('mgom_pt') && !$e.hasClass('mgom_gp')) {
				
				var pid = jQuery(this).attr('rel').substr(4);
				$mg_sel_grid = jQuery(this).parents('.mg_container');
				
				mg_open_item(pid);
			}
		});
	});
	 
	// OPEN ITEM
	mg_open_item = function(pid) {
		// backup html/body inline CSS
		mg_html_style = jQuery('html').attr('style');
		mg_body_style = jQuery('body').attr('style');
		
		jQuery('#mg_full_overlay').show();
		jQuery('html').css('overflow', 'hidden');
		jQuery('body').css('overflow', 'visible');
		
		if( jQuery('body').height() > jQuery(window).height() && !mg_is_mobile() ) {
			jQuery('html').css('margin-right', 16);	
		}
		
		if(mg_is_mobile()) { jQuery('#mg_overlay_content').delay(20).trigger('click'); }
		
		// scroll helper - adjust height dynamically
		setTimeout(function() {
			mg_scroll_helper_h = setInterval(function() {
				jQuery('#mg_lb_scroll_helper').css('height', jQuery('#mg_overlay_content').outerHeight(true));
			}, 500);
		}, 800);
		
		setTimeout(function() {
			jQuery('#mg_full_overlay .mg_item_load, #mg_full_overlay_wrap').addClass('mg_lb_shown');
			mg_get_item_content(pid);	
		}, 50);
	};
	
	
	// get item content
	mg_get_item_content = function(pid) {
		mg_set_deeplink('lb', pid);
		$mg_item_content.removeClass('mg_lb_shown');
		
		// get prev and next items ID to compose nav arrows
		var nav_arr = jQuery.makeArray();
		var curr_pos = 0;
		
		$mg_sel_grid.find('.mg_closed').not('.mg_disabled, .isotope-hidden').each(function(i, el) {
			if(jQuery(this).css('opacity') != '0') {
				var item_id = jQuery(this).attr('rel').substr(4);
	
				nav_arr.push(item_id);
				if(item_id == pid) {curr_pos = i;}
			}
        });

		// prev
		var prev_id = (curr_pos != 0) ? nav_arr[(curr_pos - 1)] : 0;
		// next
		var next_id = (curr_pos != (nav_arr.length - 1)) ? nav_arr[(curr_pos + 1)] : 0;
		
		
		// perform ajax call
		var cur_url = location.href;	
		var data = {
			mg_lb	: 'mg_lb_content',
			pid		: pid,
			prev_id : prev_id,
			next_id : next_id
		};
		jQuery.post(cur_url, data, function(response) {
			jQuery('#mg_overlay_content').html(response);
			mg_lb_shown = true;

			// featured content max-width
			if( jQuery('.mg_item_featured[rel]').size() > 0 ) {
				var fc_max_w = jQuery('.mg_item_featured').attr('rel');
				jQuery('#mg_overlay_content').css('max-width', fc_max_w);
			}
			else {jQuery('#mg_overlay_content').removeAttr('style');}
			
			// older IE iframe bg fix
			if(mg_is_old_IE() && jQuery('#mg_overlay_content .mg_item_featured iframe').size() > 0) {
				jQuery('#mg_overlay_content .mg_item_featured iframe').attr('allowTransparency', 'true');
			}
			
			// show with a little delay to be smoother
			setTimeout(function() {
				jQuery('#mg_full_overlay .mg_item_load').removeClass('mg_lb_shown');
				$mg_item_content.addClass('mg_lb_shown');
			}, 50);

			// functions for slider and players
			mg_resize_video();
			mg_lazyload();
		});

		return true;
	};
	
	// switch item - arrow click
	jQuery(document).ready(function() {
		jQuery('body').delegate('.mg_nav_active > *', 'click'+mg_generic_touch_event, function(){
			var pid = jQuery(this).parents('.mg_nav_active').attr('rel');
			mg_switch_item_act(pid);
		});
	});
	
	// switch item - keyboards events
	jQuery(document).keydown(function(e){
		if(mg_lb_shown) {
			
			// prev
			if (e.keyCode == 37 && jQuery('.mg_nav_prev.mg_nav_active').size() > 0) {
				var pid = jQuery('.mg_nav_prev.mg_nav_active').attr('rel');
				mg_switch_item_act(pid);
			}
			
			// next 
			if (e.keyCode == 39 && jQuery('.mg_nav_next.mg_nav_active').size() > 0) {
				var pid = jQuery('.mg_nav_next.mg_nav_active').attr('rel');
				mg_switch_item_act(pid);
			}
		}
	});	
	
	
	// switch item - touchSwipe events
	jQuery(document).ready(function() {
		if(mg_lb_touchswipe && navigator.appVersion.indexOf("MSIE 8.") == -1) {		

			// prev
			jQuery('#mg_overlay_content').hammer({distance: 170}).bind("swiperight", function(e) {
				if (jQuery('.mg_nav_prev.mg_nav_active').size() > 0) {
					var pid = jQuery('.mg_nav_prev.mg_nav_active').attr('rel');
					mg_switch_item_act(pid);
				}
			});
			
			// next
			jQuery('#mg_overlay_content').hammer({distance: 170}).bind("swipeleft", function(e) {
				if (jQuery('.mg_nav_next.mg_nav_active').size() > 0) {
					var pid = jQuery('.mg_nav_next.mg_nav_active').attr('rel');
					mg_switch_item_act(pid);
				}
			});
		}
	});
		
	// actions for item switching
	mg_switch_item_act = function(pid) {
		jQuery('#mg_full_overlay .mg_item_load').addClass('mg_lb_shown');
		$mg_item_content.removeClass('mg_lb_shown');
		jQuery('#mg_lb_top_nav, .mg_side_nav, #mg_top_close').fadeOut(350, function() {
			jQuery(this).remove();	
		});
		
		// wait CSS3 animations
		setTimeout(function() {
			mg_unload_fb_scripts();
			$mg_item_content.empty();
			mg_get_item_content(pid);
		}, 370);	


	};
		

	// close item
	mg_close_lightbox = function() {
		mg_unload_fb_scripts();

		mg_lb_shown = false;
		$mg_item_content.removeClass('mg_lb_shown');
		jQuery('#mg_full_overlay_wrap').delay(120).removeClass('mg_lb_shown');
		jQuery('#mg_lb_top_nav, .mg_side_nav, #mg_top_close').fadeOut(350, function() {
			jQuery(this).remove();	
		});
		
		setTimeout(function() {
			jQuery('#mg_full_overlay').hide();
			$mg_item_content.empty();
			jQuery('#mg_full_overlay_wrap.google_crawler').fadeOut();
			
			// restore html/body inline CSS
			if(typeof(mg_html_style) != 'undefined') {jQuery('html').attr('style', mg_html_style);}
			else {jQuery('html').removeAttr('style');}
			
			if(typeof(mg_body_style) != 'undefined') {jQuery('body').attr('style', mg_body_style);}
			else {jQuery('body').removeAttr('style');}
			
			if(typeof(mg_scroll_helper_h) != 'undefined') {
				clearTimeout(mg_scroll_helper_h);
			}
			jQuery('#mg_lb_scroll_helper').removeAttr('style');
		}, 370);
		
		
		// trick to fix browsers bug with shadows
		setTimeout(function() {
			jQuery('.mg_shadow_div').css('overflow', 'visible');
			
			setTimeout(function() {
				jQuery('.mg_shadow_div').css('overflow', 'hidden');
			}, 150);
		}, 150);
		
		mg_clear_deeplink();
	};
	
	jQuery(document).ready(function() {
		jQuery('body').delegate('#mg_full_overlay_wrap.mg_classic_lb, #mg_lb_scroll_helper.mg_classic_lb, .mg_close_lb', 'click'+mg_generic_touch_event, function(){
			mg_close_lightbox();
		});
	});
	
	jQuery(document).keydown(function(e){
		if( jQuery('#mg_overlay_content .mg_close_lb').size() > 0 && e.keyCode == 27 ) { // escape key pressed
			mg_close_lightbox();
		}
	});
	
	
	// unload lightbox scripts
	mg_unload_fb_scripts = function() {
		
		// prevent jPlayer crash
		if( jQuery('.jp-jplayer').size() > 0 ) {
			jQuery('.jp-jplayer').jPlayer("stop");
			jQuery('.jp-jplayer').jPlayer("destroy");
		}
		
		// destroy the slider obj
		if(typeof(mg_galleria_size_check) != 'undefined') {
			clearTimeout(mg_galleria_size_check);

		}	
	};
	
	
	// resize video 
	mg_resize_video = function() {
		if( jQuery('.mg_item_featured iframe.mg_video_iframe').size() > 0 ) {	
			var if_w = jQuery('.mg_item_featured').width();
			var if_h = if_w * 0.56;
			jQuery('.mg_item_featured iframe').attr('width', if_w).attr('height', if_h);
			jQuery('#mg_lb_video_wrap').css('height', if_h);
		}	
	};

		
	// on resize
	jQuery(window).resize(function() {
		if(mg_lb_shown) {
			if(typeof(mg_is_resizing) != 'undefined') {clearTimeout(mg_is_resizing);}

			var mg_is_resizing = setTimeout(function() {
				mg_resize_video();
				mg_galleria_resize();
				
				mg_lb_topmargin = (jQuery(window).width() < 750) ? 20 : 60;
			}, 50);
		}
	});
	
	
	// lightbox images lazyload
	mg_lazyload = function() {
		$ll_img = jQuery('.mg_item_featured > img, #mg_lb_video_wrap img');
		if( $ll_img.size() > 0 ) {

			$ll_img.fadeTo(0, 0);			
			$ll_img.lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					
					$ll_img.fadeTo(300, 1);
					jQuery('.mg_item_featured .mg_loader, #mg_lb_video_wrap .mg_loader').fadeOut('fast');
					
					// for video poster
					if( jQuery('#mg_ifp_ol').size() > 0 )  {
						jQuery('#mg_ifp_ol').delay(300).fadeIn(200);	
						setInterval(function() {
							jQuery('#mg_lb_video_wrap > img').css('display', 'block'); // fix for poster image click
						}, 200);
					}
					
					// for the mp3 player
					if( jQuery('.mg_item_featured .jp-audio').size() > 0 )  {
						mg_lb_jplayer();
						jQuery('.jp-audio').fadeIn();	
					}
				}
			});	
		}
	};

	
	//////////////////////////////////////////////////////////////////////////

	
	// items filter - buttons
	jQuery(document).ready(function() {
		jQuery('body').delegate('.mg_filter a', 'click', function(e) {
			e.preventDefault();
			
			var gid = jQuery(this).parents('.mg_filter').attr('id').substr(4);
			var sel = jQuery(this).attr('rel');
			var cont_id = 'mg_grid_' + gid ;
	
			// set deeplink
			if ( sel !== '*' ) { mg_set_deeplink('cat', sel); }
			else { mg_clear_deeplink(); }
	
			if ( sel !== '*' ) {sel = '.mgc_' + sel;}
			if(mg_filters_behav == 'standard') {
				jQuery('#' + cont_id).isotope({ filter: sel });
			} else {
				jQuery('#' + cont_id).mg_custom_iso_filter({ filter: sel });	
			}
	  
			jQuery('#mgf_'+gid+' a').removeClass('mg_cats_selected');
			jQuery(this).addClass('mg_cats_selected');
			
			// if is there a dropdown filter - select option 
			if( jQuery('#mgmf_'+gid).size() > 0 ) {
				jQuery('#mgmf_'+gid+' option').removeAttr('selected');
				
				if(jQuery(this).attr('rel') !== '*') {
					jQuery('#mgmf_'+gid+' option[value='+ jQuery(this).attr('rel') +']').attr('selected', 'selected');
				}
			}
		});
	});
	
	
	// items filter - mobile dropdown
	jQuery(document).ready(function() {
		jQuery('body').delegate('.mg_mobile_filter_dd', 'change', function(e) {
			var gid = jQuery(this).parents('.mg_mobile_filter').attr('id').substr(5);
			var sel = jQuery(this).val();
			var cont_id = 'mg_grid_' + gid ;
	
			// set deeplink
			if ( sel !== '*' ) { mg_set_deeplink('cat', sel); }
			else { mg_clear_deeplink(); }
	
			if ( sel !== '*' ) {sel = '.mgc_' + sel;}
			if(mg_filters_behav == 'standard') {
				jQuery('#' + cont_id).isotope({ filter: sel });
			} else {
				jQuery('#' + cont_id).mg_custom_iso_filter({ filter: sel });	
			}
	  		
			// select related desktop filter's button
			var btn_to_sel = (jQuery(this).val() == '*') ? '.mgf_all' : '.mgf_id_'+sel
			jQuery('#mgf_'+gid+' a').removeClass('mg_cats_selected');
			jQuery('#mgf_'+gid+' '+btn_to_sel).addClass('mg_cats_selected');
		});
	});
	
	// custom filtering behavior
	jQuery.fn.mg_custom_iso_filter = function( options ) {
		options = jQuery.extend({
			filter: '*',
			hiddenStyle: { opacity: 0.2 },
			visibleStyle: { opacity: 1 }
		}, options );
		
		this.each( function() {
			var $items = jQuery(this).children();
			var $visible = $items.filter( options.filter );
			var $hidden = $items.not( options.filter );
			
			$visible.clearQueue().animate( options.visibleStyle, 300 ).removeClass('mg_disabled');
			$hidden.clearQueue().animate( options.hiddenStyle, 300 ).addClass('mg_disabled');
		});
	};
	

	// link items + text under - fix
	jQuery('body').delegate('.mg_link .mg_title_under', 'click', function(e) {
		e.preventDefault();
		
		var $subj = jQuery(this).parents('.mg_link').find('.mg_link_elem');
		window.open($subj.attr('href'), $subj.attr('target'));
	});
	
	
	// video poster - handle click
	jQuery(document).ready(function() {
		// grid item
		jQuery('body').delegate('.mg_inl_video:not(.mg_disabled)', 'click'+mg_generic_touch_event, function(e){
			if( jQuery(this).find('.img_wrap .thumb').size() > 0 ) {
				var autop = jQuery(this).find('.thumb').attr('autoplay-url');
				if(typeof(autop) != 'undefined') {
					jQuery(this).find('iframe').attr('src', autop);	
				}

				jQuery(this).find('.img_wrap iframe').show();
				jQuery(this).find('.img_wrap > div *').not('iframe').fadeOut(300, function() {
					jQuery(this).remove();	
				});
			}
		});
		
		// lightbox
		jQuery('body').delegate('#mg_lb_video_wrap', 'click'+mg_generic_touch_event, function(e){
			if( !jQuery(e.target).hasClass('mg_self-hosted-video') ) {
				var autop = jQuery('.mg_lb_video_poster').attr('autoplay-url');
				if(typeof(autop) != 'undefined') {
					jQuery('#mg_lb_video_wrap').find('iframe').attr('src', autop);	
				}
				
				jQuery('#mg_lb_video_wrap').find('*').not('iframe').remove();
				jQuery('#mg_lb_video_wrap').find('iframe').show();
			}
		});
	});
	
	
	// touch devices hover effects
	if( mg_is_touch_device() ) {
		jQuery('.mg_box').bind('touchstart', function() { jQuery(this).addClass('mg_touch_on'); });
		jQuery('.mg_box').bind('touchend', function() { jQuery(this).removeClass('mg_touch_on'); });
	}
	
	/////////////////////////////////////
	
	// debounce resize to trigger only once
	mg_debouncer = function($,cf,of, interval){
		var debounce = function (func, threshold, execAsap) {
			var timeout;
			
			return function debounced () {
				var obj = this, args = arguments;
				function delayed () {
					if (!execAsap) {func.apply(obj, args);}
					timeout = null;
				}
			
				if (timeout) {clearTimeout(timeout);}
				else if (execAsap) {func.apply(obj, args);}
				
				timeout = setTimeout(delayed, threshold || interval);
			};
		};
		jQuery.fn[cf] = function(fn){ return fn ? this.bind(of, debounce(fn)) : this.trigger(cf); };
	};
	

	// adjust cell sizes on browser resize
	mg_debouncer(jQuery,'mg_smartresize', 'resize', 49);
	jQuery(window).mg_smartresize(function() {
		mg_item_img_switch();
		mg_responsive_txt();
		
		jQuery('.mg_container').each(function() {
			var mg_cont_id = jQuery(this).attr('id');
			mg_size_boxes(mg_cont_id, true);
		});
	});
	
	
	/////////////////////////////////////
	// lightbox deeplinking
	
	function mg_get_deeplink() {
		if(jQuery('#mg_full_overlay').size() == 0) {
			mg_append_lightbox();
		}
		
		var hash = location.hash;
		if(hash == '' || hash == '#!mg') {return false;}
		
		if (hash.indexOf('#!mg_ld') !== -1) {
			var val = hash.substring(hash.indexOf('#!mg_ld')+8, hash.length)
			
			// check the item existence
			if( jQuery('.mg_closed[rel=pid_' + val + ']') ) {
				$mg_sel_grid = jQuery('.mg_box[rel=pid_'+ val +']').parents('.mg_container');	
				mg_open_item(val);
			}
		}
	};
	
	
	function mg_set_deeplink(subj, val) {
		if( jQuery('.mg_grid_wrap').hasClass('mg_deeplink') ) {
			mg_clear_deeplink();
	
			var mg_hash = (subj == 'cat') ? 'mg_cd' : 'mg_ld';  
			location.hash = '!' + mg_hash + '=' + val;
		}
	};
	
	
	function mg_clear_deeplink() {
		if( jQuery('.mg_grid_wrap').hasClass('mg_deeplink') ) {
			var curr_hash = location.hash;

			// find if a mg hash exists
			if(curr_hash.indexOf('#!mg_cd') !== false || curr_hash.indexOf('#!mg_ld') !== false) {
				location.hash = 'mg';
			}
		}
	};
	
	
	/////////////////////////////////////
	// galleria slider functions
	
	// manage the slider initial appearance
	mg_galleria_show = function(sid) {
		setTimeout(function() {
			if( jQuery(sid+' .galleria-stage').size() > 0) {
				jQuery(sid).removeClass('mg_show_loader');
				jQuery(sid+' .galleria-container').fadeTo(400, 1);	
				jQuery('.mg_item_featured').css('max-height', 'none').css('overflow', 'visible');
			} else {
				mg_galleria_show(sid);	
			}
		}, 50);
	};
	
	
	// manage the slider proportions on resize
	mg_galleria_height = function(sid) {
		if( jQuery(sid).hasClass('mg_galleria_responsive')) {
			return parseFloat( jQuery(sid).attr('asp-ratio') );
		} else {
			return jQuery(sid).height();	
		}
	};
	
	
	mg_galleria_resize = function() {	
		if(jQuery('.mg_galleria_responsive').size() > 0) {
			var slider_w = jQuery('.mg_galleria_responsive').width();
			var mg_asp_ratio = parseFloat(jQuery('.mg_galleria_responsive').attr('asp-ratio'));
			var new_h = Math.ceil( slider_w * mg_asp_ratio );
			jQuery('.mg_galleria_responsive').css('height', new_h);
		}
	};
	

	/* initialize inline sliders */
	mg_inl_slider_init = function(sid) {
        if(typeof(sid) == 'undefined') {
			jQuery('.mg_inl_slider_wrap').each(function(i, v) {
				var sid = jQuery(this).attr('id');
				mg_slider_autoplay['#'+sid] = (jQuery(this).hasClass('mg_autoplay_slider')) ? true : false; 
		
				var delay = (navigator.userAgent.indexOf('iPhone') != 1) ? 1000 : 100;
				setTimeout(function() {
					jQuery('#' + sid).fadeTo(50, 1);
					mg_galleria_init('#' + sid, true);
				}, delay);
			});
		}
		else {
			mg_slider_autoplay['#'+sid] = (jQuery('#'+sid).hasClass('mg_autoplay_slider')) ? true : false; 
			jQuery('#' + sid+' img').each(function() {
                jQuery(this).attr('src', jQuery(this).attr('lazy-slider-img'));
            });
			
			setTimeout(function() {
				jQuery('#' + sid).fadeTo(50, 1);
				mg_galleria_init('#' + sid, true);
			}, 200);
		}
    };
	
	
	// Initialize Galleria
	mg_galleria_init = function(sid, inline_slider) {
		Galleria.run(sid, {
			theme: 'mediagrid',
			height: mg_galleria_height(sid),
			swipe: (inline_slider) ? mg_lb_touchswipe : false,
			thumbnails: (typeof(inline_slider) != 'undefined') ? 'empty' : true, 
			transition: mg_galleria_fx,
			wait: true,

			initialTransition: 'flash',
			transitionSpeed: mg_galleria_fx_time,
			imageCrop: (typeof(inline_slider) != 'undefined') ? true : mg_galleria_img_crop,
			extend: function() {
				var mg_slider_gall = this;
				jQuery(sid+' .galleria-loader').append(mg_loader);
				
				if(typeof(mg_slider_autoplay[sid]) != 'undefined' && mg_slider_autoplay[sid]) {
					jQuery(sid+' .galleria-mg-play').addClass('galleria-mg-pause');
					mg_slider_gall.play(mg_galleria_interval);	
				}
				
				// play-pause
				jQuery(sid+' .galleria-mg-play').click(function() {
					jQuery(this).toggleClass('galleria-mg-pause');
					mg_slider_gall.playToggle(mg_galleria_interval);
				});

				// thumbs navigator toggle
				jQuery(sid+' .galleria-mg-toggle-thumb').click(function() {
					var $mg_slider_wrap = jQuery(this).parents('.mg_galleria_slider_wrap');
					
					
					if( $mg_slider_wrap.hasClass('galleria-mg-show-thumbs') || $mg_slider_wrap.hasClass('mg_galleria_slider_show_thumbs') ) {
						$mg_slider_wrap.stop().animate({'padding-bottom' : '0px'}, 400);
						$mg_slider_wrap.find('.galleria-thumbnails-container').stop().animate({'bottom' : '10px', 'opacity' : 0}, 400);
						
						$mg_slider_wrap.removeClass('galleria-mg-show-thumbs');
						if( $mg_slider_wrap.hasClass('mg_galleria_slider_show_thumbs') ) {
							$mg_slider_wrap.removeClass('mg_galleria_slider_show_thumbs')
						}
					} 
					else {
						$mg_slider_wrap.stop().animate({'padding-bottom' : '56px'}, 400);
						$mg_slider_wrap.find('.galleria-thumbnails-container').stop().animate({'bottom' : '-60px', 'opacity' : 1}, 400);	
						
						$mg_slider_wrap.addClass('galleria-mg-show-thumbs');
					}
				});
				
				// interval control for resizing issues
				if(typeof(inline_slider) == 'undefined') {
					mg_galleria_size_check = setInterval(function() {
						if( jQuery('.galleria-container').css('opacity') == '1' && jQuery('.mg_galleria_slider_wrap').width() != jQuery('.galleria-stage').width()) {
							mg_galleria_resize();
							mg_slider_gall.resize();
						}
					}, 100);
				}
			}
		});
	};
	

	/////////////////////////////////////
	// utilities
	
	function mg_responsive_txt(gid) {
		var selector = (typeof(gid) != 'undefined') ? '#'+gid+' ' : '';
		var $subj = jQuery(selector + '.mg_inl_txt_td').find('p, b, div, span, strong, em, i, h6, h5, h4, h3, h2, h1'); 

		// setup original text sizes and reset
		$subj.each(function() {
			if(typeof( jQuery(this).attr('orig-size') ) == 'undefined') {
				jQuery(this).attr('orig-size', jQuery(this).css('font-size'));	
				jQuery(this).attr('orig-lheight', jQuery(this).css('line-height'));
			}
			
			// reset
			jQuery(this).removeClass('mg_min_reached mg_inl_txt_top_margin_fix mg_inl_txt_btm_margin_fix mg_inl_txt_top_padding_fix mg_inl_txt_btm_padding_fix');
			jQuery(this).css('font-size', jQuery(this).attr('orig-size'));
			jQuery(this).css('line-height', jQuery(this).attr('orig-lheight'));
        });
		
		jQuery(selector + '.mg_inl_txt_td').each(function() {
			// not for auto-height
			if(
				(!mg_mobile_mode && !jQuery(this).parents('.mg_box').hasClass('rowauto')) ||
				(mg_mobile_mode && !jQuery(this).parents('.mg_box').hasClass('m_rowauto'))
			) {
				var max_height = jQuery(this).parents('.img_wrap').height();
	
				if(max_height < jQuery(this).outerHeight()) {
					var a = 0;
					while( max_height < jQuery(this).outerHeight()) {
						if(a == 0) {
							// check and eventually reduce big margins and paddings at first
							$subj.each(function(i, v) {
								if( parseInt(jQuery(this).css('margin-top')) > 10 ) {jQuery(this).addClass('mg_inl_txt_top_margin_fix');}	
								if( parseInt(jQuery(this).css('margin-bottom')) > 10 ) {jQuery(this).addClass('mg_inl_txt_btm_margin_fix');}	
								
								if( parseInt(jQuery(this).css('padding-top')) > 10 ) {jQuery(this).addClass('mg_inl_txt_top_padding_fix');}	
								if( parseInt(jQuery(this).css('padding-bottom')) > 10 ) {jQuery(this).addClass('mg_inl_txt_btm_padding_fix');}	
							});
						}
						else {
							$subj.each(function(i, v) {
								var new_size = parseFloat( jQuery(this).css('font-size')) - 1;
								if(new_size < 12) {new_size = 12;}
								
								var new_lheight = parseInt( jQuery(this).css('line-height')) - 1;
								if(new_lheight < 15) {new_lheight = 15;}
								
								jQuery(this).css('font-size', new_size).css('line-height', new_lheight+'px');
								
								if(new_size == 12 && new_lheight == 15) { // resizing limits
									jQuery(this).addClass('mg_min_reached');	
								}
							});	
							
							// if any element has reached min size
							if( jQuery(selector + '.mg_inl_txt_td .mg_min_reached').size() ==  $subj.size()) {
								return false;	
							}
						}
						
						a++;
					}
				}
			}
        });
	}
	
	// check for touch device
	function mg_is_touch_device() {
		return !!('ontouchstart' in window);
	};
	

	// check if the browser is IE8 or older
	function mg_is_old_IE() {
		if( navigator.appVersion.indexOf("MSIE 8.") != -1 ) {return true;}
		else {return false;}
	};

	// check if mobile browser
	function mg_is_mobile() {
		if( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent) ) 
		{ return true;}
		else { return false; }
	}


})(jQuery);


/////////////////////////////////////
// Image preloader v1.01
(function($) {	
	$.fn.lcweb_lazyload = function(lzl_callbacks) {
		lzl_callbacks = jQuery.extend({
			oneLoaded: function() {},
			allLoaded: function() {}
		}, lzl_callbacks);

		var lzl_loaded = 0, 
			lzl_url_array = [], 
			lzl_width_array = [], 
			lzl_height_array = [], 
			lzl_img_obj = this;
		
		var check_complete = function() {
			if(lzl_url_array.length == lzl_loaded) {
				lzl_callbacks.allLoaded.call(this, lzl_url_array, lzl_width_array, lzl_height_array); 
			}
		};

		var lzl_load = function() {
			jQuery.map(lzl_img_obj, function(n, i){
                lzl_url_array.push( $(n).attr('src') );
            });
			
			jQuery.each(lzl_url_array, function(i, v) {
				if( jQuery.trim(v) == '' ) {console.log('empty img url - ' + (i+1) );}
				
				$('<img />').bind("load.lcweb_lazyload",function(){ 
					if(this.width == 0 || this.height == 0) {


						setTimeout(function() {
							lzl_width_array[i] = this.width;
							lzl_height_array[i] = this.height;
							
							lzl_loaded++;
							check_complete();
						}, 70);
					}
					else {
						lzl_width_array[i] = this.width;
						lzl_height_array[i] = this.height;
						lzl_loaded++;
						check_complete();
					}
				}).attr('src',  v);
			});
		};
		
		return lzl_load();
	}; 
	
})(jQuery);
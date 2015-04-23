jQuery(document).ready(function($) {
	
	// switch theme menu pages
	jQuery('.lcwp_opt_menu').click(function() {
		curr_opt = jQuery('.curr_item').attr('id').substr(5);
		var opt_id = jQuery(this).attr('id').substr(5);
		
		if(!jQuery('#form_'+opt_id).is(':visible')) {
			// remove curr
			jQuery('.curr_item').removeClass('curr_item');
			jQuery('#form_'+curr_opt).hide();
			
			// show selected
			jQuery(this).addClass('curr_item');
			jQuery('#form_'+opt_id).show();	
		}
	});
	
	
	// colorpicker
	mg_colpick = function () {
		jQuery('.lcwp_colpick input').each(function() {
			var curr_col = jQuery(this).val().replace('#', '');
			jQuery(this).colpick({
				layout:'rgbhex',
				submit:0,
				color: curr_col,
				onChange:function(hsb,hex,rgb, el, fromSetColor) {
					if(!fromSetColor){ 
						jQuery(el).val('#' + hex);
						jQuery(el).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color','#'+hex);
					}
				}
			}).keyup(function(){
				jQuery(this).colpickSetColor(this.value);
				jQuery(this).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color', this.value);
			});  
		});
	}
	mg_colpick();
	
	
	// sliders
	mg_slider_opt = function() {
		var a = 0; 
		$('.lcwp_slider').each(function(idx, elm) {
			var sid = 'slider'+a;
			jQuery(this).attr('id', sid);	
		
			svalue = parseInt(jQuery("#"+sid).next('input').val());
			minv = parseInt(jQuery("#"+sid).attr('min'));
			maxv = parseInt(jQuery("#"+sid).attr('max'));
			stepv = parseInt(jQuery("#"+sid).attr('step'));
			
			jQuery('#' + sid).slider({
				range: "min",
				value: svalue,
				min: minv,
				max: maxv,
				step: stepv,
				slide: function(event, ui) {
					jQuery('#' + sid).next().val(ui.value);
				}
			});
			jQuery('#'+sid).next('input').change(function() {
				var val = parseInt(jQuery(this).val());
				var minv = parseInt(jQuery("#"+sid).attr('min'));
				var maxv = parseInt(jQuery("#"+sid).attr('max'));
				
				if(val <= maxv && val >= minv) {
					jQuery('#'+sid).slider('option', 'value', val);
				}
				else {
					if(val <= maxv) {jQuery('#'+sid).next('input').val(minv);}
					else {jQuery('#'+sid).next('input').val(maxv);}
				}
			});
			
			a = a + 1;
		});
	}
	mg_slider_opt();
	
	
	// custom checks
	jQuery('.ip-checkbox').lc_switch('YES', 'NO');
	
	// chosen
	jQuery('.lcweb-chosen').each(function() {
		var w = jQuery(this).css('width');
		jQuery(this).chosen({width: w}); 
	});
	jQuery(".lcweb-chosen-deselect").chosen({allow_single_deselect:true});
	
	
	//////////////////////////////////////////
	// tinymce btn

	// resize and center
	mg_H = 450;
	mg_W = 555;
	
	jQuery('body').delegate('#mg_editor_btn', "click", function () {
		setTimeout(function() {
			tb_show( 'Media Grid', '#TB_inline?height='+mg_H+'&width='+mg_W+'&inlineId=mg_popup_container' );
			
			jQuery('#TB_window').css("height", mg_H);
			jQuery('#TB_window').css("width", mg_W);	
			
			jQuery('#TB_window').css("top", ((jQuery(window).height() - mg_H) / 4) + 'px');
			jQuery('#TB_window').css("left", ((jQuery(window).width() - mg_W) / 4) + 'px');
			jQuery('#TB_window').css("margin-top", ((jQuery(window).height() - mg_H) / 4) + 'px');
			jQuery('#TB_window').css("margin-left", ((jQuery(window).width() - mg_W) / 4) + 'px');

			// custom checks
			jQuery('.mg_popup_ip').lc_switch('YES', 'NO');
			
			jQuery('#TB_ajaxContent #mg_grid_choose').trigger('change');	
		}, 1);	
	});
	
	jQuery(window).resize(function() {
		if(jQuery('#lcwp_tinymce_table').is(':visible')) {
			jQuery('#lcwp_tinymce_table').parent().parent().css("height", mg_H);
			jQuery('#lcwp_tinymce_table').parent().parent().css("width", mg_W);	
			
			jQuery('#lcwp_tinymce_table').parent().parent().css("top", ((jQuery(window).height() - mg_H) / 4) + 'px');
			jQuery('#lcwp_tinymce_table').parent().parent().css("left", ((jQuery(window).width() - mg_W) / 4) + 'px');
			jQuery('#lcwp_tinymce_table').parent().parent().css("margin-top", ((jQuery(window).height() - mg_H) / 4) + 'px');
			jQuery('#lcwp_tinymce_table').parent().parent().css("margin-left", ((jQuery(window).width() - mg_W) / 4) + 'px');
		}
	});
	
	
	// toggle filters options visibility
	jQuery('body').delegate('.mg_scw_filter_toggle input', 'lcs-statuschange', function() {
		if( jQuery(this).is(':checked') ) {
			jQuery('.mg_scw_ff').slideDown('fast');	
		} else {
			jQuery('.mg_scw_ff').slideUp('fast');	
		}
	});
	
	
	// populate default filter dropdown on grid chosing
	jQuery('body').delegate('#TB_ajaxContent #mg_grid_choose', 'change', function() {
		var sel = jQuery(this).val();
		jQuery('#TB_ajaxContent #mg_def_filter').empty();

		jQuery.each(mg_def_f[sel], function(k, v) {
			jQuery('#TB_ajaxContent #mg_def_filter').prepend('<option value="'+ k +'">'+ v +'</option>');
		});
		jQuery('#TB_ajaxContent #mg_def_filter option').first().attr('selected', 'selected');
		
		jQuery("#TB_ajaxContent #mg_def_filter").trigger("chosen:updated");
	});
	
	
	// add the shortcode to the grid
	jQuery('body').delegate('#mg_insert_grid', "click", function (e) {

		var gid = jQuery('#mg_grid_choose').val();
		var sc = '[mediagrid cat="'+gid+'"';
		
		//  titles under
		if( jQuery('#mg_title_under').is(':checked') ) {
			sc += ' title_under="1"';
		}

		// filter
		if( jQuery('#mg_filter_grid').is(':checked') ) {
			var filter = 1;
			sc += ' filter="'+filter+'"';
		} 
		else {var filter = 0;}
		
		
		// filter options
		if(filter) {
			// hide "all" filter
			if( jQuery('#mg_hide_all').is(':checked') ) {
				sc += ' hide_all="1"';
			}
			
			// select default filter
			if( jQuery('#mg_def_filter').val() != '' ) {
				sc += ' def_filter="'+ jQuery('#mg_def_filter').val() +'"';
			}
		}
		
		
		// relative width
		if( jQuery.trim(jQuery('#mg_grid_w').val()) != '' ) {
			sc += ' r_width="'+ jQuery.trim(jQuery('#mg_grid_w').val()) +'"';
		}
		
		// custom overlay - add-on
		if( jQuery('#mg_custom_overlay').size() > 0 && jQuery('#mg_custom_overlay').val() != '' ) {
			sc += ' overlay="'+ jQuery('#mg_custom_overlay').val() +'"';	
		}

		sc += ']';
		
		// inserts the shortcode into the active editor
		if( jQuery('#wp-content-editor-container > textarea').is(':visible') ) {
			var val = jQuery('#wp-content-editor-container > textarea').val() + sc;
			jQuery('#wp-content-editor-container > textarea').val(val);	
		}
		else {tinyMCE.activeEditor.selection.setContent(sc);}
		
		// closes Thickbox
		tb_remove();
	});
	
});
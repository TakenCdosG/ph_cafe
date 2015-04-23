<?php
// prevent right click - global vars and Galleria init

// frontent JS on header or footer
if(get_option('mg_js_head') != '1') {
	add_action('wp_footer', 'mg_js_flags', 90);
} else { 
	add_action('wp_head', 'mg_js_flags', 850);
}


function mg_js_flags() {
	include_once(MG_DIR.'/functions.php');
	
	$delayed_fx 	= (get_option('mg_delayed_fx')) ? 'false' : 'true';
	$modal_class 	= (get_option('mg_modal_lb')) ? 'mg_modal_lb' : 'mg_classic_lb';
	$box_border 	= (get_option('mg_cells_border')) ? 1 : 0;
	$lb_vert_center = (get_option('mg_lb_not_vert_center')) ? 'false' : 'true';
	$lb_touchswipe 	= (get_option('mg_lb_touchswipe')) ? 'true' : 'false';
	$woocom 		= (mg_woocomm_active()) ? 'true' : 'false';
	?>
	<script type="text/javascript">
	// Media Grid global dynamic vars
	mg_boxMargin = <?php echo (int)get_option('mg_cells_margin') ?>;
	mg_boxBorder = <?php echo $box_border ?>;
	mg_imgPadding = <?php echo (int)get_option('mg_cells_img_border') ?>;
	mg_delayed_fx = <?php echo $delayed_fx ?>;
	mg_filters_behav = '<?php echo get_option('mg_filters_behav', 'standard') ?>';
	mg_lightbox_mode = "<?php echo $modal_class ?>";
	mg_lb_touchswipe = <?php echo $lb_touchswipe ?>;
	mg_mobile = <?php echo get_option('mg_mobile_treshold', 800) ?>; 

	// Galleria global vars
	mg_galleria_fx = '<?php echo get_option('mg_slider_fx', 'fadeslide') ?>';
	mg_galleria_fx_time = <?php echo get_option('mg_slider_fx_time', 400) ?>; 
	mg_galleria_interval = <?php echo get_option('mg_slider_interval', 3000) ?>;
	</script>	
	<?php
    
	// if prevent right click
	if(get_option('mg_disable_rclick')) :
		?>
        <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('body').delegate('.mg_grid_wrap *, #mg_full_overlay *, #mg_wp_video_wrap .wp-video *', "contextmenu", function(e) {
                e.preventDefault();
            });
		});
		</script>
        <?php	
	endif;
}

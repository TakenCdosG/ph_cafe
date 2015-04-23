<?php
// if deeplink is active and google crawler is acting - return item in page content

add_action('wp_footer', 'mg_deeplink_snapshot', 999);
function mg_deeplink_snapshot() {
	if(!get_option('mg_disable_dl') && isset($_REQUEST['_escaped_fragment_'])) {
		$val = explode('=', $_REQUEST['_escaped_fragment_']);
		
		// detect if is calling MG item and its id
		if(is_array($val) && count($val) == 2 && $val[0] == 'mg_ld') {
			if( get_post_status((int)$val[1]) == 'publish') {
				// get lightbox contents
				ob_start();
				mg_lightbox($val[1], false, false);
				$contents = ob_get_clean();
			
				echo '
				<div id="mg_full_overlay" class="google_crawler" style="display: block;">
					<div id="mg_overlay_content">
						'. $contents .'
					</div>
				</div>
				<div id="mg_full_overlay_wrap" class="google_crawler"></div>';
				
				echo '
				<script type="text/javascript">
				jQuery("html, body").css("overflow", "hidden");
				</script>';
				
			}
		}
	}
}

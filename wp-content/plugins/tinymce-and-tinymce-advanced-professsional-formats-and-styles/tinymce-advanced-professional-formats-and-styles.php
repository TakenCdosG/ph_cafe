<?php
/*
Plugin Name: TinyMCE and TinyMCE Advanced Professsional Formats and Styles
Plugin URI: http://www.blackbam.at/blog/
Description: Improve, style and completly customize your TinyMCE, TinyMCE Advanced (and some other variants) by using custom editor stylesheets in combination with a dynamically configurable styles dropdown.
Author: David Stöckl
Version: 1.1.2
Author URI: http://www.blackbam.at/blog/
License: GPLv3
 *
 * Note: This Plugins is GPLv2 licensed. This Plugin is released without any warranty. 
 *
*/

define('TAPS_TEXTDOMAIN','tinymce-and-tinymce-advanced-professional-styles');

// 1. localization
add_action('init','bb_taps_localization');

function bb_taps_localization() {
	load_plugin_textdomain( TAPS_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
} 

/* 1. Version check */
global $wp_version;

$exit_msg=sprintf(__('This Plugin requires WordPress version 3.0 or higher. %sPlease update!%s',TAPS_TEXTDOMAIN),'<a href="http://codex.wordpress.org/Upgrading_Wordpress">','</a>');

if(!version_compare($wp_version,"2.9",">")) {
	exit ($exit_msg);
}

/* 2. Install / Uninstall */
register_activation_hook(__FILE__,"bb_taps_activate");

function bb_taps_activate() {
	
	register_uninstall_hook(__FILE__,"bb_taps_uninstall");
}

function bb_taps_uninstall() {
	delete_option('bb_taps_addstyledrop');
}

function bb_taps_admin_message($message, $errormsg = false){
	if ($errormsg) {
		echo '<div id="message" class="error">';
	} else {
		echo '<div id="message" class="updated fade">';
	}
	echo "<p><strong>$message</strong></p></div>";
}

// Returns the current Styles url
function bb_taps_get_style_url($style_name) {
	$keyword = get_option("bb_taps_locstyle");
	
	$http_path="";
	
	if($keyword=="themes_directory") {
		$http_path = get_bloginfo('template_url')."/".$style_name;
	} else if($keyword=="themes_child_directory") {
		$http_path = get_stylesheet_directory_uri()."/".$style_name;
	} else if($keyword=="custom_directory") {
		$http_path= content_url()."/".get_option("bb_taps_cuslink").$style_name;
	}
	
	return $http_path;
}

// Returns the url of the styles directory
function bb_taps_get_style_server_path($style_name) {
	
	$keyword = get_option("bb_taps_locstyle");
	
	$server_side_path="";
	
	if($keyword=="themes_directory") {
		$server_side_path = get_template_directory()."/".$style_name;
	} else if($keyword=="themes_child_directory") {
		$server_side_path = get_stylesheet_directory()."/".$style_name;
	} else if($keyword=="custom_directory") {
		$server_side_path = WP_CONTENT_DIR."/".get_option('bb_taps_cuslink').$style_name;
	}
	
	return $server_side_path;
}


function bb_taps_createAndSetEditorStyles($keyword,$custom_path="") {
	
	// required
	if(!in_array($keyword,array("themes_directory","themes_child_directory","custom_directory"))) {
		add_action('admin_notices', 'bb_taps_cannot_create');
		return;
	}
	
	update_option("bb_taps_locstyle",$keyword);
	update_option("bb_taps_cuslink",$custom_path);
	
	$create_error = false;
	
	// create files, if these not exist
	if (!file_exists(bb_taps_get_style_server_path("editor-style.css"))) {
		$content = "/** These styles are used in your backend editor. **/";
		
		$fp = @fopen(bb_taps_get_style_server_path("editor-style.css"),"wb");
		if($fp!=false) {
			fwrite($fp,$content);
			fclose($fp);
		} else {
			$create_error=true;
		}
	}
	
	if (!file_exists(bb_taps_get_style_server_path("editor-style-shared.css"))) {
		$content = "/** These styles are used in the backend editor AND in your Theme. **/";
		$fp = @fopen(bb_taps_get_style_server_path("editor-style-shared.css"),"wb");
		if($fp!=false) {
			fwrite($fp,$content);
			fclose($fp);
		} else {
			$create_error=true;
		}
	}
	
	if($create_error) {
		add_action('admin_notices', 'bb_taps_cannot_create');
	}
}

function bb_taps_cannot_create() {
	bb_taps_admin_message(__("The Plugin does not have the required permissions to create the files edtior-style.css and editor-style-shared.css automatically. Please create these files manually to the specified folder on your server.",TAPS_TEXTDOMAIN));
}


// 3.  Add settings link on plugin page
function bb_taps_settings_link($links) { 
  $settings_link = sprintf(__('%s Settings %s',TAPS_TEXTDOMAIN),'<a href="options-general.php?page=tinymce-and-tinymce-advanced-professsional-formats-and-styles/tinymce-advanced-professional-formats-and-styles.php">','</a>');
  $donate_link = sprintf(__('%s Donate %s',TAPS_TEXTDOMAIN),'<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VNS6BVGVH69P6">','</a>');
  array_unshift($links, $donate_link); 
  array_unshift($links, $settings_link);
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'bb_taps_settings_link' );


/** Add user defined editor styles **/

add_filter( 'mce_css', 'bb_taps_tinymce_css',100); 
 
function bb_taps_tinymce_css($wp) {
        $wp .= ',' . bb_taps_get_style_url("editor-style.css");
		$wp .= ',' . bb_taps_get_style_url("editor-style-shared.css");
        return $wp;
}

// add stylesheet to theme, too
add_action( 'wp_enqueue_scripts', 'bb_taps_add_stylesheet' );

function bb_taps_add_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'bb-taps-editor-style-shared', bb_taps_get_style_url("editor-style-shared.css") );
    wp_enqueue_style( 'bb-taps-editor-style-shared' );
}


// Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'my_mce_buttons_2');


// Callback function to filter the MCE settings
function bb_taps_mce_before_init_insert_formats( $init_array ) {  
	// Define the style_formats array
	$style_formats = get_option('bb_taps_addstyledrop');
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'bb_taps_mce_before_init_insert_formats' ); 


/********** Backend Page *********************/
// add the backend menu page
add_action('admin_menu','bb_taps_options');

// add the options page
function bb_taps_options() {
	add_options_page('TinyMCE Advanced Professional Styles','TinyMCE prof. Styles','manage_options',__FILE__,'bb_taps_backend_page');
}

function bb_taps_backend_page() { ?>
		<div class="wrap">
			<div><?php screen_icon('options-general'); ?></div>
			<h2><?php _e('Settings: TinyMCE Advanced Professional Styles','custom-header-images'); ?></h2>
			<?php
			if(isset($_POST['bb_taps_backend_update']) && $_POST['bb_taps_backend_update']!="") {
				
				// check if themes folder changed
				if($_POST["bb_taps_locstyle"]!=get_option('bb_taps_locstyle')) {
					bb_taps_createAndSetEditorStyles($_POST["bb_taps_locstyle"],$_POST["bb_taps_cuslink"]);
				}
				
				// Update Contents
	       		$all = intval($_POST['addstyledrop_number']);
				
				$all_options = array();
				$allowed = false;
				$errors = array();
				
				// tag type requires tag
				
		        // Wordpress sometimes tries to escape the HTML when saving something to wp-options
		        for($i=1;$i<=$all;$i++) {
		        	$allowed=true;
					$field0 = $_POST["addstyledrop_0_".$i];
		        	$field1 = $_POST["addstyledrop_1_".$i];
					$field3 = $_POST["addstyledrop_3_".$i];
					$field4 = $_POST["addstyledrop_4_".$i];
					$field7 = intval($_POST["addstyledrop_7_".$i]);
					$field8 = intval($_POST["addstyledrop_8_".$i]);
					
					// if there is no title, the row will be deleted
					if($field0!="") {
						
						if(!in_array($field1,array("inline","block","selector"))) {
							$allowed=false;
							array_push($errors,__("Settings row not saved: Row ".$i." option was not checked.","ultrasimpleshop"));
						}
						
						if($field3=="") {
							$allowed=false;
							array_push($errors,__("Settings row not saved: Row ".$i." must not be empty.","ultrasimpleshop"));
						}
						
			        	if($allowed) {
			        		$checked_row = array();
							$checked_row["title"] = $field0;
							$checked_row[$field1] = $field3;
							$checked_row["classes"] = $field4;
							
							// save the custom styles
							$styles_to_check = intval($_POST["tpcount_5_".$i]);
							$ready_styles = array();
							
							for($a=1; $a<=$styles_to_check;$a++) {
								if($_POST['addstyledrop_5_'.$i.'_'.$a.'_key']!="" && $_POST['addstyledrop_5_'.$i.'_'.$a.'_val']!="") {
									$ready_styles[$_POST['addstyledrop_5_'.$i.'_'.$a.'_key']] = $_POST['addstyledrop_5_'.$i.'_'.$a.'_val'];
								}
							}
							$checked_row["styles"] = $ready_styles;
							
							// save the custom attributes
							$styles_to_check = intval($_POST["tpcount_6_".$i]);
							$ready_attribs = array();
							
							for($a=1; $a<=$styles_to_check;$a++) {
								if($_POST['addstyledrop_6_'.$i.'_'.$a.'_key']!="" && $_POST['addstyledrop_6_'.$i.'_'.$a.'_val']!="") {
									$ready_attribs[$_POST['addstyledrop_6_'.$i.'_'.$a.'_key']] = $_POST['addstyledrop_6_'.$i.'_'.$a.'_val'];
								}
							}
							$checked_row["attributes"] = $ready_attribs;
							
							if($field7==1) {
								$checked_row["exact"] = true;
							} else {
								$checked_row["exact"] =false;
							}
							if($field8==1) {
								$checked_row["wrapper"] = true;
							} else {
								$checked_row["wrapper"] = false;
							}
							
			        		array_push($all_options,$checked_row);
			        	}
					}
					

		        }
				update_option('bb_taps_addstyledrop',$all_options);
				
				if(!empty($errors)) {
					foreach($errors as $error) { ?>
					<div class="error"> 
						<p><strong><?php echo $error; ?></strong></p>
					</div>
				<?php }
				} else {
					?>
						<div class="updated settings-error"> 
							<p><strong><?php _e('Settings saved successfully.',TAPS_TEXTDOMAIN); ?></strong></p>
						</div>
				<?php
				}
			
			}
			// get the data
			$data = get_option('chi_data');
			?>
			<form method="post" action="">
				<p><?php printf(__('A good WordPress Plugin means a lot of work. Please consider %s donating %s if you like it. Thank you.',TAPS_TEXTDOMAIN),'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VNS6BVGVH69P6">','</a>'); ?></p>
				
				<h3><?php _e('Change the visual style of your TinyMCE',TAPS_TEXTDOMAIN); ?></h3>
				<p><?php _e('To change the visual style appearance of your TinyMCE visual editor, we have to create two stylesheets: editor-style.css (TinyMCE only) and editor-style-shared.css (both, TinyMCE and your theme).',TAPS_TEXTDOMAIN); ?></p>
				<p><?php _e('Please choose a location for your files:',TAPS_TEXTDOMAIN); ?></p>
				<p><input type="radio" name="bb_taps_locstyle" value="themes_directory" <?php if(get_option('bb_taps_locstyle')=="themes_directory") {?>checked="checked" <?php } ?> /> Directory of current Theme (do <strong>not</strong> choose in case of automatically updated theme)</p>
				<p><input type="radio" name="bb_taps_locstyle" value="themes_child_directory" <?php if(get_option('bb_taps_locstyle')=="themes_child_directory") {?>checked="checked" <?php } ?> /> Directory of current Child Theme (do <strong>not</strong> choose in case of automatically updated child theme)</p>
				<p><input type="radio" name="bb_taps_locstyle" value="custom_directory" <?php if(get_option('bb_taps_locstyle')=="custom_directory") {?>checked="checked" <?php } ?> /> Advanced: Use any custom directory inside of wp-content: /wp-content/<input size="50" type="text" name="bb_taps_cuslink" value="<?php echo get_option("bb_taps_cuslink"); ?>" />editor-style[-shared].css</p>
				<p>
					<?php
					if (!file_exists(bb_taps_get_style_server_path("editor-style.css"))) {?>
						
						<p><span style="color:#f00; font-weight:bold;"><?php _e('Error: ',TAPS_TEXTDOMAIN); ?></span> <?php _e('The file "editor-style.css" was not found/could not be created in the directory you have specified. Please create this file.',TAPS_TEXTDOMAIN); ?></p>
					
					<?php
					} else if (!file_exists(bb_taps_get_style_server_path("editor-style-shared.css"))) {?>
						
						<p><span style="color:#f00; font-weight:bold;"><?php _e('Error: ',TAPS_TEXTDOMAIN); ?></span> <?php _e('The file "editor-style-shared.css" was not found/could not be created in the directory you have specified. Please create this file.',TAPS_TEXTDOMAIN); ?></p>
					
					<?php } else {
					
						if(get_option("bb_taps_locstyle")=="themes_directory") { ?>
							<?php printf(__('Edit your %s editor-style.css %s for the editor styles here.',TAPS_TEXTDOMAIN),'<a href="'.get_admin_url().'/theme-editor.php?file=editor-style.css" target="_blank">','</a>'); ?>
								<br/>
							<?php printf(__('Edit your %s editor-style-shared.css %s for the common editor AND theme styles here.',TAPS_TEXTDOMAIN),'<a href="'.get_admin_url().'/theme-editor.php?file=editor-style-shared.css" target="_blank">','</a>'); ?>
						<?php
						} else if(get_option("bb_taps_locstyle")=="themes_child_directory") { ?>	
							<?php printf(__('Create/edit your editor style located at %s on your server.',TAPS_TEXTDOMAIN),"<strong>".bb_taps_get_style_url("editor-style.css")."</strong>"); ?><br/>
							<?php printf(__('Create/edit your theme/editor shared style located at %s on your server.',TAPS_TEXTDOMAIN),"<strong>".bb_taps_get_style_url("editor-style-shared.css")."</strong>"); ?>
						<?php
						} else if(get_option("bb_taps_locstyle")=="custom_directory") { ?>
							<?php printf(__('Create/edit your editor style located at %s on your server.',TAPS_TEXTDOMAIN),"<strong>".bb_taps_get_style_url("editor-style.css")."</strong>"); ?><br/>
							<?php printf(__('Create/edit your theme/editor shared style located at %s on your server.',TAPS_TEXTDOMAIN),"<strong>".bb_taps_get_style_url("editor-style-shared.css")."</strong>"); ?>
						<?php } 
					}
					?>
				</p>
				
				<?php // The Table ?>
				<h3><?php _e('Manage your custom formats and styles for TinyMCE',TAPS_TEXTDOMAIN); ?></h3>
				<p><?php printf(__('%s This part %s of the official TinyMCE documentation will help you understanding this table.',TAPS_TEXTDOMAIN),'<a href="http://www.tinymce.com/wiki.php/Configuration:formats" target="_blank">','</a>'); ?></p>
				<p>&nbsp;</p>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php _e('Column',TAPS_TEXTDOMAIN); ?></th>
							<th><?php _e('Description',TAPS_TEXTDOMAIN); ?></th>
						</tr>
					</thead>
				<tbody>
				<tr>
				<td><strong><?php _e('Title',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('required',TAPS_TEXTDOMAIN); ?>]</td>
				<td><?php _e('The label for this dropdown item.',TAPS_TEXTDOMAIN); ?></td>
				</tr>
				<tr>
				<td><strong><?php _e('Selector',TAPS_TEXTDOMAIN); ?></strong> | <strong><?php _e('Block',TAPS_TEXTDOMAIN); ?></strong> | <strong><?php _e('Inline',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('required',TAPS_TEXTDOMAIN); ?>]</td>
				<td>
				<ul>
				<li><?php printf(__('%sSelector%s: Choose %s a valid CSS selector %s to limit this style to a specific HTML tag which will be applied to the style of an existing tag instead of creating a new one.',TAPS_TEXTDOMAIN),'<strong>','</strong>','<a href="http://www.w3schools.com/cssref/css_selectors.asp" target="_blank">','</a>'); ?></li>
				<li><?php printf(__('%sBlock%s: Choose %s a valid HTML block level element %s (f.e. blockquote) to create this new element with the style applied, which will replace the existing block element in TinyMCE around the cursor.',TAPS_TEXTDOMAIN),'<strong>','</strong>','<a href="https://developer.mozilla.org/en-US/docs/HTML/Block-level_elements" target="_blank">','</a>'); ?></li>
				<li><?php printf(__('%sInline%s: Choose %s a new HTML inline element %s (f.e. span) to create this new element with the style applied, which will wrap whatever is selected in the editor, not replacing any tags.',TAPS_TEXTDOMAIN),'<strong>','</strong>','<a href="https://developer.mozilla.org/en-US/docs/HTML/Inline_elements" target="_blank">','</a>'); ?></li>
				</ul>
				</td>
				</tr>
				<tr>
				<td><strong><?php _e('CSS Class(es)',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('optional',TAPS_TEXTDOMAIN); ?>]</td>
				<td><?php _e('A space-separated list of classes to apply to the element.',TAPS_TEXTDOMAIN); ?></td>
				</tr>
				<tr>
				<td><strong><?php _e('CSS Styles',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('optional',TAPS_TEXTDOMAIN); ?>]</td>
				<td><ul>
					<li><?php _e('Here you have the possibility to define CSS styles, which will be directly applied to the element.',TAPS_TEXTDOMAIN); ?></li>
					<li><strong><?php _e('Note:',TAPS_TEXTDOMAIN); ?></strong> <?php printf(__('Multi-word attributes, like %sfont-size%s, are written in Javascript-friendly camel case: %sfontSize%s)',TAPS_TEXTDOMAIN),'<em>','</em>','<em>','</em>'); ?></li>
					<li><strong><?php _e('Note:',TAPS_TEXTDOMAIN); ?></strong> <?php _e('It is more recommendable to use classes of your editor-style.css / editor-style-shared.css in most cases.',TAPS_TEXTDOMAIN); ?> </li>
				</ul>
				</td>
				</tr>
				<tr>
				<td><strong><?php _e('Attributes',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('optional',TAPS_TEXTDOMAIN); ?>]</td>
				<td><?php _e('Here you have the possibility to define HTML-Attributes, which will be applied to the element(s).',TAPS_TEXTDOMAIN); ?></td>
				</tr>
				<tr>
				<td><strong><?php _e('Wrapper',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('optional',TAPS_TEXTDOMAIN); ?>]</td>
				<td><?php _e('If you check this, selecting the style creates a new block-level element around any selected block-level elements.',TAPS_TEXTDOMAIN); ?></td>
				</tr>
				<tr>
				<td><strong><?php _e('Exact',TAPS_TEXTDOMAIN); ?></strong> [<?php _e('optional',TAPS_TEXTDOMAIN); ?>]</td>
				<td><?php _e('Checking this option disables the "merge similar styles" feature, needed for some CSS inheritance issues.',TAPS_TEXTDOMAIN); ?></td>
				</tr>
				</tbody>
				</table>
				<p>&nbsp;</p>
				<div id="taps_settings_table">
					<table class="widefat" id="bb_taps_addstyledrop">
						<thead>
							<tr valign="top">
								<th scope="row"><?php _e('Title *',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Type *',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Type Value *',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('CSS Class(es)',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('CSS Styles',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Attributes',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Exact',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Wrapper',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Add/Remove',TAPS_TEXTDOMAIN); ?></th>
							</tr>
						</thead>
						<tfoot>
							<tr valign="top">
								<th scope="row"><?php _e('Title *',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Type *',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Type Value *',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('CSS Class(es)',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('CSS Styles',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Attributes',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Exact',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Wrapper',TAPS_TEXTDOMAIN); ?></th>
								<th scope="row"><?php _e('Add/Remove',TAPS_TEXTDOMAIN); ?></th>
							</tr>
						</tfoot>
						<tbody>
						<?php
						$op_ct=1;
						
						$items = get_option('bb_taps_addstyledrop',array());
						
						foreach($items as $item) {
							$type="";
							$typeval="";
							if(array_key_exists('inline', $item)) {
								$type="inline";
								$typeval=$item["inline"];
							} else if(array_key_exists('block', $item)) {
								$type="block";
								$typeval=$item["block"];
							} else if(array_key_exists('selector', $item)) {
								$type="selector";
								$typeval=$item["selector"];
							}
							
							?>
						<tr valign="top" id="addstyledrop_row_<?php echo $op_ct; ?>">
							<td><input type="text" name="addstyledrop_0_<?php echo $op_ct; ?>" id="addstyledrop_0_<?php echo $op_ct; ?>" value="<?php echo $item['title']; ?>" /></td>
							<td>
								<input type="radio" value="inline" name="addstyledrop_1_<?php echo $op_ct; ?>" <?php if($type=="inline") { ?> checked="checked" <?php } ?> /> Inline&nbsp;&nbsp;
								<input type="radio" value="block" name="addstyledrop_1_<?php echo $op_ct; ?>" <?php if($type=="block") { ?> checked="checked" <?php } ?> /> Block&nbsp;&nbsp;
								<input type="radio" value="selector" name="addstyledrop_1_<?php echo $op_ct; ?>" <?php if($type=="selector") { ?> checked="checked" <?php } ?> /> Selector&nbsp;&nbsp;
							</td>
							<td><input type="text" name="addstyledrop_3_<?php echo $op_ct; ?>" id="addstyledrop_3_<?php echo $op_ct; ?>" value="<?php echo $typeval; ?>" /></td>
							<td><input type="text" name="addstyledrop_4_<?php echo $op_ct; ?>" id="addstyledrop_4_<?php echo $op_ct; ?>" value="<?php echo $item['classes']; ?>" /></td>
							<td>
								<table id="addstyledrop_5_<?php echo $op_ct; ?>">
									<tr>
										<th>Style</th>
										<th>Value</th>
										<th>Delete</th>
									</tr>
								<?php
								$tp_items = $item["styles"];
								$tp_ct = 1;
								foreach($tp_items as $key => $tp_item) { ?>
									<tr id="tprow_5_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>">
										<td>
											<input type="text" id="addstyledrop_5_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_key" name="addstyledrop_5_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_key" value="<?php echo $key; ?>" />
										</td>
										<td>
											<input type="text" id="addstyledrop_5_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_val" name="addstyledrop_5_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_val" value="<?php echo $tp_item; ?>" />
										</td>
										<td><a style="cursor:pointer;" onclick="delete_tp_row(5,<?php echo $op_ct; ?>,<?php echo $tp_ct; ?>)">X</a></td>
									</tr>
									<?php
									$tp_ct++;
								}
								?>
								</table>
								<div>
									<input value="<?php echo $tp_ct; ?>" type="hidden" id="tpcount_5_<?php echo $op_ct; ?>" name="tpcount_5_<?php echo $op_ct; ?>" />
									<button type="button" class="button-secondary" onclick="add_tp_row(<?php echo $op_ct; ?>,5)"><?php _e('Add new style',TAPS_TEXTDOMAIN); ?></button>
								</div>
							</td>
							<td>
								<table id="addstyledrop_6_<?php echo $op_ct; ?>">
									<tr>
										<th>Attribute</th>
										<th>Value</th>
										<th>Delete</th>
									</tr>
								<?php
								$tp_items = $item["attributes"];
								$tp_ct = 1;
								foreach($tp_items as $key => $tp_item) { ?>
									<tr id="tprow_6_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>">
										<td>
											<input type="text" id="addstyledrop_6_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_key" name="addstyledrop_6_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_key" value="<?php echo $key; ?>" />
										</td>
										<td>
											<input type="text" id="addstyledrop_6_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_val" name="addstyledrop_6_<?php echo $op_ct; ?>_<?php echo $tp_ct; ?>_val" value="<?php echo $tp_item; ?>" />
										</td>
										<td><a style="cursor:pointer;" onclick="delete_tp_row(6,<?php echo $op_ct; ?>,<?php echo $tp_ct; ?>)">X</a></td>
									</tr>
									<?php
									$tp_ct++;
								}
								?>
								</table>
								<div>
									<input value="<?php echo $tp_ct; ?>" type="hidden" id="tpcount_6_<?php echo $op_ct; ?>" name="tpcount_6_<?php echo $op_ct; ?>" />
									<button type="button" class="button-secondary" onclick="add_tp_row(<?php echo $op_ct; ?>,6)"><?php _e('Add new attribute',TAPS_TEXTDOMAIN); ?></button>
								</div>
							</td>
							<td><input type="checkbox" name="addstyledrop_7_<?php echo $op_ct; ?>" id="addstyledrop_7_<?php echo $op_ct; ?>" value="1" <?php if(intval($item['exact'])==1) {?>checked="checked"<?php } ?> /></td>
							<td><input type="checkbox" name="addstyledrop_8_<?php echo $op_ct; ?>" id="addstyledrop_8_<?php echo $op_ct; ?>" value="1" <?php if(intval($item['wrapper'])==1) {?>checked="checked"<?php } ?> /></td>
							<td><a style="cursor:pointer;" onclick="remove(<?php echo $op_ct; ?>)">X</a></td>
						</tr>
						<?php 
							$op_ct++;		
						}
						?>
						</tbody>
					</table>
					<p>
							<input value="<?php echo $op_ct; ?>" type="hidden" id="addstyledrop_number" name="addstyledrop_number" />
							<button type="button" class="button-secondary" onclick="add()"><?php _e('Add new style',TAPS_TEXTDOMAIN); ?></button>
					</p>
					<script type="text/javascript">
						function add_tp_row(main_row,tp_id) {
							var rowcount = parseInt(document.getElementById('tpcount_'+tp_id+'_'+main_row).value);
							rowcount++;
							var table = document.getElementById('addstyledrop_'+tp_id+'_'+main_row);
							var rowHTML ='<tr id="tprow_'+tp_id+'_'+main_row+'_'+rowcount+'">';
							rowHTML+='<td>';
							rowHTML+='				<input type="text" id="addstyledrop_'+tp_id+'_'+main_row+'_'+rowcount+'_key" name="addstyledrop_'+tp_id+'_'+main_row+'_'+rowcount+'_key" value="" />';
							rowHTML+='			</td>';
							rowHTML+='			<td>';
							rowHTML+='				<input type="text" id="addstyledrop_'+tp_id+'_'+main_row+'_'+rowcount+'_val" name="addstyledrop_'+tp_id+'_'+main_row+'_'+rowcount+'_val" value="" />';
							rowHTML+='			</td>';
							rowHTML+='			<td><a style="cursor:pointer;" onclick="delete_tp_row('+tp_id+','+main_row+','+rowcount+')">X</a></td>';
							rowHTML+='		</tr>';
							table.insertAdjacentHTML( "beforeend", rowHTML );
							document.getElementById('tpcount_'+tp_id+'_'+main_row).value=rowcount;
						}
						
						function delete_tp_row(tp_id,main_row,tp_row) {
							document.getElementById('tprow_'+tp_id+'_'+main_row+'_'+tp_row).style.display='none';
							document.getElementById('addstyledrop_'+tp_id+'_'+main_row+'_'+tp_row+'_key').value='';
						}
					
						function remove(row) {
							document.getElementById('addstyledrop_row_'+row).style.display='none';
							document.getElementById('addstyledrop_0_'+row).value='';
						}
						
						function add() {
							rowcount = parseInt(document.getElementById('addstyledrop_number').value);
							rowcount++;
							table = document.getElementById("bb_taps_addstyledrop");
							var rowHTML ='<tbody><tr valign="top" id="addstyledrop_row_'+rowcount+'">';
							rowHTML +='<td><input type="text" name="addstyledrop_0_'+rowcount+'" id="addstyledrop_0_'+rowcount+'" value="" /></td>';
							rowHTML +='<td>';
								rowHTML +='<input type="radio" name="addstyledrop_1_'+rowcount+'" value="inline" checked="checked" /> Inline&nbsp;&nbsp;';
								rowHTML +='<input type="radio" name="addstyledrop_1_'+rowcount+'" value="block"  /> Block&nbsp;&nbsp;';
								rowHTML +='<input type="radio" name="addstyledrop_1_'+rowcount+'" value="selector" /> Selector';
							rowHTML +='</td>';
							rowHTML +='<td><input type="text" name="addstyledrop_3_'+rowcount+'" id="addstyledrop_3_'+rowcount+'" value="" /></td>';
							rowHTML +='<td><input type="text" name="addstyledrop_4_'+rowcount+'" id="addstyledrop_4_'+rowcount+'" value="" /></td>';
							rowHTML +='<td>';
							rowHTML +='	<table id="addstyledrop_5_'+rowcount+'">';
							rowHTML +='		<tr>';
							rowHTML +='			<th>Style</th>';
							rowHTML +='			<th>Value</th>';
							rowHTML +='			<th>Delete</th>';
							rowHTML +='		</tr>';
							rowHTML +='	</table>';
							rowHTML +='	<div>';
							rowHTML +='<input value="0" type="hidden" id="tpcount_5_'+rowcount+'" name="tpcount_5_'+rowcount+'" />';
							rowHTML +='<button type="button" class="button-secondary" onclick="add_tp_row('+rowcount+',5)"><?php _e('Add new style',TAPS_TEXTDOMAIN); ?></button>';
							rowHTML +='	</div>';
							rowHTML +='</td>';
							rowHTML +='<td>';
							rowHTML +='	<table id="addstyledrop_6_'+rowcount+'">';
							rowHTML +='		<tr>';
							rowHTML +='			<th>Attribute</th>';
							rowHTML +='			<th>Value</th>';
							rowHTML +='			<th>Delete</th>';
							rowHTML +='		</tr>';
							rowHTML +='	</table>';
							rowHTML +='	<div>';
							rowHTML +='<input value="0" type="hidden" id="tpcount_6_'+rowcount+'" name="tpcount_6_'+rowcount+'" />';
							rowHTML +='<button type="button" class="button-secondary" onclick="add_tp_row('+rowcount+',6)"><?php _e('Add new attribute',TAPS_TEXTDOMAIN); ?></button>';
							rowHTML +='	</div>';
							rowHTML +='</td>';
							rowHTML +='<td><input type="checkbox" name="addstyledrop_7_'+rowcount+'" id="addstyledrop_7_'+rowcount+'" value="1" /></td>';
							rowHTML +='<td><input type="checkbox" name="addstyledrop_8_'+rowcount+'" id="addstyledrop_8_'+rowcount+'" value="1" /></td>';
							rowHTML +='<td><a style="cursor:pointer;" onclick="remove('+rowcount+')">X</a></td>';
							rowHTML+='</tr></tbody>';
							table.insertAdjacentHTML( "beforeend", rowHTML );
							document.getElementById('addstyledrop_number').value=rowcount;
						}
					</script>
				</div><!-- taps_options div -->
				<hr/>
				<p>&nbsp;</p>
				<p><input type="hidden" name="bb_taps_backend_update" value="doit" />
				<input type="submit" name="Save" value="<?php _e('Save Settings',TAPS_TEXTDOMAIN); ?>" class="button-primary" /></p>
				<p>&nbsp;</p>
			</form>
		</div>
		
<?php }




?>

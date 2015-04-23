<?php
// implement tinymce button

add_action('media_buttons', 'mg_editor_btn', 19);
add_action('admin_footer', 'mg_editor_btn_content');


//action to add a custom button to the content editor
function mg_editor_btn($context) {
	$img = MG_URL . '/img/mg_icon_small.png';
  
	//append the icon
	echo '
	<a class="thickbox" id="mg_editor_btn" title="Media Grid">
	  <img src="'.$img.'" />
	</a>';
}


function mg_editor_btn_content() {
	if(strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php')) :
	include_once(MG_DIR . '/functions.php');
?>

    <div id="mg_popup_container" style="display:none;">
      <?php 
	  // get the grids
	  $grids = get_terms( 'mg_grids', 'hide_empty=0' );
	  
	  if(!is_array($grids)) {echo '<span>'. __('No grids found', 'mg_ml') .' ..</span>';}
	  else {
	  ?>
      	<table id="lcwp_tinymce_table" class="lcwp_form lcwp_table" cellspacing="0" style="width: 530px">
          <tr>
            <td style="width: 35%;">Grid</td>
      		<td colspan="2">
            	<select id="mg_grid_choose" data-placeholder="<?php _e('Select a grid', 'mg_ml') ?> .." name="mg_grid" class="lcweb-chosen" autocomplete="off" style="width: 370px;">
				<?php 
                foreach ( $grids as $grid ) {
                    echo '<option value="'.$grid->term_id.'">'.$grid->name.'</option>';
                }
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php _e('Titles under items?', 'mg_ml') ?></td>
      		<td style="width: 30%;" class="lcwp_form">
            	<input type="checkbox" name="mg_title_under" value="1" class="mg_popup_ip" id="mg_title_under" autocomplete="off" />
            </td>
            <td><span class="info"><?php _e('Move titles under items', 'mg_ml') ?></span></td>
          </tr> 
          
          <tr>
            <td><?php _e('Allow filter', 'mg_ml') ?></td>
      		<td style="width: 30%;" class="lcwp_form mg_scw_filter_toggle">
            	<input type="checkbox" name="filter_grid" value="1" class="mg_popup_ip" id="mg_filter_grid" autocomplete="off" />
            </td>
            <td><span class="info"><?php _e('Allow items filtering by category', 'mg_ml') ?></span></td>
          </tr>  
          <tr class="mg_scw_ff" style="display: none;">
            <td><?php _e('Hide "All" filter', 'mg_ml') ?></td>
      		<td style="width: 30%;" class="lcwp_form">
            	<input type="checkbox" name="hide_all" value="1" class="mg_popup_ip" id="mg_hide_all" autocomplete="off" />
            </td>
            <td><span class="info"><?php _e('Hide the "All" option from filters', 'mg_ml') ?></span></td>
          </tr>
          <tr class="mg_scw_ff" style="display: none;">
            <td><?php _e('Default filter', 'mg_ml') ?></td>
      		<td style="width: 30%;" class="lcwp_form" colspan="2">
            	<select id="mg_def_filter" data-placeholder="<?php _e('Select a filter', 'mg_ml') ?> .." name="mg_def_filter" class="lcweb-chosen" autocomplete="off" style="width: 370px;">
            	</select>
            </td>
          </tr>
          
          <tr>
            <td><?php _e('Relative Width', 'mg_ml') ?></td>
      		<td><input type="text" name="mg_grid_w" id="mg_grid_w" class="lcwp_slider_input" maxlength="4" /> px</td>
            <td><span class="info"><?php _e('Relative with to calculate cells size.<br/>Leave empty to auto-calculate', 'mg_ml') ?></span></td>
          </tr> 
          
          <?php 
		  ///// OVERLAY MANAGER ADD-ON ///////////
		  ////////////////////////////////////////
		  if(defined('MGOM_DIR')) : ?>
          <tr>
            <td><?php _e('Custom Overlay', 'mg_ml') ?></td>
      		<td colspan="2">
            	<select id="mg_custom_overlay" data-placeholder="<?php _e('Select an overlay', 'mg_ml') ?> .." name="mg_custom_overlay" class="lcweb-chosen" style="width: 370px;">
					<option value="">(<?php _e('default one', 'mg_ml') ?>)</option>
					
					<?php
					$overlays = get_terms('mgom_overlays', 'hide_empty=0');
					foreach($overlays as $ol) {
						  $sel = ($ol->term_id == $fdata['mg_default_overlay']) ? 'selected="selected"' : '';
						  echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
					}
					?>
              </select>
            </td>
          </tr> 
          <?php endif;
		  ////////////////////////////////////////
		  ?>  
            
          <tr class="tbl_last">
          	<td colspan="2">
            	<input type="button" value="<?php _e('Insert Grid', 'mg_ml') ?>" name="mg_insert_grid" id="mg_insert_grid" class="button-primary" />
            </td>    
          </tr>
        </table>   
      <?php } ?>
    </div>
	
    
    <?php 
	// javascript var containing grid filters list 
	if(is_array($grids)) :
	?>
	<script type="text/javascript">
	mg_def_f = jQuery.makeArray();
	<?php 
	foreach($grids as $grid) {
		$arr = array('' => __('no initial filter', 'mg_ml'));
		
		$filters = mg_grid_terms_data($grid->term_id, $return = 'array');
		if(is_array($filters)) {
			foreach($filters as $filter) {
				$arr[ $filter['id'] ] = $filter['name'];	
			}
		}
		
		echo 'mg_def_f["'.$grid->term_id.'"] = '. json_encode($arr).';'; 
	}
	?>
	</script>
    <?php endif; ?>



    <?php // SCRIPTS ?>
    <script src="<?php echo MG_URL; ?>/js/functions.js" type="text/javascript"></script>
	<script src="<?php echo MG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo MG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>
<?php
	endif;
	return true;
}

?>
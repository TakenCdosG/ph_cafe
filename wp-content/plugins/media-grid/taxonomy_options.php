<?php
include_once(MG_DIR . '/functions.php');


// add the order field
add_action('mg_item_categories_add_form_fields','mg_cat_order_field', 10, 2 );
add_action('mg_item_categories_edit_form_fields' , "mg_cat_order_field", 10, 2);

function mg_cat_order_field($tax_data) {
   //check for existing taxonomy meta for term ID
   if(is_object($tax_data)) {
	  $term_id = $tax_data->term_id;
	  $order = (int)get_option("mg_cat_".$term_id."_order");
	}
	else {$order = 0;}
	
	// creator layout
	if(!is_object($tax_data)) :
?>
		<div class="form-field">
            <label><?php _e('Order', 'mg_ml') ?></label>
           	<input type="text" name="mg_cat_order" value="<?php echo $order ?>" maxlength="3" style="width: 35px;" /> 
            <p><?php _e('The category order that will be used for the grid filter', 'mg_ml') ?></p>
        </div>
	<?php
	else:
	?>
	 <tr class="form-field">
      <th scope="row" valign="top"><label><?php _e('Order', 'mg_ml') ?></label></th>
      <td>
        <input type="text" name="mg_cat_order" value="<?php echo $order ?>" maxlength="3" style="width: 35px;" />
        <p class="description"><?php _e('The category order that will be used for the grid filter', 'mg_ml') ?></p>
      </td>
    </tr>
<?php
	endif;
}


// save the fields
add_action('created_mg_item_categories', 'save_mg_cat_order_field', 10, 2);
add_action('edited_mg_item_categories', 'save_mg_cat_order_field', 10, 2);

function save_mg_cat_order_field( $term_id ) {
	
    if ( isset($_POST['mg_cat_order']) ) {
		$val = (int)$_POST['mg_cat_order']; 
		
		//save the option array
        update_option("mg_cat_".$term_id."_order", $val); 
    }
	else {delete_option("mg_cat_".$term_id."_order");}
}



/////////////////////////////
// manage taxonomy table
add_filter( 'manage_edit-mg_item_categories_columns', 'mg_cat_order_column_headers', 10, 1);
add_filter( 'manage_mg_item_categories_custom_column', 'mg_cat_order_column_row', 10, 3);


// add the table column
function mg_cat_order_column_headers($columns) {
    $columns_local = array();
	
    if (!isset($columns_local['order'])) { 
        $columns_local['order'] = __("Order", 'mg_ml');
	}
	
    return array_merge($columns, $columns_local);
}


// fill the custom column row
function mg_cat_order_column_row( $row_content, $column_name, $term_id){
	
	if($column_name == 'order') {
		return (int)get_option("mg_cat_".$term_id."_order");
	}
	else {return '&nbsp;';}
}


/////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////
// REMOVE THE PARENT FIELD FOR THE CUSTOM TEXONOMY
function mg_remove_cat_parent(){
    global $current_screen;
    switch ( $current_screen->id ) {
        case 'edit-mg_item_categories':
            
			?>
			<script type="text/javascript">
            jQuery(document).ready( function($) {
                jQuery('#parent').parents('.form-field').remove();
            });
            </script>
            <?php
			
            break;
    }
}
add_action('admin_footer-edit-tags.php', 'mg_remove_cat_parent');


?>
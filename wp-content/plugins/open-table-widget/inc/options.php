<?php
$open_table_widget_options = array(
	array( 'name' => __( 'About', $open_table_widget->textdomain ), 'type' => 'opentab' ),
	array( 'type' => 'about' ),
	array( 'type' => 'closetab', 'actions' => false ),

	array(
		'name' => __( 'Options', $open_table_widget->textdomain ),
		'type' => 'opentab'
	),

	array(
		'name'  => __( 'Disable Plugin CSS', $open_table_widget->textdomain ),
		'desc'  => __( 'Useful to style your own widget and for theme integration and optimization.', $open_table_widget->textdomain ),
		'std'   => '',
		'id'    => 'disable_css',
		'type'  => 'checkbox',
		'label' => __( 'Yes', $open_table_widget->textdomain )
	),
	array(
		'name'  => __( 'Disable Bootstrap select fields', $open_table_widget->textdomain ),
		'desc'  => __( 'The select fields will be replaced by standard HTML select fields rather than the Twitter Bootstrap ones. For more information about the bootstrap dropdowns.', $open_table_widget->textdomain ),
		'std'   => '',
		'id'    => 'disable_bootstrap_select',
		'type'  => 'checkbox',
		'label' => __( 'Yes', $open_table_widget->textdomain )
	),
	array(
		'name'  => __( 'Disable Bootstrap dropdown JS', $open_table_widget->textdomain ),
		'desc'  => __( 'Themes built with Twitter Bootstrap will already have support for the Bootstrap dropdowns. If this is your case, check this to remove the dropdown script from being output.', $open_table_widget->textdomain ),
		'std'   => '',
		'id'    => 'disable_bootstrap_dropdown',
		'type'  => 'checkbox',
		'label' => __( 'Yes', $open_table_widget->textdomain )
	),
	array( 'type' => 'closetab' )
);
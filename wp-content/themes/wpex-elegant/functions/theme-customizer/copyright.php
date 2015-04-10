<?php
/**
 * Copyright theme options
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */



add_action( 'customize_register', 'wpex_customizer_copyright' );

function wpex_customizer_copyright($wp_customize) {

	// Add textarea
	class ATT_Customize_Textarea_Control extends WP_Customize_Control {
		
		//Type of customize control being rendered.
		public $type = 'textarea';

		//Displays the textarea on the customize screen.
		public function render_content() { ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
			</label>
		<?php
		}
	}

	// Copyright Section
	$wp_customize->add_section( 'wpex_copyright' , array(
		'title'		=> __( 'Copyright', 'wpex' ),
		'priority'	=> 900,
	) );
	
	$wp_customize->add_setting( 'wpex_copyright', array(
		'type'		=> 'theme_mod',
		'default'	=> 'Powered by <a href=\"http://www.wordpress.org\" title="WordPress" target="_blank">WordPress</a> and <a href=\"http://themeforest.net/user/WPExplorer?ref=WPExplorer" target="_blank" title="WPExplorer" rel="nofollow">WPExplorer Themes</a>',
	) );

	$wp_customize->add_control( new ATT_Customize_Textarea_Control( $wp_customize, 'wpex_copyright', array(
		'label'		=> __('Custom Copyright','wpex'),
		'section'	=> 'wpex_copyright',
		'settings'	=> 'wpex_copyright',
		'type'		=> 'textarea',
	) ) );
		
}
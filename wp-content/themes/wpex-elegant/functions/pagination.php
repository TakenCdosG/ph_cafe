<?php
/**
 * Custom pagination functions
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */


/**
	Numbered pagination
**/
if ( ! function_exists( 'wpex_pagination') ) {
	function wpex_pagination() {
		global $wp_query,$wpex_query;
		if ( $wpex_query ) {
			$total = $wpex_query->max_num_pages;
		} else {
			$total = $wp_query->max_num_pages;
		}
		$big = 999999999; // need an unlikely integer
		if ( $total > 1 )  {
			 if ( !$current_page = get_query_var( 'paged') )
				 $current_page = 1;
			 if ( get_option( 'permalink_structure') ) {
				 $format = 'page/%#%/';
			 } else {
				 $format = '&paged=%#%';
			 }
			echo paginate_links(array(
				'base'		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'	=> $format,
				'current'	=> max( 1, get_query_var( 'paged') ),
				'total'		=> $total,
				'mid_size'	=> 2,
				'type'		=> 'list',
				'prev_text'	=> '<i class="fa fa-angle-left"></i>',
				'next_text'	=> '<i class="fa fa-angle-right"></i>',
			 ));
		}
	}
}


/**
	Next/Previous page style pagination
**/
if ( !function_exists( 'wpex_pagejump') ) {
	function wpex_pagejump($pages = '', $range = 4) {
		$showitems = ($range * 2)+1; 
		global $paged;
		if ( empty($paged) ) $paged = 1;
		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if (!$pages) {
				$pages = 1;
			}
		}
		if ( 1 != $pages ) {
			echo '<div class="post-navigation clr"><div class="alignleft">';
			previous_posts_link( '&larr; ' . __( 'Newer Posts', 'wpex' ) );
			echo '</div><div class="alignright">';
			next_posts_link( __( 'Older Posts', 'wpex' ) .' &rarr;' );
			echo '</div></div>';
		}
		
	}
}
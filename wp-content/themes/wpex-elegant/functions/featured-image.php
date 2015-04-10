<?php
/**
 * Returns a post featured image URl
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
*/


// Returns the site featured image
if ( ! function_exists( 'wpex_get_featured_img_url' ) ) {
	
		function wpex_get_featured_img_url( $attachment_id = '', $full_image = false, $main_query = true ) {
			
			// Post Vars
			global $post, $wpex_query;
			$post_id = $post->ID;
			$post_type = get_post_type( $post_id );
			$attachment_id = $attachment_id ? $attachment_id : get_post_thumbnail_id( $post_id );
			$attachment_url = wp_get_attachment_url( $attachment_id );
			
			// Resizing Vars
			$width = 9999;
			$height = 9999;
			$crop = false;
			
			// Return full image url if set to true
			if ( $full_image ) return $attachment_url;
			
			/**
				Pages
			**/
			if ( $post_type == 'page' && is_singular( 'page' ) ) {
				$width = '9999';
				$height = '9999';
			}

			/**
				Standard post
			**/
			if ( $post_type == 'post' ) {
				if ( is_singular() && !$wpex_query ) {
					$width = '640';
					$height = '340';
				} else {
					$width = '640';
					$height = '340';
				}
			}

			/**
				Portfolio
			**/
			if ( $post_type == 'portfolio' ) {
				if ( is_singular() && !$wpex_query ) {
					$width = '9999';
					$height = '9999';
				} else {
					$width = '400';
					$height = '260';
				}
			}

			/**
				Staff
			**/
			if ( $post_type == 'staff' ) {
				if ( is_singular() && !$wpex_query ) {
					$width = '9999';
					$height = '9999';
				} else {
					$width = '400';
					$height = '9999';
				}
			}

			/**
				Slides
			**/
			if ( $post_type == 'slides' ) {
				$width = '9999';
				$height = '500';
			}

			/**
				Search retuls
			**/
			if ( is_search() ) {
				$width = '150';
				$height = '150';
			}
		
			// Return Dimensions & crop
			$width = intval($width);
			$width = $width ? $width : '9999';
			$height = intval($height);
			$height = $height ? $height : '9999';
			$width = apply_filters('wpex_featured_image_width', $width );
			$height = apply_filters('wpex_featured_image_height', $height );
			$crop = ( $height == '9999' ) ? false : true;
			$cropped_image = aq_resize( $attachment_url, $width, $height, $crop );
			$cropped_image = apply_filters( 'wpex_get_featured_img_url', $cropped_image );
			return $cropped_image;
			
			
		} // End function
	
} // End if
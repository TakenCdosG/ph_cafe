<?php
/**
 * Outputs the homepage slider
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */


if ( !function_exists( 'wpex_homepage_slider' ) ) {
	function wpex_homepage_slider() {
		global $post;
		if ( ! is_front_page() ) return;
		// Get slides
		$wpex_query = new WP_Query(
			array(
				'post_type'			=> 'slides',
				'posts_per_page'	=> '-1',
				'no_found_rows'		=> true,
			)
		);
		// Display Slides
		if ( $wpex_query->posts ) { ?>
			<div id="homepage-slider-wrap" class="clr flexslider-container">
				<div id="homepage-slider" class="flexslider">
					<ul class="slides clr">
						<?php
						// Loop through each slide
						foreach( $wpex_query->posts as $post ) : setup_postdata($post);
						$post_id = $post->ID;
						$title = esc_attr( the_title_attribute( 'echo=0' ) );
						//$caption[] = get_post_meta( $post_id, 'wpex_slide_caption', true );
						$caption[] = get_post_meta( $post_id, 'first_line', true );
						$caption[] = get_post_meta( $post_id, 'second_line', true );
						$url = get_post_meta( $post_id, 'wpex_slide_url', true );
						$url_target = get_post_meta( $post_id, 'wpex_slide_target', true );
						$url_target = $url_target ? $url_target : 'blank'; ?>
							<li class="homepage-slider-slide" style="background-image: url('<?php echo wpex_get_featured_img_url("",TRUE); ?>')">
								<?php if ( '' !== $url ) { ?>
								<a href="<?php echo $url; ?>" title="<?php echo $title; ?>" target="_<?php echo $url_target; ?>">
								<?php } ?>
								<div class="homepage-slide-inner container">
									<div class="homepage-slide-content">
										<?php if ( '' != $caption ) {
											foreach($caption as $key => $value){ ?>
											<div class="homepage-slide-caption caption-<?php echo $key; ?>"><?php echo $caption[$key]; ?></div>
										<?php }} ?>
									</div><!-- .homepage-slider-content -->
								</div>
<!--								<img src="--><?php //echo wpex_get_featured_img_url("",TRUE); ?><!--" alt="--><?php //echo $title; ?><!--">-->
								<?php if ( '' !== $url ) { ?>
								</a>
								<?php } ?>
							</li>
						<?php endforeach; ?>
					</ul><!-- .slides -->
				</div><!-- .flexslider -->
			</div><!-- #homepage-slider" -->
		<?php }
		// Reset post data
		wp_reset_postdata();
	}
}
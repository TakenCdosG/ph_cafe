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

						$url = get_post_meta( $post_id, 'wpex_slide_url', true );
						$url_target = get_post_meta( $post_id, 'wpex_slide_target', true );
						$url_target = $url_target ? $url_target : 'blank'; ?>
							<li class="homepage-slider-slide" style="background-image: url('<?php echo wpex_get_featured_img_url("",TRUE); ?>')">
								<?php if ( '' !== $url ) { ?>
								<a href="<?php echo $url; ?>" title="<?php echo $title; ?>" target="_<?php echo $url_target; ?>">
								<?php } ?>
								<div class="homepage-slide-inner container">
									<div class="homepage-slide-content">
                                        <?php 	$first_line = get_post_meta( $post_id, 'first_line', true );
                                        $second_line = get_post_meta( $post_id, 'second_line', true );?>
                                        <div class="homepage-slide-caption caption_first_line"><?php if(!empty($first_line)) echo $first_line;  ?></div>
                                        <div class="homepage-slide-caption caption_second_line"><?php if(!empty($second_line))  echo $second_line;  ?></div>
										<div class="sub-menu-home">
											<?php if ( function_exists( 'ot_get_option' ) ) {
												$ids = ot_get_option( 'sub_menu_header', array() ); } ?>
											<ul>
												<li><?php print"<a href='".$ids[0]['link']."'>".$ids[0]['title']."</a>"; ?></li>
												<li><?php print"<a href='".$ids[1]['link']."'>".$ids[1]['title']."</a>"; ?></li>
											</ul>
										</div>
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
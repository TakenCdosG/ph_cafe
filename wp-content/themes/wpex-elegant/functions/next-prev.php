<?php
/**
 * Used for next and previous post links
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
*/


// Display next/previous links
if ( ! function_exists( 'wpex_next_prev' ) ) {
	function wpex_next_prev() {
		// Get post data
		global $post;
		// Not singular so bye bye!
		if ( !is_singular() ) return; ?>
		<?php
		// Output the next/previous links ?>
		<ul class="single-post-pagination clr">
			<?php next_post_link( '<li class="post-prev">%link</li>', '<i class="fa fa-angle-left"></i>' ); ?><?php previous_post_link( '<li class="post-next">%link</li>', '<i class="fa fa-angle-right"></i>' ); ?>
		</ul><!-- .post-post-pagination -->
	<?php }
}
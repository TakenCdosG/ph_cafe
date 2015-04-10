<?php
/**
 * Used to output post meta info
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */


if ( ! function_exists( 'wpex_post_meta' ) ) {

	function wpex_post_meta() {
		
		// Get post data
		global $post;
		$post_id = $post->ID;
		$post_type = get_post_type($post);

		// Do not display if password protected
		if ( post_password_required($post_id) ) return;

		// Get category for posts only
		if ( $post_type == 'post' ) {
			$category = get_the_category();
			if ( !empty($category) ) {
				$fist_category = reset($category);
				$category_name = $fist_category->cat_name;
				$category_url = get_category_link( $fist_category->term_id );
			}
		}

		// Get Portfolio Category
		if ( $post_type == 'portfolio' && taxonomy_exists('portfolio_category') ) {
			$category = get_the_terms( $post_id, 'portfolio_category', array('number' => '1') );
			if ( !empty($category) ) {
				$fist_category = reset($category);
				$category_name = $fist_category->name;
				$category_id = $fist_category->term_id;
				$category_url = get_term_link( $category_id, 'portfolio_category' );
			}
		}

		// Get EDD Category - woot!
		if ( $post_type == 'download' && taxonomy_exists('download_category') ) {
			$category = get_the_terms( $post_id, 'download_category', array('number' => '1') );
			if ( !empty($category) ) {
				$fist_category = reset($category);
				$category_name = $fist_category->name;
				$category_id = $fist_category->term_id;
				$category_url = get_term_link( $category_id, 'download_category' );
			}
		} ?>
		
		<ul class="post-meta clr">
			<li class="meta-date">
				<?php _e('Posted on','wpex'); ?>
				<span class="meta-date-text"><?php echo get_the_date(); ?></span>
			</li>
			<?php if ( !is_page_template('templates/home.php') ) { ?>
				<?php if(isset($fist_category)){ ?>
					<li class="meta-category">
						<span class="meta-seperator">/</span><?php _e('Under','wpex'); ?>
						<a href="<?php echo $category_url; ?>" title="<?php echo $category_name; ?>"><?php echo $category_name; ?></a>
					</li>
				<?php } ?>
				<?php if( comments_open( $post_id ) ) { ?>
					<li class="meta-comments comment-scroll">
						<span class="meta-seperator">/</span><?php _e('With','wpex'); ?>
						<?php comments_popup_link( __( '0 Comments', 'wpex' ), __( '1 Comment',  'wpex' ), __( '% Comments', 'wpex' ), 'comments-link' ); ?>
					</li>
				<?php } ?>
			<?php } ?>
		</ul><!-- .post-meta -->
		
		<?php
		
	} // End function
	
} // End if
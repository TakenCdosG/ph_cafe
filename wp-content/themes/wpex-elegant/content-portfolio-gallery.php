<?php
/**
 * The default template for displaying portfolio content.
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */


/**
	Single Posts
**/
global $wpex_query;
if ( is_singular( 'portfolio' ) && !$wpex_query ) {

	// Display post slider based on gallery images
	// See functions/post-slider.php
	wpex_post_slider();

/**
	Entries
**/
} else { ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if( has_post_thumbnail() ) { ?>
			<div class="portfolio-entry-media">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
					<img src="<?php echo wpex_get_featured_img_url(); ?>" alt="<?php the_title(); ?>" class="portfolio-entry-img" />
				</a>
			</div><!-- .portfolio-entry-media -->
			<div class="portfolio-entry-details clr">
				<h3 class="portfolio-entry-title">
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
						<?php the_title(); ?>
					</a>
				</h3>
				<div class="portfolio-entry-categories clr">
					<?php echo get_the_term_list( get_the_ID(), 'portfolio_category', '', ', ', '' ); ?> 
				</div><!-- .portfolio-entry-categories -->
			</div><!-- .portfolio-entry-details -->
		<?php } ?>
	</article><!-- .portfolio-entry -->

<?php } ?>
<?php
/**
 * The Template for displaying your single portfolio posts
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<div id="primary" class="content-area clr">
		<div id="content" class="site-content" role="main">
			<article>
				<header class="page-header clr">
						<h1 class="page-header-title"><?php the_title(); ?></h1>
					<?php wpex_post_meta(); ?>
					<?php wpex_next_prev(); ?>
				</header><!-- .page-header -->
				<?php get_template_part( 'content-portfolio', get_post_format() ); ?>
				<div class="entry clr">
					<?php the_content(); ?>
				</div><!-- .entry -->
				<footer class="entry-footer">
					<?php edit_post_link( __( 'Edit Post', 'wpex' ), '<span class="edit-link clr">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article>
			<?php wp_link_pages( array( 'before' => '<div class="page-links clr">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
			<?php
			/**
				Related Posts
			**/
			if ( get_theme_mod( 'wpex_portfolio_related', '1' ) == '1' ) {
				$wpex_portfolio_more_count = get_theme_mod( 'portfolio_more_count', '4' );
				$wpex_query = new WP_Query(
					array(
						'post_type'			=> 'portfolio',
						'posts_per_page'	=> $wpex_portfolio_more_count,
						'orderby'			=> 'rand',
						'no_found_rows'		=> true,
					)
				);
				if ( $wpex_query->posts ) { ?>
					<div class="clear"></div>
					<div id="single-portfolio-related" class="clr">
						<h3 class="heading"><span><?php _e( 'Recent Work', 'wpex' ); ?></span></h3>
						<?php $wpex_count=0; ?>
						<?php foreach( $wpex_query->posts as $post ) : setup_postdata($post); ?>
							<?php $wpex_count++; ?>
							<?php get_template_part( 'content', 'portfolio' ); ?>
							<?php if ( $wpex_count == '4' ) { ?>
								<?php $wpex_count = 0; ?>
							<?php } ?>
						<?php endforeach; ?>
					</div><!-- /single-portfolio-related -->
				<?php } ?>
				<?php wp_reset_postdata(); ?>
			<?php  } ?>
			<?php comments_template(); ?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php endwhile; ?>
<?php get_footer(); ?>
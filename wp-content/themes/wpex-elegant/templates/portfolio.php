<?php
/**
 * Template Name: Portfolio
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area clr">
		<div id="content" class="site-content" role="main">
			<header class="page-header clr">
				<h1 class="page-header-title"><?php the_title(); ?></h1>
				<?php
				while ( have_posts() ) : the_post();
					if ( get_the_content() !== '' ) { ?>
						<div id="portfolio-template-content" class="entry clr">
							<?php the_content(); ?>
						</div><!-- .entry -->
					<?php }
				endwhile; ?>
			</header><!-- #page-header -->
			<?php
			// Query portfolio posts
			global $post,$paged;
			$paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$display_count = get_theme_mod('wpex_portfolio_posts_per_page', '10');
			$wpex_query = new WP_Query(
				array(
					'post_type'			=> 'portfolio',
					'posts_per_page'	=> $display_count,
					'paged'				=> $paged
				)
			);
			// If portfolio posts are found lets loop through them
			if( $wpex_query->posts ) { ?>
				<div id="portfolio-wrap" class="clr">
					<?php $wpex_count=0; ?>
					<?php foreach( $wpex_query->posts as $post ) : setup_postdata( $post ); ?>
						<?php $wpex_count++; ?>
							<?php get_template_part( 'content-portfolio', get_post_format() ); ?>
						<?php if ( $wpex_count == '4' ) { ?>
							<?php $wpex_count=0; ?>
						<?php } ?>
					<?php endforeach; ?>
				</div><!-- #portfolio-wrap -->
				<?php wpex_pagination(); ?>
			<?php } ?>
			<?php
			// Reset the query
			wp_reset_postdata(); ?>
		</div><!-- #content -->
	</div><!-- #primary -->
	
<?php get_footer(); ?>
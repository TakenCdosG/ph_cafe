<?php
/**
 * Template Name: Staff
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
			</header><!-- #page-header -->
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				if ( get_the_content() !== '' ) { ?>
				<div class="entry clr">
					<?php the_content(); ?>
				</div><!-- .entry -->
				<?php } ?>
			<?php endwhile; ?>
			<?php
			// Query staff posts
			global $post,$paged;
			$paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$display_count = get_theme_mod('wpex_staff_posts_per_page', '9');
			$wpex_query = new WP_Query(
				array(
					'post_type'			=> 'staff',
					'posts_per_page'	=> $display_count,
					'paged'				=> $paged
				)
			);
			// If staff posts are found lets loop through them
			if( $wpex_query->posts ) { ?>
				<div id="staff-wrap" class="clr">
					<?php $wpex_count=0; ?>
					<?php foreach( $wpex_query->posts as $post ) : setup_postdata( $post ); ?>
						<?php $wpex_count++; ?>
							<?php get_template_part( 'content-staff', get_post_format() ); ?>
						<?php if ( $wpex_count == '3' ) { ?>
							<?php $wpex_count=0; ?>
						<?php } ?>
					<?php endforeach; ?>
				</div><!-- #staff-wrap -->
				<?php wpex_pagination(); ?>
			<?php } ?>
			<?php
			// Reset the query
			wp_reset_postdata(); ?>
		</div><!-- #content -->
	</div><!-- #primary -->
	
<?php get_footer(); ?>
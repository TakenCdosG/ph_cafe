<?php
/**
 * The template for displaying your Portfolio Category custom taxonomy archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area clr">
		<div id="content" class="site-content" role="main">
			<div id="page-header-wrap">
				<header class="page-header container clr">
					<h1 class="page-header-title"><?php echo single_term_title(); ?></h1>
					<?php if ( term_description() !== '' ) { ?>
						<div id="archive-description" class="clr"><?php echo term_description(); ?></div>
					<?php } ?>
				</header><!-- #page-header -->
			</div><!-- #page-header-wrap -->
			<?php if ( have_posts( ) ) : ?>
				<div id="portfolio-wrap" class="clr">
					<?php $wpex_count=0; ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php $wpex_count++; ?>
							<?php get_template_part( 'content', 'portfolio' ); ?>
						<?php if ( $wpex_count == '4' ) { ?>
							<?php $wpex_count=0; ?>
						<?php } ?>
					<?php endwhile; ?>
				</div><!-- #portfolio-wrap -->
				<?php wpex_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
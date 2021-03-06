<?php
/**
 * Template Name: Single-Gallery
 */

get_header(); ?>

    <div id="primary" class="content-area clr">
        <div id="content" class="site-content" role="main">
            <?php while ( have_posts() ) : the_post(); ?>


                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry clr">
                        <?php the_content(); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links clr">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post -->
            <?php endwhile; ?>
        </div><!-- #content -->
    </div><!-- #primary -->

<?php get_footer(); ?>
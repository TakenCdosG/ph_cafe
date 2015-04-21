<?php
/**
 * Template Name: GiftCards
 */

get_header(); ?>

    <div id="header_text" class="content-area clr">
        <?php print get_field('big_text'); ?>
    </div>

    <div id="primary" class="content-area clr">
        <div id="content" class="site-content span_1_of_2 col col-1" role="main">
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry clr">
                        <?php the_content(); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links clr">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post -->
            <?php endwhile; ?>
        </div><!-- #content -->
        <div id="paypal_form" class="span_1_of_2 col col-2">
            <?php print get_field('paypal_form'); ?>
        </div>
    </div><!-- #primary -->




<?php get_footer(); ?>
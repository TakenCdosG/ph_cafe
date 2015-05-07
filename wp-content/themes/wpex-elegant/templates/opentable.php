<?php
/**
 * Template Name: OpenTable
 */

get_header(); ?>

    <div id="primary" class="content-area clr">
        <?php if ( !is_front_page() ) { ?>
            <header class="page-header clr">
                <h1 class="page-header-title"><?php the_title(); ?></h1>
            </header><!-- #page-header -->
        <?php } ?>
        <div id="content" class="site-content left-content" role="main">
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry clr">
                        <?php the_content(); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links clr">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
                    </div><!-- .entry-content -->
                    <footer class="entry-footer">
                        <?php edit_post_link( __( 'Edit Page', 'wpex' ), '<span class="edit-link clr">', '</span>' ); ?>
                    </footer><!-- .entry-footer -->
                </article><!-- #post -->
                
            <?php endwhile; ?>
        </div><!-- #content -->
        <?php get_sidebar(); ?>
    </div><!-- #primary -->
<?php get_footer(); ?>
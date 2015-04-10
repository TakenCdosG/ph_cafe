<?php
/**
 * The default template for displaying features entries
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */

//Vars
$wpex_feature_url = get_post_meta( get_the_ID(), 'wpex_feature_url', true ); ?>

<article id="id-<?php the_ID(); ?>"  <?php post_class(); ?>>
	<?php if ( $wpex_feature_url ) {
		echo '<a href="'. esc_url( $wpex_feature_url ) .'" title="'. get_the_title() .'" target="_blank" class="feature-entry-url clr">';
	} ?>
	<?php if ( get_post_meta( get_the_ID(), 'wpex_icon_font', true ) ) { ?>
		<div class="feature-icon-font"><i class="fa fa-<?php echo get_post_meta( get_the_ID(), 'wpex_icon_font', true ); ?>"></i></div>
	<?php } elseif ( has_post_thumbnail() && get_theme_mod( 'wpex_blog_post_thumb', '1' ) == '1' ) { ?>
		<div class="feature-thumbnail">
			<img src="<?php echo wpex_get_featured_img_url(); ?>" alt="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" />
		</div><!-- .feature-thumbnail -->
	<?php } ?>
	<header class="feature-entry-header clr">
		<h2 class="feature-entry-title"><?php the_title(); ?></h2>
	</header>
	<div class="feature-entry-content entry clr">
		<?php the_content(); ?>
	</div>
	<?php if ( $wpex_feature_url ) {
		echo '</a>';
	} ?>
</article>
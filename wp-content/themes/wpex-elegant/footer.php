<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Elegant WPExplorer Theme
 * @since Elegant 1.0
 */
?>

<?php if(!is_front_page()){?>
</div><!-- #main-content -->
<?php } ?>
<?php include("footer_buttons.php"); ?>
	<div id="footer-wrap" class="site-footer clr">
		<div id="footer" class="clr container">
			<div id="footer-widgets" class="clr">
				<div class="footer-box span_1_of_3 col col-1">
					<?php dynamic_sidebar( 'footer-one' ); ?>
				</div><!-- .footer-box -->
				<div class="footer-box span_1_of_3 col col-2">
					<?php dynamic_sidebar( 'footer-two' ); ?>
				</div><!-- .footer-box -->
				<div class="footer-box span_1_of_3 col col-3">
					<?php dynamic_sidebar( 'footer-three' ); ?>
				</div><!-- .footer-box -->
			</div><!-- #footer-widgets -->
		</div><!-- #footer -->
	</div><!-- #footer-wrap -->


</div><!-- #wrap -->

<?php wp_footer(); ?>
</body>
</html>
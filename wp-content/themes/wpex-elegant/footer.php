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
        <div id="footer-social-wrap" class="container clr">
            <div id="email-signup" class="span_1_of_3 col col-1">
                <form name="ccoptin" action="http://visitor.r20.constantcontact.com/d.jsp" target="_blank" method="post">
                    <input type="hidden" name="llr" value="kav6gfcab">
                    <input type="hidden" name="m" value="1101834146826">
                    <input type="hidden" name="p" value="oi">
                    <div id="input-border-left"></div><input id="email-input" type="text" name="ea" size="20" title="Email Signup" class="blur" placeholder="Email Signup">
                    <input  type="button" name="go" value="Join" class="submit"></form>
            </div>
            <div id="social-icons" class="span_2_of_3 col col-2">
                    <a href="https://www.facebook.com/pondhousecafe" id="facebook_icon" class="social-icon" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fb_icon.png">
                    </a>
                    <a href="https://twitter.com/PondHouseCafe" id="twitter_icon" class="social-icon" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tw_icon.png">
                    </a>
                    <a href="https://www.pinterest.com/pondhousecafe/" id="painteres_icon" class="social-icon" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pt_icon.png">
                    </a>
                    <a href="https://www.theknot.com/marketplace/pond-house-cafe-west-hartford-ct-380213" id="knot_icon" class="social-icon" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/knot_icon.resized.png">
                    </a>
                    <a href="http://www.yelp.com/biz/pond-house-cafe-west-hartford-3" id="yelp_icon" class="social-icon" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/yp_icon.png">
                    </a>
                     <a href="http://www.opentable.com/pond-house-cafe-reservations-west-hartford?rid=6035&restref=6035" id="open_table_icon" class="social-icon" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ot_icon.png">
                    </a>


            </div>
        </div><!-- #footer-social-icons -->
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
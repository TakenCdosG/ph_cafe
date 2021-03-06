
<div id="footer-buttons-wrap" class="clr">
	<div id="footer-buttons" class="clr container">
		<div class="count-1 col span_1_of_4">
            <a href="/cafe" class="cafe">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/cafe_button.png">
            </a>
		</div>
		<div class="col span_1_of_4">
            <a href="/banquets" class="banquets">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/banquets_button.png">
            </a>
		</div>
		<div class="col span_1_of_4">
            <a href="/wedding-info" class="weddings">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/weddings_button.png">
            </a>
		</div>
		<div class="col span_1_of_4">
            <a href="/events/category/all/" class="catering">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/Calendar_purple.png">
            </a>
		</div>
	</div>

	<footer id="copyright-wrap" class="clr container">
		<div id="copyright" role="contentinfo" class="clr">
			<?php
			// Displays copyright info
			// See functions/copyright.php
			wpex_copyright(); ?>
		</div><!-- #copyright -->
	</footer><!-- #footer-wrap -->

</div>


<?php
	//print get_template_directory_uri()."/assets/css/custom-styles.css";

?>

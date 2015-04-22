
<?php
/**
 * Load All WRIS Custom Post Type
 */
$IG_CPT_Name = "ris_gallery";
$AllSlides = array(  'p' => $Id['id'], 'post_type' => $IG_CPT_Name, 'orderby' => 'ASC');
$loop = new WP_Query( $AllSlides );

while ( $loop->have_posts() ) : $loop->the_post();
//get the post id
$post_id = get_the_ID();
	
/**
 * Get All Slides Details Post Meta
 */
$RPGP_AllPhotosDetails = unserialize(base64_decode(get_post_meta( get_the_ID(), 'ris_all_photos_details', true)));
$TotalImages =  get_post_meta( get_the_ID(), 'ris_total_images_count', true );
$i = 1;
$j = 1;
?>
<script type="text/javascript">
	jQuery( document ).ready(function( jQuery ) {
		jQuery( '#example3_<?php echo $post_id; ?>' ).sliderPro({
			width: <?php if($WRIS_L3_Slider_Width != "") echo $WRIS_L3_Slider_Width; else echo "1000"; ?>,
			height: <?php if($WRIS_L3_Slider_Height != "") echo $WRIS_L3_Slider_Height; else echo "500"; ?>,
			autoplay: <?php if($WRIS_L3_Auto_Slideshow == 1) echo "true"; else echo "false"; ?>,
			arrows: <?php if($WRIS_L3_Sliding_Arrow == 1) echo "true"; else echo "false"; ?>,
			buttons: <?php if($WRIS_L3_Navigation_Button == 1) echo "true"; else echo "false"; ?>,
			smallSize: 500,
			mediumSize: 1000,
			largeSize: 3000,
			//fade: true,
			thumbnailArrows: true,			
		});
	});
</script>
<style>
/* Example 3 */
#example3_<?php echo $post_id; ?> .sp-selected-thumbnail {
	border: 4px solid #000;
}

/*
#example3_<?php //echo $post_id; ?> .title-in {
	font-weight: bolder;
	opacity: 0.7 !important;
	font-size: 1.2em;
}

#example3_<?php //echo $post_id; ?> .desc-in {
	opacity: 0.7 !important;
	text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
	font-size: 1em;
}
*/

#example3_<?php echo $post_id; ?> .title-in {
	color: <?php echo $RISP_Slide_In_Title_Color; ?> !important;
	font-weight: bolder;
	text-align: center;
}

#example3_<?php echo $post_id; ?> .title-in-bg {
	background: rgba(255, 255, 255, 0.7); !important;
	white-space: unset !important;
	max-width: 90%;
	min-width: 40%;
	transform: initial !important;
	font-size: 14px !important;
}

#example3_<?php echo $post_id; ?> .desc-in {
	color: <?php echo $RISP_Slide_In_Desc_Color; ?> !important;
	text-align: center;
}
#example3_<?php echo $post_id; ?> .desc-in-bg {
	background: rgba(<?php echo $RISP_Slide_In_Desc_BG_Color; ?>, <?php echo $RISP_BG_Color_Opacity; ?>) !important;
	white-space: unset !important;
	width: 80% !important;
	min-width: 30%;
	transform: initial !important;
	font-size: 13px !important;
}

@media (max-width: 640px) {
	#example3_<?php echo $post_id; ?> .hide-small-screen {
		display: none;
	}
}

@media (max-width: 860px) {
	#example3_<?php echo $post_id; ?> .sp-layer {
		font-size: 18px;
	}
	
	#example3_<?php echo $post_id; ?> .hide-medium-screen {
		display: none;
	}
}
</style>
<?php  if($WRIS_L3_Slide_Title == 1) { ?>
<h3 class="uris-slider-title"><?php echo get_the_title( $post_id ); ?> </h3>
<?php } ?>
<div id="example3_<?php echo $post_id; ?>" class="slider-pro">
	<!---- slides div start ---->
	<div class="sp-slides">
		<?php 
		foreach($RPGP_AllPhotosDetails as $RPGP_SinglePhotoDetails) {
		$Title = $RPGP_SinglePhotoDetails['rpgp_image_label'];
		$Desc = $RPGP_SinglePhotoDetails['rpgp_image_desc'];
		$Url = $RPGP_SinglePhotoDetails['rpgp_image_url'];
		$i++;
		?>
		<div class="sp-slide">
			<img class="sp-image" src="" 
				data-src="<?php echo $Url; ?>"
				data-small="<?php echo $Url; ?>"
				data-medium="<?php echo $Url; ?>"
				data-large="<?php echo $Url; ?>"
				data-retina="<?php echo $Url; ?>"/>

			<?php if($Title != "") { ?>
			<p class="sp-layer sp-white sp-padding title-in title-in-bg hide-small-screen" 
				data-position="centerCenter"
				data-vertical="-14%"
				data-show-transition="left" data-show-delay="500">
				<?php if(strlen($Title) > 100 ) echo substr($Title,0,100); else echo $Title; ?>
			</p>
			<?php } ?>

			<?php if($Desc != "") { ?>
			<p class="sp-layer sp-black sp-padding desc-in desc-in-bg hide-medium-screen" 
				data-position="centerCenter"
				data-vertical="14%"
				data-show-transition="right" data-show-delay="500">
				<?php if(strlen($Desc) > 300 ) echo substr($Desc,0,300)."..."; else echo $Desc; ?>
			</p>
			<?php } ?>
		</div>
		<?php } //end for each ?>		
	</div>
	
	<!---- slides div end ---->
	<?php 
	if($WRIS_L3_Slider_Navigation == 1) {
	?>
	<!-- slides thumbnails div start -->
	<div class="sp-thumbnails">
		<?php 
		foreach($RPGP_AllPhotosDetails as $RPGP_SinglePhotoDetails) {
		$ThumbUrl = $RPGP_SinglePhotoDetails['rpggallery_admin_thumb'];
		$j++;
		?>
		<img class="sp-thumbnail" src="<?php echo $ThumbUrl; ?>"/>
		<?php } ?>
	</div>
	<?php } ?>
	<!-- slides thumbnails div end -->
	
</div>
<?php endwhile; ?>
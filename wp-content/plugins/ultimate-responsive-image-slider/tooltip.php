<style>
.sprev {
	margin:8px;
}
</style>
<script>
jQuery(document).ready( function(){
	//Basic
	jQuery('#p1').darkTooltip();
	jQuery('#p2').darkTooltip();
	jQuery('#p3').darkTooltip();
	jQuery('#p4').darkTooltip();
	jQuery('#p5').darkTooltip();
	jQuery('#p6').darkTooltip();
	
	//With some options
	jQuery('#light').darkTooltip({
		animation:'flipIn',
		gravity:'west',
		confirm:true,
		theme:'light'
	});
});
</script>
<?php $PreviewImg = WRIS_PLUGIN_URL.'img/'; ?>

<div style="display:none;" id="s1">
	<h4>Sliding Arrow</h4>
	<img src="<?php echo $PreviewImg."sliding-arrow.jpg"; ?>" class="sprev">
</div>

<div style="display:none;" id="s2">
	<h4>Show Thumbnail</h4>
	<img src="<?php echo $PreviewImg."show-thumbnail.jpg"; ?>" class="sprev">
</div>

<div style="display:none;" id="s3">
	<h4>Show Navigation Bullets</h4>
	<img src="<?php echo $PreviewImg."show-navigation-bullets.jpg"; ?>" class="sprev">
</div>

<div style="display:none;" id="s4">
	<h4>Slider Width</h4>
	<img src="<?php echo $PreviewImg."slider-width.jpg"; ?>" class="sprev">
</div>

<div style="display:none;" id="s5">
	<h4>Slider Height</h4>
	<img src="<?php echo $PreviewImg."slider-height.jpg"; ?>" class="sprev">
</div>
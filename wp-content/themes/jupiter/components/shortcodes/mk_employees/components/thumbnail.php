<?php


$image_src = Mk_Image_Resize::resize_by_id_adaptive( get_post_thumbnail_id(), $view_params['image_size'], $view_params['image_width'], $view_params['image_height'], $crop = false, $dummy = true);

if(!empty($view_params['link'])) { ?>
	<a href="<?php echo $view_params['link']; ?>">
<?php } ?>
		<img alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" src="<?php echo $image_src['dummy']; ?>" <?php echo $image_src['data-set']; ?>/>

<?php if(!empty($view_params['link'])) { ?>
	</a>
<?php } ?>

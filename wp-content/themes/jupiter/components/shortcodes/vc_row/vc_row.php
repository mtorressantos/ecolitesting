<?php

$path = pathinfo( __FILE__ ) ['dirname'];

include( $path . '/config.php' );

wp_enqueue_script( 'wpb_composer_front_js' );

$fullwidth_start = $output = $fullwidth_end = '';

$wrapper_attributes = array();

$row_id = ! empty( $id ) ? (' id="' . esc_attr( $id ) . '"') : '';

$post_id = global_get_post_id();
$page_layout = get_post_meta( $post_id, '_layout', true );

if ( isset( $_REQUEST['layout'] ) && ! empty( $_REQUEST['layout'] ) ) {
	$page_layout = esc_html( $_REQUEST['layout'] );
}

$padding = ! empty( $padding ) ? $padding : $column_padding;

$row_classes[] = $visibility;
$row_classes[] = 'mk-fullwidth-' . $fullwidth;
$row_classes[] = ('true' == $attached) ? 'add-padding-' . $padding : '';
$row_classes[] = 'attched-' . $attached;
$row_classes[] = $el_class;
$row_classes[] = get_viewport_animation_class( $animation );
$row_classes[] = vc_shortcode_custom_css_class( $css, ' ' );
$row_classes[] = 'true' == $equal_columns ? ' equal-columns' : '';
$row_classes[] = 'js-master-row';
$row_classes[] = ( 'yes' === $disable_element ) ? 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md' : '';

if ( 'true' === $fullwidth && 'true' === $fullwidth_content ) {
	$row_classes[] = 'mk-full-content-true';
}

// Prallax video & image.
$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_speed = $parallax_speed_video;
	$parallax_image = $video_bg_url;
	$row_classes[] = 'vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed.
	$row_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$row_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$row_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

$after_output = '';
if ( 'full-width' !== $page_layout ) {
	if ( 'true' == $fullwidth ) {
		$wrapper_attributes[] = 'data-mk-full-width="true"';
		$wrapper_attributes[] = 'data-mk-full-width-init="false"';
		$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
	}
	if ( 'true' == $fullwidth_content ) {
		$wrapper_attributes[] = 'data-mk-stretch-content="true"';
	}
}

if ( 'true' !== $fullwidth ) {
	$row_classes[] = 'mk-grid';
}

?>

<div <?php echo $row_id; ?> <?php echo implode( ' ', $wrapper_attributes ); ?> class="wpb_row vc_row vc_row-fluid <?php echo esc_attr( implode( ' ', $row_classes ) ); ?>">
	<?php if ( 'true' == $fullwidth && 'false' == $fullwidth_content ) { ?>
		<div class="mk-grid">
	<?php } ?>
			<?php echo wpb_js_remove_wpautop( $content ); ?>
	<?php if ( 'true' == $fullwidth && 'false' == $fullwidth_content ) { ?>
		</div>
	<?php } ?>
</div>
<?php
if ( 'true' == $fullwidth ) {
	 echo $after_output;
}
?>

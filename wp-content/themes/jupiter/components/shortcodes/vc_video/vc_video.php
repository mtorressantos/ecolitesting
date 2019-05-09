<?php
global $wp_embed;

include trailingslashit( dirname( __FILE__ ) ) . 'config.php';

$id = Mk_Static_Files::shortcode_id();

if ( empty( $host ) || ! in_array( $host, array( 'self_hosted', 'social_hosted' ) ) ) {
	_e( 'Invalid video type.', 'mk_framework' );
	return;
}

// Check self hosted video data.
if ( $host == 'self_hosted' && empty( $mp4 ) && empty( $webm ) ) {
	_e( 'Self hosted video file is empty.', 'mk_framework' );
	return;
}

// Check social hosted video data.
if ( $host == 'social_hosted' && empty( $link ) ) {
	_e( 'Social hosted video URL is empty.', 'mk_framework' );
	return;
}

// Set video wrapper attributes.
$wpb_wrapper_attr = '';
if ( intval( $max_width ) ) {
	$wpb_wrapper_attr .= ' style="max-width:' . $max_width . 'px"';
}

// Set video loop attributes.
$video_loop = '';
if ( 'true' == $loop && 'true' == $autoplay ) {
	$video_loop = ' loop';
}

// Set video controls attributes.
$video_controls = ' controls="controls"';
if ( 'true' == $hide_controls && 'true' == $autoplay ) {
	$video_controls = '';
}

// Check aspect ratio.
$aspect_ratio_class = '';
if ( 'self_hosted' == $host && 'true' == $aspect_ratio ) {
	$aspect_ratio_class = 'mk-aspect-ratio';
}

// Set video container data attributes.
$video_container_attr = '';
$video_container_attr .= ' data-id="' . $id . '"';
$video_container_attr .= ' data-source="' . $host . '"';
$video_container_attr .= ' data-autoplay="' . ( ( 'true' == $autoplay ) ? '1' : '0' ) . '"';
$video_container_attr .= ' data-loop="' . ( ( 'true' == $loop ) ? '1' : '0' ) . '"';
$video_container_attr .= ' data-target="' . $play_target . '"';

$thumbnail_image_src = '';
if ( ! empty( $thumbnail_image ) ) {
	$thumbnail_image_src = wp_get_attachment_url( $thumbnail_image );
}

$play_icon = ! empty( $play_icon ) ? (strpos( $play_icon, 'mk-' ) !== false) ? $play_icon : ( 'mk-' . $play_icon . '' ) : '';

if ( $thumbnail_image_src ) {
	Mk_Static_Files::addCSS( '
		#video-thumbnail-' . $id . ' {
			background-image:url(\'' . $thumbnail_image_src . '\');
		}
	', $id );
}
?>
<div class="wpb_video_widget <?php echo get_viewport_animation_class( $animation ) . $el_class . ' ' . $visibility; ?>">
	<div class="wpb_wrapper"<?php echo $wpb_wrapper_attr; ?>>
		<?php mk_get_view( 'global', 'shortcode-heading', false, [ 'title' => $title ] ); ?>
		<div id="video-container-<?php echo $id; ?>" class="video-container <?php echo $aspect_ratio_class; ?>"<?php echo $video_container_attr; ?> <?php echo get_schema_markup( 'video' ); ?>>
			<div id="video-player-<?php echo $id; ?>" class="video-player">
			<?php if ( $host == 'self_hosted' ) : ?>
			<video id="video-player-<?php echo $id; ?>" data-id="<?php echo $id; ?>" poster="<?php echo $poster_image; ?>" preload="auto" <?php echo $video_controls . $video_loop; ?>>
				<?php if ( ! empty( $mp4 ) ) { ?>
					<source type="video/mp4" src="<?php echo $mp4; ?>" />
				<?php } if ( ! empty( $webm ) ) { ?>
					<source type="video/webm" src="<?php echo $webm; ?>" />
				<?php } ?>
			</video>
			<?php elseif ( $host == 'social_hosted' ) :
				$embed_code = $wp_embed->run_shortcode( '[embed width="1140" height="641"]' . $link . '[/embed]' );
				$embed_code = str_replace( '<iframe', '<iframe id="iframe-player-' . $id . '" data-id="' . $id . '"', $embed_code );
				echo $embed_code;
			endif; ?>
			</div>
			<?php if ( $custom_thumbnail == 'true' && $autoplay == 'false' ) : ?>
			<div id="video-thumbnail-<?php echo $id; ?>" class="video-thumbnail <?php echo $play_icon_animation; ?>">
				<div class="video-thumbnail-overlay">
					<?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, $play_icon, $play_icon_size, $play_icon_color ); ?>
					<div class="preloader-preview-area" style="display:none;">
						<div class="line-scale">
							<div style="background-color: #c7c7c7"></div>
							<div style="background-color: #c7c7c7"></div>
							<div style="background-color: #c7c7c7"></div>
							<div style="background-color: #c7c7c7"></div>
							<div style="background-color: #c7c7c7"></div>
						</div>
					 </div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

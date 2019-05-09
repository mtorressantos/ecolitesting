<?php

$has_parallax   = ('true' == $view_params['parallax']);
$layer_config[] = ( $has_parallax ) ? 'data-mk-component="Parallax"' : '';
$layer_config[] = ( $has_parallax ) ? 'data-parallax-config=\'{"speed" : ' . floatval( $view_params['speed_factor'] ) . ' }\'' : '';
$poster_image   = esc_url( $view_params['poster_image'] );

if ( 'yes' != $view_params['bg_video'] ) {
	return false;
}

if ( 'self' == $view_params['video_source'] ) {

		// Loop false doesn't work as expected and presence of loop attribute by itself determines behaviour
		// We keep true as value for crossbrowser compatibility (? not sure - refactor if needed) and remove attribute completly when false.
		$loop = ('true' === $view_params['video_loop']) ? 'loop="true"' : '';

	if ( ! empty( $poster_image ) ) { ?>
			<div style="background-image:url(<?php echo $poster_image; ?>);" class="mk-video-section-touch js-el" <?php echo implode( ' ', $layer_config ); ?>></div>
		<?php } ?>

		<div class="mk-section-video mk-center-video js-el" <?php echo implode( ' ', $layer_config ); ?>>
			<video poster="<?php echo esc_url( $poster_image ); ?>" muted="muted" preload="auto" <?php echo esc_attr( $loop ); ?> autoplay="true" style="opacity:0;">

				<?php if ( ! empty( $view_params['mp4'] ) ) { ?>
					<source type="video/mp4" src="<?php echo esc_url( $view_params['mp4'] ); ?>" />
				<?php } ?>

				<?php if ( ! empty( $view_params['webm'] ) ) { ?>
					   <source type="video/webm" src="<?php echo esc_url( $view_params['webm'] ); ?>" />
				<?php } ?>

				<?php if ( ! empty( $view_params['ogv'] ) ) { ?>
					<source type="video/ogg" src="<?php echo esc_url( $view_params['ogv'] ); ?>" />
				<?php } ?>
			</video>
		</div>

	<?php
} else {

	$loop = ('true' === $view_params['video_loop']) ? '1' : '0';

	if ( ! empty( $poster_image ) ) {
		?>
			<div style="background-image:url(<?php echo esc_url( $poster_image ); ?>);" class="mk-video-section-touch js-el" <?php echo implode( ' ', $layer_config ); ?>></div>
		<?php } ?>
		
		<div class="mk-section-video   js-el" <?php echo implode( ' ', $layer_config ); ?>>
		<div class="video-social-hosted mk-center-video">

			<?php if ( 'youtube' == $view_params['stream_host_website'] ) { ?>
					<?php wp_enqueue_script( 'api-youtube' ); ?>
					<iframe src="https://www.<?php echo esc_attr( mk_get_thirdparty_domain_name( 'youtube' ) ); ?>/embed/<?php echo esc_attr( $view_params['stream_video_id'] ); ?>?rel=0&amp;wmode=transparent&amp;enablejsapi=1&amp;controls=0&amp;showinfo=0&amp;loop=<?php echo esc_attr( $loop ); ?>&amp;playlist=<?php echo esc_attr( $view_params['stream_video_id'] ); ?>"></iframe>

				<?php } else if ( 'vimeo' == $view_params['stream_host_website'] ) { ?>
					<?php wp_enqueue_script( 'api-vimeo' ); ?>
					<iframe src="//player.vimeo.com/video/<?php echo esc_attr( $view_params['stream_video_id'] ); ?>?autoplay=1&badge=0&title=0&byline=0&background=1&muted=1&loop=<?php echo esc_attr( $loop ); ?>" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<?php } ?>
		</div>
		</div>


<?php
}

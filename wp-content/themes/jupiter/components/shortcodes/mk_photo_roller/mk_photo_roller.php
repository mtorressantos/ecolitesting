<?php
/**
 * Photo Roller shortcode template.
 *
 * @package Jupiter
 * @subpackage Visual_Composer
 * @since 5.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * Shortcode class
 * @var $this WPBakeryShortCode_MK_Photo_Roller
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$rolling_speed = 301 - $atts['rolling_speed'];
$id = Mk_Static_Files::shortcode_id();

// Container.
$photo_roller_class = array();
$photo_roller_class[] = 'mk-photo-roller mk-photo-roller-' . $id;

if ( ! empty( $atts['visibility'] ) ) {
	$photo_roller_class[] = $atts['visibility'];
}

if ( ! empty( $atts['el_class'] ) ) {
	$photo_roller_class[] = $atts['el_class'];
}

// Frame.
$photo_roller_frame_class = array();
$photo_roller_frame_class[] = 'mk-photo-roller-frame';

if ( ! empty( $atts['rolling_axis'] ) && 'vertical' === $atts['rolling_axis'] ) {
	$photo_roller_frame_class[] = 'mk-vertical';
}

if ( ! empty( $atts['reverse_direction'] ) && 'true' === $atts['reverse_direction'] ) {
	$photo_roller_frame_class[] = 'mk-reverse-direction';
}

if ( ! empty( $atts['pause_hover'] ) && 'true' === $atts['pause_hover'] ) {
	$photo_roller_frame_class[] = 'mk-pause-hover';
}

// Image.
$image = wp_get_attachment_image_src( $atts['image'], 'full' );

if ( ! empty( $image ) ) {
	$image_url = $image[0];
	$image_width = $image[1];
	$image_height = $image[2];
}

if ( false === $image ) {
	$image_url = vc_asset_url( 'vc/no_image.png' );
	$image_width = 800;
	$image_height = 900;
}

// Dynamic styles.
Mk_Static_Files::addCSS( '
	.mk-photo-roller-' . $id . ' .mk-photo-roller-frame {
		width: ' . $image_width  . 'px;
		animation-duration: ' . $rolling_speed . 's;
	}

	.mk-photo-roller-' . $id . ' .mk-photo-roller-frame:after {
		background-image: url(' . $image_url . ');
	}

	@media (max-width: 993px) {
		.mk-photo-roller-' . $id . ' div.mk-photo-roller-frame:not( .mk-vertical ) {
			width: ' . ( $image_width / 100 ) * 7 . '%;
		}
	}
	', $id );

?>

<div class="<?php echo implode( ' ', $photo_roller_class ); ?>">
	<div class="<?php echo implode( ' ', $photo_roller_frame_class ); ?>">
		<img class="mk-photo-roller-frame-img" src="<?php echo $image_url; ?>">
	</div>
</div>

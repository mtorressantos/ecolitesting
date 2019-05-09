<?php

/**
 * Template part for header logo. views/header/master
 *
 * @author Artbees
 * @package jupiter/views
 * @since 5.0.0
 * @since 6.0.1 Add support for width option.
 */

global $mk_options;

$logo = ! empty( $mk_options['logo'] ) ? $mk_options['logo'] : '';
$logo_svg = ( pathinfo( $logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
$light_logo = ! empty( $mk_options['light_header_logo'] ) ? $mk_options['light_header_logo'] : '';
$light_logo_svg = ( pathinfo( $light_logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
$mobile_logo = ! empty( $mk_options['responsive_logo'] ) ? $mk_options['responsive_logo'] : '';
$mobile_logo_svg = ( pathinfo( $mobile_logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
$sticky_logo = ! empty( $mk_options['sticky_header_logo'] ) ? $mk_options['sticky_header_logo'] : '';
$sticky_logo_svg = ( pathinfo( $sticky_logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';

$is_nav_item = isset( $view_params['is_nav_item'] ) ? $view_params['is_nav_item'] : false;
$post_id = global_get_post_id();

if ( $post_id ) {

	global $post;
	$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

	if ( 'true' === $enable ) {
		$logo_meta = get_post_meta( $post_id, 'logo', true );
		$light_logo_meta = get_post_meta( $post_id, 'light_logo', true );
		$sticky_logo_meta = get_post_meta( $post_id, 'sticky_header_logo', true );
		$logo_mobile_meta = get_post_meta( $post_id, 'responsive_logo', true );

		$logo = ! empty( $logo_meta ) ? $logo_meta : $logo;
		$logo_svg = ( pathinfo( $logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
		$light_logo = ! empty( $light_logo_meta ) ? $light_logo_meta : $light_logo;
		$light_logo_svg = ( pathinfo( $light_logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
		$mobile_logo = ! empty( $logo_mobile_meta ) ? $logo_mobile_meta : $mobile_logo;
		$mobile_logo_svg = ( pathinfo( $mobile_logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
		$sticky_logo = ! empty( $sticky_logo_meta ) ? $sticky_logo_meta : $sticky_logo;
		$sticky_logo_svg = ( pathinfo( $sticky_logo, PATHINFO_EXTENSION ) === 'svg' ) ? 'mk-svg' : '';
	}
}

$tag = $is_nav_item ? 'li' : 'div';

$class[] = $is_nav_item ? 'nav-middle-logo menu-item' : 'header-logo';
$class[] = 'fit-logo-img';
$class[] = 'add-header-height';
$class[] = ! empty( $mobile_logo ) ? 'logo-is-responsive' : '';
$class[] = ! empty( $sticky_logo ) ? 'logo-has-sticky' : '';

if ( ! empty( $logo ) || ! empty( $sticky_logo ) ) { ?>
	<<?php echo $tag; ?> class=" <?php echo esc_attr( implode( ' ', $class ) ); ?>">
		<a href="<?php echo home_url( '/' ); ?>" title="<?php esc_attr( bloginfo( 'name' ) ); ?>">

			<img class="mk-desktop-logo dark-logo <?php echo esc_attr( $logo_svg ); ?>"
				title="<?php esc_attr( bloginfo( 'description' ) ); ?>"
				alt="<?php esc_attr( bloginfo( 'description' ) ); ?>"
				src="<?php echo esc_url( $logo ); ?>" />

			<?php if ( $light_logo ) { ?>
				<img class="mk-desktop-logo light-logo <?php echo esc_attr( $light_logo_svg ); ?>"
					title="<?php esc_attr( bloginfo( 'description' ) ); ?>"
					alt="<?php esc_attr( bloginfo( 'description' ) ); ?>"
					src="<?php echo esc_url( $light_logo ); ?>" />
			<?php } ?>

			<?php if ( $mobile_logo && ! $is_nav_item ) { ?>
				<img class="mk-resposnive-logo <?php echo esc_attr( $mobile_logo_svg ); ?>"
					title="<?php esc_attr( bloginfo( 'description' ) ); ?>"
					alt="<?php esc_attr( bloginfo( 'description' ) ); ?>"
					src="<?php echo esc_url( $mobile_logo ); ?>" />
			<?php } ?>

			<?php if ( $sticky_logo ) { ?>
				<img class="mk-sticky-logo <?php echo esc_attr( $sticky_logo_svg ); ?>"
					title="<?php esc_attr( bloginfo( 'description' ) ); ?>"
					alt="<?php esc_attr( bloginfo( 'description' ) ); ?>"
					src="<?php echo esc_url( $sticky_logo ); ?>" />
			<?php } ?>
		</a>
	</<?php echo $tag; ?>>
<?php } else { ?>

    <<?php echo $tag; ?> class="<?php echo esc_attr( implode(' ', $class) ); ?>">
    	<a href="<?php echo home_url('/'); ?>" title="<?php esc_attr( get_bloginfo('name') ); ?>">
            <img title="<?php esc_attr( bloginfo('description') ); ?>" alt="<?php esc_attr( bloginfo('description') ); ?>" src="<?php echo THEME_IMAGES; ?>/jupiter-logo.png" />
        </a>
    </<?php echo $tag; ?>>

<?php }

<?php
/**
 * template part for header style 3 full screen navigation. views/header/holders
 *
 * @author Artbees
 * @package jupiter/views
 * @since 5.0.0
 * @since 5.9.5 Fix fullscreen nav logo visibility.
 */

global $mk_options;
$logo_skin = ! empty( $mk_options['fullscreen_nav_logo'] ) ? $mk_options['fullscreen_nav_logo'] : 'dark';
$mobile_logo_skin = ! empty( $mk_options['fullscreen_nav_mobile_logo'] ) ? $mk_options['fullscreen_nav_mobile_logo'] : 'dark';
$close_btn_skin = ! empty( $mk_options['fullscreen_close_btn_skin'] ) ? $mk_options['fullscreen_close_btn_skin'] : 'light';
$light_logo = isset( $mk_options['light_header_logo'] ) ? $mk_options['light_header_logo'] : '';
$logo = ($logo_skin == 'dark') ? $mk_options['logo'] : $light_logo;
$mobile_logo = ($mobile_logo_skin == 'dark') ? $mk_options['logo'] : $light_logo;
$fullscreen_nav_logo = ! empty( $mk_options['fullscreen_nav_logo'] ) ? $mk_options['fullscreen_nav_logo'] : 'dark';
$show_fullscreen_nav_logo = $fullscreen_nav_logo && 'none' !== $fullscreen_nav_logo;

// If Mobile Logo is a Custom Logo
if ( $mobile_logo_skin == 'custom' ) {
	$mobile_logo = ! empty( $mk_options['fullscreen_nav_mobile_logo_custom'] ) ? $mk_options['fullscreen_nav_mobile_logo_custom'] : $mobile_logo;
}
$is_repsonive_logo = ! empty( $mobile_logo ) ? 'logo-is-responsive' : '';
?>

<div class="mk-fullscreen-nav <?php echo $is_repsonive_logo; ?>">
	<a href="#" class="mk-fullscreen-nav-close <?php echo esc_attr( $close_btn_skin ); ?>"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-moon-close-2' ); ?></a> 
	<div class="mk-fullscreen-inner _ flex flex-center flex-items-center ">
		<div class="mk-fullscreen-nav-wrapper">

			<?php if ( $show_fullscreen_nav_logo ) { ?>
				<img class="mk-fullscreen-nav-logo dark-logo" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $logo ); ?>" />
			<?php } ?>
			<img class="mk-fullscreen-nav-logo responsive-logo" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $mobile_logo ); ?>" />

			<?php
			wp_nav_menu(array(
				'theme_location' => 'fullscreen-menu',
				'container' => 'nav',
				'container_id' => 'fullscreen-navigation',
				'container_class' => 'fullscreen-menu',
				'menu_class' => 'fullscreen-navigation-ul',
				'fallback_cb' => '',
				'walker' => new header_icon_walker(),
			));
			?>
		</div>
	</div>
</div>

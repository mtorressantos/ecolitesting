<?php
/**
 * Output Updates pane in Jupiter > Control Panel
 *
 * @copyright   Artbees LTD (c)
 * @package     artbees
 */
wp_update_themes();
$api_key = get_option( 'artbees_api_key' );
$is_registered = ! empty( $api_key ) ? '' : 'mka-call-to-register-product';
$mk_control_panel = new mk_control_panel();
$updates  = new Mk_Wp_Theme_Update();
$releases = $updates->get_release_note();
$latest_theme_package = esc_url( $updates->get_theme_latest_package_url() );
$check_latest_version = esc_html( $updates->check_latest_version(1) );
?>

<div class="mka-cp-pane-box <?php echo $is_registered; ?>" id="mk-cp-image-sizes">
	<div class="mka-cp-pane-title">
		<?php esc_html_e( 'Update', 'mk_framework' ); ?>
		<?php echo THEME_NAME; ?>
	</div>
	<div class="mka-cp-new-version-wrap">
		<div class="mka-cp-new-version-title">
			<span class="mka-cp-version-number"><?php echo str_replace( 'V', 'Version ', $releases->post_title ); ?></span>
			<span class="mka-cp-version-date"><?php echo mysql2date( 'j F Y', $releases->post_date ); ?></span>
		</div>
		<div class="mka-cp-new-version-content">
			<?php echo $releases->post_content; ?>
		</div>
		<?php if ( ! empty( $check_latest_version ) ) { ?>
			<a class="mka-button mka-button--green mka-button--small" href="<?php echo esc_url( $updates->get_theme_update_url() ); ?>" id="js__update-theme-btn">
				<?php esc_html_e( 'Update', 'mk_framework' ); ?>
			</a>
		<?php } ?>
		<?php if ( $latest_theme_package ) { ?>
			<a class="mka-button mka-button--gray mka-button--small" target="_blank" href="<?php echo $latest_theme_package; ?>" id="js__download-theme-package-btn">
				<?php esc_html_e( 'Download', 'mk_framework' ); ?>
			</a>
		<?php } ?>
	</div>
</div>

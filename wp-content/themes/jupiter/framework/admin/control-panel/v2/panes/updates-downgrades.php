<?php
/**
 * Output Updates pane in Jupiter > Control Panel
 *
 * @since     6.0.3 Remove update page URL in button.
 * @copyright Artbees LTD (c)
 * @package   artbees
 */

// @codingStandardsIgnoreStart
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );
// @codingStandardsIgnoreEnd

wp_update_themes();
$api_key = get_option( 'artbees_api_key' );
$is_registered = ! empty( $api_key ) ? '' : 'mka-call-to-register-product';
$mk_control_panel = new mk_control_panel();
$updates  = new Mk_Updates_Downgrades();
$releases = $updates->get_release_notes();
$get_release_download_link_nonce = wp_create_nonce( 'mk-ajax-get-theme-release-package-url-nonce' );
?>
<script type="text/javascript">
	DynamicMaxHeight({selector: ".dynamic-max-height"});
</script>
<div class="mka-cp-pane-box <?php echo $is_registered; ?>" id="mk-cp-updates-downgrades">
	<div class="mka-cp-pane-title">
		<?php esc_html_e( 'Update', 'mk_framework' ); ?>
		<?php echo THEME_NAME; ?>
	</div>
	<?php
	foreach ( $releases as $release ) {
		// $release_download_url = $updates->get_theme_release_package_url( $release->ID );
		$release_version = trim( str_replace( 'V', '', $release->post_title ) );
		$current_version = get_option( 'mk_jupiter_theme_current_version' );
		$version_compare = version_compare( $release_version,$current_version );
		$button_text = null;
		$button_color = null;
		if ( 1 === $version_compare ) {
			$button_text = 'Update';
			$button_color = 'green';
		} elseif ( -1 === $version_compare ) {
			$button_text = 'Downgrade';
			$button_color = 'red';
		}
	?>
	<hr style="width: 110%; margin-left: -25px; margin-bottom: 35px;" id="<?php echo esc_attr( $release_version ); ?>" />
	<div class="mka-cp-new-version-wrap js-dynamic-height" style="padding-bottom: 40px;" data-maxheight="200">
		<div class="mka-cp-new-version-title" id="<?php echo esc_attr( $release_version ); ?>">
			<span class="mka-cp-version-number"><?php echo esc_attr( 'Version ' . $release_version ); ?></span>
			<span class="mka-cp-version-date"><?php echo esc_attr( mysql2date( 'j F Y', $release->post_date ) ); ?></span>
		</div>
		<div class="mka-cp-new-version-content dynamic-height-wrap dynamic-max-height" data-maxheight="140" data-button-more="Show More" data-button-less="Show Less">
			<div class="dynamic-wrap">
			<?php echo wp_kses_post( $release->post_content ); ?>
			</div>
		</div>
		<?php if ( $button_text && $button_color ) { ?>
			<a class="mka-button mka-button--<?php echo esc_attr( $button_color ); ?> mka-button--small js__cp_change_theme_version" href="#" data-release-version="<?php echo esc_attr( $release_version ); ?>" data-nonce="<?php echo esc_attr( $get_release_download_link_nonce ); ?>" data-release-id="<?php echo esc_attr( $release->ID ); ?>" id="js__update-theme-btn">
				<?php esc_html_e( $button_text, 'mk_framework' ); ?>
			</a>
		<?php } ?>
			<a class="mka-button mka-button--gray mka-button--small release-download" target="_blank" href="#" id="js__download-theme-package-btn" data-nonce="<?php echo esc_attr( $get_release_download_link_nonce ); ?>" data-release-id="<?php echo esc_attr( $release->ID ); ?>">
				<?php esc_html_e( 'Download', 'mk_framework' ); ?>
			</a>
	</div>
	<?php } ?>
</div>


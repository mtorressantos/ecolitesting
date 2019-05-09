<?php
$update_class = new Mk_Wp_Theme_Update();
$api_key = get_option( 'artbees_api_key' );
$check_latest_version = $update_class->check_latest_version();
?>

<!-- Control Panel Sidebar -->
<div class="mka-cp-sidebar">
	<ul class="mka-cp-sidebar-list">
		<li class="mka-cp-sidebar-list-items mka-is-active">
			<a class="mka-cp-sidebar-link" href="#mk-cp-support">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--support">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Support', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-register-product">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--register-product">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Register Product', 'mk_framework' ); ?>
				</label>
					<svg class="js__no-api-key-warning-icon <?php echo (empty( $api_key ) ? 'is-active' : ''); ?>" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">
						<circle fill="#f5575c" cx="8" cy="8" r="8"/>
						<rect x="7.1" y="3" fill="#FFFFFF" width="1.9" height="6.3"/>
						<circle fill="#FFFFFF" cx="8" cy="12.3" r="1.2"/>
					</svg>
			</a>
		</li>
		<?php } ?>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-templates">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--templates">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Templates', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-plugins">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--plugins">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Required Plugins', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<!-- <li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-addons">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--addons">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Add-Ons', 'mk_framework' ); ?>
				</label>
			</a>
		</li> -->
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-image-sizes">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--image-sizes">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Image Sizes', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-icon-library">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--addons">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Icon Library', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-system-status">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--system-status">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'System Status', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<?php } ?>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-announcements">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--announcements">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Announcements', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<?php } ?>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-export-import">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--export-import">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Export & Import', 'mk_framework' ); ?>
				</label>
			</a>
		</li>
		<?php } ?>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
		<li class="mka-cp-sidebar-list-items">
			<a class="mka-cp-sidebar-link" href="#mk-cp-updates-downgrades">
				<span class="mka-cp-nav-item-icon mka-cp-nav-item-icon--updates">
				</span>
				<label class="mka-cp-nav-label">
					<?php esc_html_e( 'Updates', 'mk_framework' ); ?>
				</label>
					<svg class="mka-new-version-warning-icon <?php echo ( ! empty( $check_latest_version ) ? 'is-active' : ''); ?>" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">
						<circle fill="#f5575c" cx="8" cy="8" r="8"/>
						<rect x="7.1" y="3" fill="#FFFFFF" width="1.9" height="6.3"/>
						<circle fill="#FFFFFF" cx="8" cy="12.3" r="1.2"/>
					</svg>
			</a>
			</a>
		</li>
		<?php } ?>
	</ul>
</div>
<!-- End of Sidebar -->

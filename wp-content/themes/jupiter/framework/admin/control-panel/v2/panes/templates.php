<?php
$api_key = get_option( 'artbees_api_key' );
$is_registered = ! empty( $api_key ) ? '' : 'mka-call-to-register-product';
$installed_template_data_attr = '';
$installed_template_id = get_option( 'jupiter_template_installed_id', '' );
$installed_template_data_attr .= ' data-installed-template-id="' . $installed_template_id . '"';
$installed_template = get_option( 'jupiter_template_installed', '' );
$installed_template_data_attr .= ' data-installed-template="' . $installed_template . '"';
wp_print_request_filesystem_credentials_modal();
?>


<div class="mka-cp-pane-box <?php echo $is_registered; ?>" id="mk-cp-templates">


	<!-- Restore Button wrap -->    
	<div id="js__restore-template-wrap" class="mka-restore-template-wrap">
		<a class="mka-button mka-button--blue mka-button--medium mka-button--icon mka-button--restore-backup" id="js__restore-template-btn" href="#">
			<?php esc_html_e( 'Restore from Last Backup', 'mk_framework' ); ?>
			<span class="mka-restore-template-info-arrow"></span>
		</a>
		<div class="mka-restore-template-info-box"><?php esc_html_e( 'Restore database to a backup stored at: ', 'mk_framework' ); ?><span class="js__backup-date"></span></div>
	</div>
	<!-- End of Restore Button wrap --> 



	<!-- Installed Template wrap -->
	<div id="js__installed-template-wrap" class="mka-cp-installed-template">
		<div class="mka-cp-pane-title">
			<?php esc_html_e( 'Installed Template', 'mk_framework' ); ?>
			 <!-- <div class="mka-wrap mka-tip-wrap">
				<a href="#" class="mka-tip">
					<span class="mka-tip-icon">
						<span class="mka-tip-arrow"></span>
					</span>
					<span class="mka-tip-ripple"></span>
				</a>
				<div class="mka-tip-content">
				</div>
			</div> -->
		</div>
		<div id="js__installed-template"<?php echo $installed_template_data_attr; ?> ></div>
		<div class="mka-clearfix"></div>
	</div>
	<!-- End of installed template -->



	<div class="mka-cp-install-template mka-clearfix">

				<div class="mka-cp-pane-title">
			<?php esc_html_e( 'New Templates', 'mk_framework' ); ?>
			<!-- <div class="mka-wrap mka-tip-wrap">
				<a href="#" class="mka-tip">
					<span class="mka-tip-icon">
						<span class="mka-tip-arrow"></span>
					</span>
					<span class="mka-tip-ripple"></span>
				</a>
				<div class="mka-tip-content">
				</div>
			</div> -->
		</div>

		<div class="mka-search-wrap mka-cp-template-search">
			<div class="mka-search">
				<input class="mka-search-box" type="text" id="js__template-search">
					<span class="mka-search-icon-wrap">
						<span class="mka-search-icon">
						</span>
						<div class="mka-bubbling">
							<span class="mka-bubbling-1">
							</span>
							<span class="mka-bubbling-2">
							</span>
							<span class="mka-bubbling-3">
							</span>
						</div>
					</span>
				</input>
			</div>
		</div>

		<div class="mka-select-wrap mka-cp-template-category-filter">
			<div class="mka-select">
				<input class="mka-select-box-value" id="js__templates-category-filter" type="hidden">
					<div class="mka-select-box">
						<?php esc_html_e( 'All Categories', 'mk_framework' ); ?>
					</div>
					<div class="mka-select-box-list-wrap mka-select-list-wrap">
						<div class="mka-select-list">
						</div>
					</div>
				</input>
			</div>
		</div>

		<div id="js__new-templates-list" class="mka-cp-template-items "></div>

		<div class="mka-clearfix"></div>

		<div class="abb-template-page-load-more" data-from="0">
			<svg class="mka-spinner" width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle class="mka-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
			</svg> 
		</div>
	</div>
</div>

<?php
/**
 * Export/Import view.
 *
 * @package Jupiter
 * @subpackage Control Panel
 * @since 6.0.4
 */

?>
<div class="mka-cp-pane-box" id="mk-cp-addons">
	<div class="mka-cp-export-wrap">
		<div class="mka-cp-pane-title">
			<?php esc_html_e( 'Export', 'mk_framework' ); ?>
			<div class="mka-wrap mka-tip-wrap">
				<a href="#" class="mka-tip">
					<span class="mka-tip-icon">
						<span class="mka-tip-arrow"></span>
					</span>
					<span class="mka-tip-ripple"></span>
				</a>
				<div class="mka-tip-content">
					<?php esc_html_e( 'Export your website data selectively. This feature is not intended for large data, complete backup or migration. For mentioned purposes, use a 3rd-party plugin.', 'mk_framework' ); ?>
				</div>
			</div>
		</div>
		<form class="mka-cp-export-form">
			<label>
				<input type="checkbox" name="check" value="Content" checked>
				<?php esc_html_e( 'Site Content', 'mk_framework' ); ?>
			</label>
			<label>
				<input type="checkbox" name="check" value="Widgets" checked>
				<?php esc_html_e( 'Widgets', 'mk_framework' ); ?>
			</label>
			<label>
				<input type="checkbox" name="check" value="Theme Options" checked>
				<?php esc_html_e( 'Theme Options', 'mk_framework' ); ?>
			</label>
			<label>
				<input type="checkbox" name="check" value="Customizer" checked>
				<?php esc_html_e( 'Customizer', 'mk_framework' ); ?>
			</label>
			<button type="submit" class="mka-button mka-button--blue mka-button--medium mka-cp-export-btn"><?php esc_html_e( 'Export', 'mk_framework' ); ?></button>
		</form>
	</div>
	<div class="mka-cp-import-wrap">
		<div class="mka-cp-pane-title">
			<?php esc_html_e( 'Import', 'mk_framework' ); ?>
			<div class="mka-wrap mka-tip-wrap">
				<a href="#" class="mka-tip">
					<span class="mka-tip-icon">
						<span class="mka-tip-arrow"></span>
					</span>
					<span class="mka-tip-ripple"></span>
				</a>
				<div class="mka-tip-content">
					<?php esc_html_e( "Import your website data selectively. This only accepts an exported Zip file from above section. Site Content and Widgets are merged in current data. Theme Options's and Customizer's data are replaced. For installing templates please go to Control Panel -> Templates.", 'mk_framework' ); ?>
				</div>
			</div>
		</div>
		<div>
			<div class="mka-wrap mka-upload-wrap">
				<div class="mka-upload">
					<input class="mka-textfield" placeholder="Select a Zip file" type="text">
					<a href="#" class="mka-upload-btn mka-cp-import-upload-btn">
						<span class="mka-upload-btn-icon"></span>
					</a>
				</div>
			</div>
			<button type="submit" class="mka-button mka-button--blue mka-button--medium mka-cp-import-btn"><?php esc_html_e( 'Import', 'mk_framework' ); ?></button>
		</div>
	</div>
</div>

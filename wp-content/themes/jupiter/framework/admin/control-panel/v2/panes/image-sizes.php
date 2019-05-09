<?php
$control_panel = new mk_control_panel();
$saved_sizes = $control_panel->get_image_size_data();
?>


<div class="mka-cp-pane-box" id="mk-cp-image-sizes">
	<div class="mka-cp-pane-title">
		<?php esc_html_e( 'Image Sizes', 'mk_framework' ); ?>
		<div class="mka-wrap mka-tip-wrap">
			<a class="mka-tip" href="#">
				<span class="mka-tip-icon">
					<span class="mka-tip-arrow">
					</span>
				</span>
				<span class="mka-tip-ripple">
				</span>
			</a>
			<div class="mka-tip-content">
				<?php esc_html_e( 'In this page you can add custom image sizes to be used in various locations such as shortcodes as well as theme options.', 'mk_framework' ); ?>
				</a>
			</div>
		</div>
	</div>

	<div class="mka-wrap mka-clist-wrap">
		<div class="mka-clist">

			<div class="mka-clist-list js__mka-clist-list">
				<?php
				// print_r($saved_sizes);
				foreach ( $saved_sizes as $size ) {
					if ( ! empty( $size['size_n'] ) && ! empty( $size['size_w'] ) && ! empty( $size['size_h'] ) ) {
					?>
						<div class="mka-clist-item js__cp-clist-item">
							<div class="mka-clist-item-inner">
								<div class="mka-clist-item-content">
									<span class="mka-clist-item-holder js__size-name"><?php echo $size['size_n']; ?></span>
									<span class="mka-clist-item-holder mka-highlight js__size-dimension"><?php echo $size['size_w']; ?>px <?php echo $size['size_h']; ?>px</span>
									<span class="mka-clist-item-holder mka-passive">
										<span class="js__size-crop">
										<?php
										if ( $size['size_c'] == 'on' ) {
											echo '&#x2713;';
										} else {
											echo '&#10005;'; }
?>
</span>
										<?php esc_html_e( 'Image Sizes', 'mk_framework' ); ?>
									</span>
								</div>
								<div class="mka-clist-buttons">
									<a class="mka-cp-clist-edit js__cp-clist-edit-item" href="#">
										<span class="mka-clist-edit-icon">
										</span>
										<span class="mka-clist-edit-ripple">
									</span>
								</a>
								<a class="mka-cp-clist-remove js__cp-clist-remove-item" href="#">
									<span class="mka-clist-remove-icon">
									</span>
									<span class="mka-clist-remove-ripple">
									</span>
								</a>
								</div>

								<input name="size_n" type="hidden" value="<?php echo esc_attr( $size['size_n'] ); ?>">
								<input name="size_w" type="hidden" value="<?php echo esc_attr( $size['size_w'] ); ?>">
								<input name="size_h" type="hidden" value="<?php echo esc_attr( $size['size_h'] ); ?>">
								<input name="size_c" type="hidden" value="<?php echo esc_attr( $size['size_c'] ); ?>">

							</div>
						</div>
				<?php
					}
				}
					?>

								 </div>

			<a class="mka-cp-clist-add js__cp-clist-add-item" href="#" style="top: auto; display: block; transform: matrix(1, 0, 0, 1, 0, 0);">
				<span class="mka-clist-add-icon">
				</span>
				<span class="mka-clist-add-text" style="opacity: 1;">
					<?php esc_html_e( 'Add a New Size', 'mk_framework' ); ?>
				</span>
			</a>
			<div class="mka-clist-addbox mka-clist-addbox-clone">
				<div class="mka-clist-item-add-content">
					<div class="mka-image-size-name-option">
						<span class="mka-clist-social-list-title">
							<?php esc_html_e( 'Size Name', 'mk_framework' ); ?>
						</span>
						<input class="mka-textfield" name="size_n" type="text">
					</div>
					<div class="mka-image-size-width-option">
						<span class="mka-clist-social-list-title">
							<?php esc_html_e( 'Image Width', 'mk_framework' ); ?>
						</span>
						<input class="mka-textfield" name="size_w" type="number" step="1" min="50">

											</div>

					<div class="mka-image-size-height-option">
						<span class="mka-clist-social-list-title">
							<?php esc_html_e( 'Image Height', 'mk_framework' ); ?>
						</span>
						<input class="mka-textfield" name="size_h" type="number" step="1" min="50">
					</div>                
					<div class="mka-image-size-crop-option">
						<span class="mka-clist-social-list-title">
							<?php esc_html_e( 'Hard Crop?', 'mk_framework' ); ?>
						</span>
						<div class="mka-checkbox-wrap">
							<input class="mka-checkbox" type="checkbox" name="size_c" checked="checked">
							<div class="mka-checkbox-skin">
								<div class="mka-checkbox-bg"></div>
								<div class="mka-checkbox-bullet" style="opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);"></div>
								<div class="mka-checkbox-bullet-inactive" style="opacity: 0;"></div>
								<div class="mka-checkbox-ripple" style="opacity: 0; transform: matrix(1, 0, 0, 1, 0, 0);"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="mka-clist-item-add-buttons">
					<a class="mka-cp-clist-cancel-item js__cp-clist-cancel-item mka-button mka-button--darkgray mka-button--small mka-button--float" href="#">
						<svg height="10" id="Layer_1" version="1.1" viewbox="0 0 16 16" width="10" x="0px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" y="0px">
							<line style="fill:none;stroke:#ffffff;stroke-width:3;stroke-miterlimit:10;" x1="0.8" x2="15.2" y1="0.8" y2="15.3">
							</line>
							<line style="fill:none;stroke:#ffffff;stroke-width:3;stroke-miterlimit:10;" x1="15.2" x2="0.8" y1="0.8" y2="15.3">
							</line>
						</svg>
					</a>
					<a class="mka-cp-clist-apply-item js__cp-clist-apply-item mka-button mka-button--green mka-button--small mka-button--float" href="#">
						<svg height="18" id="Layer_1" version="1.1" viewbox="0 0 16 16" width="18" x="0px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" y="0px">
							<polyline points="2.5,7.3 5.7,10.6 12.7,3.6 " style="fill:none;stroke:#ffffff;stroke-width:2;stroke-miterlimit:10;">
							</polyline>
						</svg>
					</a>
				</div>
			</div>

			<!-- item clone, do not remove it -->
			<div class="mka-clist-item js__cp-clist-item mka-clist-item-clone">
				<div class="mka-clist-item-inner">
					<div class="mka-clist-item-content">
						<span class="mka-clist-item-holder js__size-name"></span>
						<span class="mka-clist-item-holder mka-highlight js__size-dimension"></span>
						<span class="mka-clist-item-holder mka-passive"><span class="js__size-crop"></span> Hard crop</span>
					</div>
					<div class="mka-clist-buttons">
						<a class="mka-cp-clist-edit js__cp-clist-edit-item" href="#">
							<span class="mka-clist-edit-icon">
							</span>
							<span class="mka-clist-edit-ripple">
						</span>
					</a>
					<a class="mka-cp-clist-remove js__cp-clist-remove-item" href="#">
						<span class="mka-clist-remove-icon">
						</span>
						<span class="mka-clist-remove-ripple">
						</span>
					</a>
					</div>
					<input name="size_n" type="hidden" value="">
					<input name="size_w" type="hidden" value="">
					<input name="size_h" type="hidden" value="">
					<input name="size_c" type="hidden" value="">
				</div>
			</div>
			<!-- End of clone item --> 

		</div>
		<?php wp_nonce_field( 'ajax-image-sizes-options', 'security' ); ?>   
	</div>
</div>

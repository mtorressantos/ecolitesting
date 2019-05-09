
<div class="mka-cp-pane-box" id="mk-cp-icon-library">
	<div class="mk-cp-icon-library-wrap">
		<div class="mka-cp-pane-title">
			<?php esc_html_e( 'Icon Library', 'mk_framework' ); ?>
			<div class="mka-wrap mka-tip-wrap">
					<a href="#" class="mka-tip">
						<span class="mka-tip-icon">
							<span class="mka-tip-arrow"></span>
						</span>
						<span class="mka-tip-ripple"></span>
					</a>
					<div class="mka-tip-content">
					<?php esc_html_e( 'Search and find your desired icon and paste its "class name" into the "text field" provided in shortcode options.', 'mk_framework' ); ?>
					</div>
				</div>
		</div>


		<div class="mka-search-wrap mka-cp-icon-lib-search">
			<div class="mka-search">
				<input class="mka-search-box" id="js__icon-lib-search" type="text">
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


		<div class="mka-select-wrap mka-cp-icon-lib-category-filter">
			<div class="mka-select">
				<input class="mka-select-box-value" id="js__icon-lib-category-filter" type="hidden">
					<div class="mka-select-box">
						<?php esc_html_e( 'All Libraries', 'mk_framework' ); ?>
					</div>
					<div class="mka-select-box-list-wrap mka-select-list-wrap">
						<div class="mka-select-list">
							<span class="mka-select-list-item" data-value="">
								<?php esc_html_e( 'All Libraries', 'mk_framework' ); ?>
							</span>
							<span class="mka-select-list-item" data-value="line">
								<?php esc_html_e( 'Line', 'mk_framework' ); ?>
							</span>
							<span class="mka-select-list-item" data-value="icomoon">
								<?php esc_html_e( 'Icomoon', 'mk_framework' ); ?>
							</span>
							<span class="mka-select-list-item" data-value="fontawesome">
								<?php esc_html_e( 'Fontawesome', 'mk_framework' ); ?>
							</span>
						</div>
					</div>
				</input>
			</div>
		</div>


		<div class="mka-cp-icon-libs-items" id="js__icon-lib-list"></div>
		<div class="mka-clearfix"></div>

		<div class="mka-icon-lib-page-load-more" data-from="0">
			<svg class="mka-spinner" height="50px" viewbox="0 0 66 66" width="50px" xmlns="http://www.w3.org/2000/svg">
				<circle class="mka-spinner-path" cx="33" cy="33" fill="none" r="30" stroke-linecap="round" stroke-width="6">
				</circle>
			</svg>
		</div>
	</div>
</div>

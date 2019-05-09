<?php
	$mk_control_panel = new mk_control_panel();
	$announcements = $mk_control_panel->get_announcements();
?>
<div class="mka-cp-pane-box" id="mk-cp-announcements">
	<div class="mka-cp-pane-title">
		<?php esc_html_e( 'Announcements', 'mk_framework' ); ?>
	</div>
	<ul class="mka-cp-announcement-ul">
		<?php
		if ( ! empty( $announcements ) && is_array( $announcements ) ) {

			foreach ( $announcements as $announcement ) {

				$title = isset( $announcement[1] ) ? $announcement[1] : '';
				$date = isset( $announcement[3] ) ? mysql2date( 'j F Y', $announcement[3] ) : '';
				$link = isset( $announcement[4] ) ? $announcement[4] : '';

				?>
				<li class="mka-cp-announcement-item">
					<a class="mka-cp-announcement-item-link" target="_blank" href="<?php echo $link; ?>">
						<span class="mka-cp-announcement-item-icon"></span>
						<span class="mka-cp-announcement-item-text"><?php echo $title; ?></span>
						<span class="mka-announcement-item-date"><?php echo $date; ?></span>
					</a>
					</li>
			<?php
			}
		}
		?>

			 </ul>

	<a class="mka-cp-older-announcements" target="_blank" href="https://themes.artbees.net/support/jupiter/announcements/">
		<?php esc_html_e( 'Older Announcements >', 'mk_framework' ); ?>
	</a>
</div>

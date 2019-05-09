<?php

$mk_control_panel = new mk_control_panel();

$announcements = $mk_control_panel->get_announcements();
?>
 <div class="control-panel-holder">

<?php
echo mk_get_control_panel_view(
	'header', true, array(
		'page_slug' => 'theme-announcements',
	)
);
?>

<div class="cp-pane clearfix">

<h3><?php _e( 'Latest Announcements', 'mk_framework' ); ?></h3>

<ul class="cp-announcement-box">
	<?php
	if ( ! empty( $announcements ) && is_array( $announcements ) ) {
		foreach ( $announcements as $announcement ) {
		?>
			<li>
				<h4 class="cp-announcement-title"><?php echo $announcement[1]; ?></h4>
				<span class="cp-announcement-date"><?php echo mysql2date( 'j F Y', $announcement[3] ); ?></span>
				<p><?php echo strip_tags( $announcement[2] ); ?></p>
				<a class="cp-announcement-more-link" href="<?php echo $announcement[4]; ?>" target="_blank"><?php _e( 'Learn More', 'mk_framework' ); ?></a>
			</li>
		<?php
		}
	} else {
		echo $announcements;
	}
	?>
</ul>
<a class="cp-all-announcements-link" href="https://themes.artbees.net/support/jupiter/announcements/"><?php _e( 'Older Announcements >', 'mk_framework' ); ?></a>
	
</div>
</div>

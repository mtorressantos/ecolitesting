<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$class = $el_class;
$class .= ' ' . $visibility;

?>

<aside id="mk-sidebar" class="<?php echo $class; ?>">
	<div class="sidebar-wrapper" style="padding:0;">
		<?php dynamic_sidebar( $sidebar ); ?>
	</div>
</aside>


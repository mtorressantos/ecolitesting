<?php

$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$id = Mk_Static_Files::shortcode_id();

$attr[] = 'data-style="'.$action_style.'"';
$attr[] = 'data-initialIndex="'.$open_toggle.'"';
$attr[] = 'id="mk-accordion-'.$id.'"';

$class[] = 'mobile-'.(($responsive == 'true') ? 'false' : 'true');
$class[] = $style;
$class[] = $el_class;

if ( ! empty( $visibility ) ) {
	echo '<div class="' . $visibility . '">';
}

Mk_Static_Files::addCSS('#mk-accordion-'.$id.' .mk-accordion-pane{'.$container_bg_color.'}', $id);

mk_get_view('global', 'shortcode-heading', false, ['title' => $heading_title]); ?>

<div <?php echo implode(' ', $attr); ?> class="mk-accordion <?php echo implode(' ', $class); ?>">
	<div class="wpb_accordion_wrapper">
		<?php echo wpb_js_remove_wpautop($content); ?>
	</div>
</div>

<?php if ( ! empty( $visibility ) ) : ?>
</div>
<?php endif; ?>


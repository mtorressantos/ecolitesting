<?php

/**
 * template part for header toolbar date. views/header/toolbar
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */
?>

<span class="mk-header-date"><?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-moon-clock', 16) ?><?php echo date_i18n("F j, Y"); ?></span>

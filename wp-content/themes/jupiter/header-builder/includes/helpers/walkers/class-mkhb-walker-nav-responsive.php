<?php
/**
 * Jupiter: MKHB_Walker_Nav_Responsive class.
 *
 * @package Jupiter
 * @subpackage Custom Nav Walker
 * @since 5.9.5
 */

/**
 * Main menu responsive walker.
 *
 * @todo Move it to HB and fix it later.
 *
 * @since 5.9.5
 * @since 6.0.0 Fix some PHPMD warnings.
 *
 * @see mk_main_menu_responsive_walker
 *
 * @SuppressWarnings(PHPMD)
 */
class MKHB_Walker_Nav_Responsive extends Walker_Nav_Menu {

	/**
	 * The prefix of sub menu.
	 *
	 * @since 5.9.5
	 * @since 6.0.0 Fix some PHPMD warnings.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 * @param array  $args   Additional argument for sub menu prefix.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$mk_svg_icons = new Mk_SVG_Icons();
		if ( 0 === $depth ) {
			$output .= '<span class="mkhb-navigation-resp__arrow mkhb-navigation-resp__sub-closed">' . $mk_svg_icons::get_svg_icon_by_class_name( false, 'mk-moon-arrow-down', 16 ) . '</span>';
		}
		$output .= "\n$indent<ul class=\"sub-menu {locate_class}\">\n";
	}

	/**
	 * The suffix of sub menu.
	 *
	 * @since 5.9.5
	 * @since 6.0.0 Fix some PHPMD warnings.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 * @param array  $args   Additional argument for sub menu prefix.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
		$output = str_replace( '{locate_class}', '', $output );
	}

	/**
	 * Start of menu items.
	 *
	 * @todo Add some additional docblock to explain how this function works.
	 *
	 * @since 5.9.5
	 * @since 6.0.0 Fix some PHPMD warnings.
	 *
	 * @param  string  $output            HTML markup of current menu item.
	 * @param  object  $item              Menu item details.
	 * @param  integer $depth             The depth of sub menu level.
	 * @param  array   $args              Additional arguments of current menu item.
	 * @param  integer $current_object_id Current menu item object ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$item_output = '';
		$this->megamenu_widgetarea = get_post_meta( $item->ID, '_menu_item_megamenu_widgetarea', true );
		$this->menu_icon = get_post_meta( $item->ID, '_menu_item_menu_icon', true );

		$mk_svg_icons = new Mk_SVG_Icons();
		$menu_icon_tag = ! empty( $this->menu_icon ) ? $mk_svg_icons::get_svg_icon_by_class_name( false, $this->menu_icon, 16 ) : '';
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : ' href="#"';

		$item_output .= $args->before;
		$item_output .= '<a class="menu-item-link js-smooth-scroll" ' . $attributes . '>';
		$item_output .= $menu_icon_tag;
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = '';
		$value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) , $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="responsive-menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

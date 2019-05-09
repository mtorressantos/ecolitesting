<?php
/**
 * Jupiter: MKHB_Walker_Nav_Burger class.
 *
 * @package Jupiter
 * @subpackage Custom Nav Walker
 * @since 5.9.5
 */

/**
 * Main menu burger style walker.
 *
 * @since 5.9.5
 * @since 6.0.0 Fix PHPMD warnings.
 *
 * @see header_icon_walker
 *
 * @SuppressWarnings(PHPMD)
 */
class MKHB_Walker_Nav_Burger extends Walker_Nav_Menu {

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

		/**
		 * Code above is used to set different icon between Fullscreen and Dashboard.
		 * Right now we only support Fullscreen, so, Dashboard will be removed.
		 *
		 * @see header_icon_walker
		 */
		$mk_svg_icons = new Mk_SVG_Icons();
		$output .= '<span class="menu-sub-level-arrow">' . $mk_svg_icons::get_svg_icon_by_class_name( false, 'mk-moon-arrow-down', 16 ) . '</span>';

		$output .= "\n$indent<ul class=\"sub-menu \">\n";
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
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$this->menu_icon = get_post_meta( $item->ID, '_menu_item_menu_icon', true );

		$class_names = '';
		$value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) , $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		$mk_svg_icons = new Mk_SVG_Icons();
		$menu_icon_tag = ! empty( $this->menu_icon ) ? '<span class="menu-item-icon">' . $mk_svg_icons::get_svg_icon_by_class_name( false, $this->menu_icon, 16 ) . '</span>' : '';

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $menu_icon_tag . $args->link_before . '<span class="meni-item-text">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span>';
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Add a conditional statement for preventing fullscreen-menu items display
	 * their sub items.
	 *
	 * @since 5.9.5
	 * @since 6.0.0 Fix some PHPMD warnings.
	 *
	 * @see Walker::display_element()
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Passed by reference. Used to append additional content.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];
		$id_el = $element->$id_field;

		// Display this element.
		$this->has_children = ! empty( $children_elements[ $id_el ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args[0]['has_children'] = $this->has_children; // Back-compat.
		}

		$cb_args = array_merge( array( &$output, $element, $depth ), $args );
		call_user_func_array( array( $this, 'start_el' ), $cb_args );

		// Descend only when the depth is right and there are childrens for this element.
		if ( ( 0 === $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id_el ] ) ) {

			foreach ( $children_elements[ $id_el ] as $child ) {

				if ( ! isset( $newlevel ) ) {
					$newlevel = true;
					// Start the child delimiter.
					$cb_args = array_merge( array( &$output, $depth ), $args );
					call_user_func_array( array( $this, 'start_lvl' ), $cb_args );
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id_el ] );
		}

		if ( ! empty( $newlevel ) ) {
			// End the child delimiter.
			$cb_args = array_merge( array( &$output, $depth ), $args );
			call_user_func_array( array( $this, 'end_lvl' ), $cb_args );
		}

		// End this element.
		$cb_args = array_merge( array( &$output, $element, $depth ), $args );
		call_user_func_array( array( $this, 'end_el' ), $cb_args );
	}

	/**
	 * Add a conditional statement for preventing fullscreen-menu items display
	 * close ul tag for the child.
	 *
	 * @since 5.9.5
	 * @since 6.0.0 Fix some PHPMD warnings.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$tab = "\t";
		$new_line = "\n";

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$tab = '';
			$new_line = '';
		}

		$indent = str_repeat( $tab, $depth );
		$output .= "$indent</ul>{$new_line}";
	}

}

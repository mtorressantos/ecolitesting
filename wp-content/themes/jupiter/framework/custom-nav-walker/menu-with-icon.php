<?php
/**
 * Used for header style 3 & 4 that appends only icon
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.3
 * @last_update Version 5.6
 * @package     artbees
 */
class header_icon_walker extends Walker_Nav_Menu {


	 /**
	  * @see Walker::start_lvl()
	  *
	  * @param string $output Passed by reference. Used to append additional content.
	  * @param int    $depth Depth of page. Used for padding.
	  */
	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$menu_location = ! empty( $args->theme_location ) ? $args->theme_location : '';

		// Check if current menu is fullscreen-menu
		/*
		if ( $menu_location == 'fullscreen-menu' ) {
            $output .= '';
        } else {*/
			$indent = str_repeat( "\t", $depth );

		if ( $menu_location == 'fullscreen-menu' ) {
			$output .= '<span class="menu-sub-level-arrow">' . Mk_SVG_Icons::get_svg_icon_by_class_name( false, 'mk-moon-arrow-down', 16 ) . '</span>';
		} else {
			$output .= '<span class="menu-sub-level-arrow">' . Mk_SVG_Icons::get_svg_icon_by_class_name( false, 'mk-icon-angle-right', 16 ) . '</span>';
		}

			$output .= "\n$indent<ul class=\"sub-menu \">\n";

		if ( $menu_location != 'fullscreen-menu' ) {
			$output .= '<li class="mk-vm-back"><a href="#">' . Mk_SVG_Icons::get_svg_icon_by_class_name( false, 'mk-icon-angle-left', 16 ) . __( 'Back', 'mk_framework' ) . '</a></li>';
		}
		/*}*/
	}



	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
		global $wp_query;
		$indent = ($depth) ? str_repeat( "\t", $depth ) : '';

		$this->menu_icon = get_post_meta( $item->ID, '_menu_item_menu_icon', true );

				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;

				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) , $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

				$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

				$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

				$menu_icon_tag = ! empty( $this->menu_icon ) ? '<span class="menu-item-icon">' . Mk_SVG_Icons::get_svg_icon_by_class_name( false, $this->menu_icon, 16 ) . '</span>' : '';

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

		// Check theme_location object is exist
		/*
		if ( ! empty( $args[0]->theme_location ) ) {
            // Check if current menu is fullscreen-menu and menu item is sub menu
            if ( $args[0]->theme_location == 'fullscreen-menu' && $element->menu_item_parent != '0' ) {
                return;
            }
        }*/

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;

		// display this element
		$this->has_children = ! empty( $children_elements[ $id ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args[0]['has_children'] = $this->has_children; // Back-compat.
		}

		$cb_args = array_merge( array( &$output, $element, $depth ), $args );
		call_user_func_array( array( $this, 'start_el' ), $cb_args );

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {

			foreach ( $children_elements[ $id ] as $child ) {

				if ( ! isset( $newlevel ) ) {
					$newlevel = true;
					// start the child delimiter
					$cb_args = array_merge( array( &$output, $depth ), $args );
					call_user_func_array( array( $this, 'start_lvl' ), $cb_args );
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset( $newlevel ) && $newlevel ) {
			// end the child delimiter
			$cb_args = array_merge( array( &$output, $depth ), $args );
			call_user_func_array( array( $this, 'end_lvl' ), $cb_args );
		}

		// end this element
		$cb_args = array_merge( array( &$output, $element, $depth ), $args );
		call_user_func_array( array( $this, 'end_el' ), $cb_args );
	}

	/**
	 * Add a conditional statement for preventing fullscreen-menu items display
	 * close ul tag for the child.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$menu_location = ! empty( $args->theme_location ) ? $args->theme_location : '';

		// Check if current menu is fullscreen-menu
		/*
		if ( $menu_location == 'fullscreen-menu' ) {
            $output .= '';
        } else {*/
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
			$indent = str_repeat( $t, $depth );
			$output .= "$indent</ul>{$n}";
		/*}*/
	}

}

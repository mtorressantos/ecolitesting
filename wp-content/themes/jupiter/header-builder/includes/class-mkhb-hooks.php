<?php
/**
 * Header Builder: Store HB Hooks, MKHB_Hooks class.
 *
 * For use in front end integration with Jupiter.
 *
 * @package Header_Builder
 * @subpackage Elements_Generator
 * @since 6.0.0
 */

/**
 * Store hooks after HB rendering shortcodes in HB Custom Header.
 *
 * @since 6.0.0
 */
class MKHB_Hooks {
	/**
	 * Main instance of MKHB_Hooks class.
	 *
	 * @since 6.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * All hooks saved.
	 *
	 * @since 6.0.0
	 *
	 * @var array
	 */
	public static $hooks;

	/**
	 * Return the *Singleton* instance of this class.
	 *
	 * @since 6.0.0
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Enqueue hooks.
	 *
	 * @since 6.0.0
	 *
	 * @param string $key   Hooks name.
	 * @param string $value Hooks value.
	 */
	public static function set_hook( $key, $value ) {
		$hooks = self::$hooks;

		// If empty.
		if ( ! is_array( $hooks ) || empty( $hooks ) ) {
			$hooks = array();
		}

		// Check key exist or not.
		if ( ! isset( $hooks[ $key ] ) ) {
			$hooks[ $key ] = array();
		}

		// Push the value into key.
		$hooks[ $key ][] = $value;

		self::$hooks = $hooks;
	}

	/**
	 * Enqueue hooks with concated value.
	 *
	 * @since 6.0.3
	 *
	 * @param string $key   Hooks name.
	 * @param string $value Hooks value.
	 */
	public static function concat_hook( $key, $value = '' ) {
		// Concat only allow string value.
		if ( ! is_string( $value ) || empty( $value ) ) {
			return false;
		}

		$hooks = self::$hooks;

		// If hooks empty.
		if ( ! is_array( $hooks ) || empty( $hooks ) ) {
			$hooks = array();
		}

		// Check key exist or not. Default is empty string.
		if ( ! isset( $hooks[ $key ] ) ) {
			$hooks[ $key ] = '';
		}

		// Concat the value into existing value on the key.
		$hooks[ $key ] .= $value;

		self::$hooks = $hooks;
	}

	/**
	 * Return all saved hooks.
	 *
	 * @since 6.0.0
	 *
	 * @return array Hooks list.
	 */
	public static function get_hooks() {
		if ( is_null( self::$hooks ) ) {
			self::$instance = array();
		}

		return self::$hooks;
	}

	/**
	 * Return single saved hook.
	 *
	 * @since 6.0.0
	 *
	 * @param  string $key     Hook name or key.
	 * @param  mixed  $default Hook default value.
	 * @return array           Hooks data.
	 */
	public static function get_hook( $key, $default ) {
		$hooks = self::get_hooks();

		if ( ! empty( $hooks[ $key ] ) ) {
			return $hooks[ $key ];
		}

		return $default;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 *
	 * @since 6.0.0
	 */
	protected function __construct() {}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @since 6.0.0
	 */
	private function __clone() {}
}

/**
 * Create a global function to get Hooks instance.
 *
 * @since 6.0.0
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function mkhb_hooks() {
	return MKHB_Hooks::get_instance();
}

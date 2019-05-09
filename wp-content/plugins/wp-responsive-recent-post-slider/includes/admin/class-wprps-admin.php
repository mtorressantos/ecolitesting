<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP Responsive Recent Post Slider
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wprps_Admin {
	
	function __construct() {
		
		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wprps_register_menu'), 12 );

		// Admin init process
		add_action( 'admin_init', array($this, 'wprps_admin_init_process') );
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wprps_register_menu() {

		// Register plugin premium page
		add_submenu_page( 'wprps-about', __('Upgrade to PRO - Recent Post Slider', 'wp-responsive-recent-post-slider'), '<span style="color:#2ECC71">'.__('Upgrade to PRO', 'wp-responsive-recent-post-slider').'</span>', 'edit_posts', 'wprps-premium', array($this, 'wprps_premium_page') );

		add_submenu_page(  'wprps-about', __('Hire Us', 'wp-responsive-recent-post-slider'), '<span style="color:#2ECC71">'.__('Hire Us', 'wp-responsive-recent-post-slider').'</span>', 'manage_options', 'wprps-hireus', array($this, 'wprps_hireus_page') );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wprps_premium_page() {
		include_once( WPRPS_DIR . '/includes/admin/settings/premium.php' );
	}

	function wprps_hireus_page() {		
		include_once( WPRPS_DIR . '/includes/admin/settings/hire-us.php' );
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.4.3
	 */
	function wprps_admin_init_process() {
		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wprps-plugin-notice' ) {
	    	set_transient( 'wprps_install_notice', true, 604800 );
	    }
	}
}

$wprps_admin = new Wprps_Admin();
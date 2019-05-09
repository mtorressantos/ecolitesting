<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Responsive Recent Post Slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Wppsac_Script {
	
	function __construct() {
		
		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wppsac_front_style') );
		
		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wppsac_front_script') );	
		
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wppsac_front_style() {
		
		// Registring and enqueing slick slider css
		if( !wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WPRPS_URL.'assets/css/slick.css', array(), WPRPS_VERSION );
			wp_enqueue_style( 'wpos-slick-style' );
		}
		
		// Registring and enqueing public css
		wp_register_style( 'wppsac-public-style', WPRPS_URL.'assets/css/recent-post-style.css', array(), WPRPS_VERSION );
		wp_enqueue_style( 'wppsac-public-style' );
	}
	
	/**
	 * Function to add script at front side
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wppsac_front_script() {
		
		// Registring slick slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WPRPS_URL.'assets/js/slick.min.js', array('jquery'), WPRPS_VERSION, true );
		}
		
		// Registring and enqueing public script
		wp_register_script( 'wppsac-public-script', WPRPS_URL.'assets/js/wppsac-public.js', array('jquery'), WPRPS_VERSION, true );
		wp_localize_script( 'wppsac-public-script', 'Wppsac', array(
																	'is_mobile' => (wp_is_mobile()) ? 1 : 0,
																	'is_rtl' 	=> (is_rtl()) 		? 1 : 0
																	));
	}
	
}

$wppsac_script = new Wppsac_Script();
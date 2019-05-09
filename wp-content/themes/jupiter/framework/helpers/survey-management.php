<?php
/**
 * This class is responsible manage all jupiter's related survey
 * it will communicate with Artbees Portal and get latest survey.
 *
 * @author       Reza Marandi <ross@artbees.net>, Ugur Mirza Zeyrek <mirza@artbees.net>, Sheikh Danish Iqbal <danish@artbees.net>
 * @copyright    Artbees LTD (c)
 *
 * @link         http://artbees.net
 * @since        5.8
 *
 * @version      1.0
 */

class mk_survey_management {


	// Time period between sending requests to server
	private $time_difference = '+6 hours';
	private $logger;

	/*====================== MAIN SECTION ============================*/

	/**
	 * mk_survey_management constructor.
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	public function __construct() {
		add_action( 'admin_init', array( &$this, 'survey_check' ) );
		add_action( 'admin_footer', array( &$this, 'rendered_html' ), 100 );
		add_action( 'wp_ajax_abb_never_show_survey_again', array(&$this,'abb_never_show_survey_again'));
		add_action( 'wp_ajax_abb_remind_survey_later', array(&$this,'abb_remind_survey_later'));
		$this->logger = new Devbees\BeesLog\logger();
	}

	/**
	 * Gets the post request for never show this survey
	 *
	 * @return bool
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	public function abb_never_show_survey_again(){
		if(isset($_POST['survey_id']) and is_numeric($_POST['survey_id']) and current_user_can('administrator')) {
			$result = update_option('mk_never_show_survey_again_'.$_POST['survey_id'],true);
			wp_die($result);
		} else {
			wp_die("Unauthorized request");
			return false;
		}
	}

	/**
	 * Deletes the mk_never_show_survey_again options. Added for testing purposes. Usage example:
	 * yourdomain.com/wp-admin/?delete_mk_never_show_survey_again=1
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	public function delete_mk_never_show_survey_again()
	{
		global $wpdb;
		$sql = "DELETE FROM {$wpdb->options} WHERE option_name like '\mk\_never\_show\_survey\_again\_%'";
		$wpdb->query($sql);
	}

	/**
	 * This method is responsible to check if time is right and user have minimum required privilege and also active api-key
	 * it will communicate with ATP and ask for any existence survey
	 *
	 * @return boolean
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	public function survey_check() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		if( isset($_GET['delete_mk_never_show_survey_again']) )
			$this->delete_mk_never_show_survey_again();

		if ( $this->user_level_check() == false || $this->timing_check() == false || $this->get_api_key() == false || $this->survey_cookie_lifetime_status_check() == false ) {
			return false;
		}

		$response = $this->create_request();
		if ( $response == false ) {
			return false;
		}

		update_option( 'mk_last_survey_check', date( 'Y-m-d H:i:s' ) );
		update_option( 'mk_survey_link', $response['survey_link'] );
		update_option( 'mk_survey_message', $response['message'] );
		update_option( 'mk_survey_action_status', $response['action_status'] );
		update_option( 'mk_survey_id', $response['survey_id'] );
		if(isset($response['cookie_lifetime']) and is_numeric($response['cookie_lifetime'])) {
			update_option('mk_survey_cookie_lifetime', $response['cookie_lifetime']);
		}
		return true;
	}

	/**
	 * This method is responsible to check active api-key on jupiter and return it
	 *
	 * @return mixed return false if api-key is not exists and return api-key itself if its exist
	 *
	 * @author      Ross Marandi
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	private function get_api_key() {
		$api_key = get_option( 'artbees_api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}
		return $api_key;
	}

	/**
	 * This method is responsible to create http body and post request to ATP and evaluate response
	 *
	 * @return mixed it will returl JSON if response is okay and false if there is a problem on connection
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	private function create_request() {
		$url  = 'https://themes.artbees.net/wp-admin/admin-ajax.php';
		$args = array(
			'method'   => 'POST',
			'blocking' => true,
			'timeout'  => 45,
			'headers'  => array(
				'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8',
			),
			'body'     => array(
				'action'  => 'abb_surveys_show_survey_by_api_key',
				'api_key' => $this->get_api_key(),
			),
		);
		$response = wp_remote_post( $url, $args );

		// Check if ARTBEES Theme response in error
		if ( is_wp_error( $response ) ) {
			$this->logger->debug( 'Survey Manager - Error in response', $response->get_error_message() );
			return false;
		}

		$result        = wp_remote_retrieve_body( $response );
		$return_result = @json_decode( $result, true );

		// Check if response is not encoded in JSON
		if ( empty( $return_result ) ) {
			$this->logger->debug( 'Survey Manager - Error in decoding', $result );
			return false;
		}

		return $return_result;
	}

	/**
	 * This method is responsible to check last time that user send request to ATP and
	 * if the request is expired return true to allow new request
	 *
	 * @return boolean true if allowed and false if last request is not expired
	 *
	 * @author      Ross Marandi
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	private function timing_check() {
		$survey_time = get_option( 'mk_last_survey_check' );

		if ( empty( $survey_time ) ) {
			return true;
		}

		$current_time      = date( 'Y-m-d H:i:s' );

		$last_request_time = date( 'Y-m-d H:i:s', strtotime( $survey_time . ' +' . $this->time_difference ) );
		if ( $last_request_time <= $current_time ) {
			return true;
		}

		return false;
	}

	/**
	 * This method is responsible to check whether the survey time cookie has expired or not
	 * if the cookie is alive return false
	 *
	 * @return boolean false if cookie is alive, else return true
	 *
	 * @author      Sheikh Danish Iqbal
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	private function survey_cookie_lifetime_status_check() {
		$cookie_lifetime_status = get_option('mk_survey_cookie_lifetime_status');

		if($cookie_lifetime_status) {
			$survey_cookie_life_time = get_option( 'mk_survey_cookie_lifetime' );
			
			if (!empty($survey_cookie_life_time)) {
				$current_time = date( 'Y-m-d H:i:s' );
				$survey_time = get_option( 'mk_last_survey_check' );

				$survey_cookie_time = strtotime($survey_time) + $survey_cookie_life_time;
				$survey_expiration_date = date('Y-m-d H:i:s', $survey_cookie_time);

				if ($survey_expiration_date >= $current_time) {
					//Cookie life is yet to expire
					$survey_message = "<script>console.log('Survey Will be alive after : " . $survey_expiration_date . " ')</script>";
					echo $survey_message;
					return false; 
				}
				else
				{
					update_option('mk_survey_cookie_lifetime_status', false);
				}
			}
		}
		return true;
	}

	/**
	 * This method is responsible to update cookie lifetime status to true when clicked on modal close or remind me later button
	 *
	 * @return None
	 *
	 * @author      Sheikh Danish Iqbal
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	public function abb_remind_survey_later(){
		update_option('mk_survey_cookie_lifetime_status', true);
		wp_die();
	}

	/**
	 * This method is responsible to check if the current user have administrator privilege or not
	 *
	 * @return boolean true if current user is admin and false if its not
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	private function user_level_check() {
		if (current_user_can('administrator') || current_user_can('super_admin')) {
			return true;
		}

		$user = wp_get_current_user();
		if ( $user->user_level > 9 ) {
			return true;
		}

		return false;
	}

	/**
	 * This method is responsible to create template of survey and print it (it call by admin_footer hook )
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 */
	public function rendered_html() {
		$mk_survey_link = get_option( 'mk_survey_link' );
		$mk_survey_id = get_option( 'mk_survey_id' );
		$mk_survey_message = get_option( 'mk_survey_message' );
		$mk_survey_action_status = get_option( 'mk_survey_action_status' );
		$mk_never_show_survey = get_option('mk_never_show_survey_again_'.$mk_survey_id);

		if(!$mk_never_show_survey) {
			// Rendering HTML
			if($mk_survey_message) {
				$survey_message = "<script>console.log('Survey Status : " . $mk_survey_message . " ')</script>";
				echo $survey_message;
			}

			if($mk_survey_link and $mk_survey_action_status) {
				wp_enqueue_style('control-panel-modal-plugin', THEME_CONTROL_PANEL_ASSETS . '/css/sweetalert.css');
				wp_enqueue_script('control-panel-sweet-alert', THEME_CONTROL_PANEL_ASSETS . '/js/sweetalert.min.js', array('jquery'));
				echo $this->create_survey_script($mk_survey_link, $mk_survey_id);
			}
		} else {
			$never_show_survey_message = "<script>console.log('User does not want to survey : " . $mk_survey_message . " ".$mk_survey_id." ')</script>";
			echo $never_show_survey_message;
		}

		// Empty survey related options
		delete_option( 'mk_survey_link' );
		delete_option( 'mk_survey_message' );
		delete_option( 'mk_survey_action_status' );
	}


	/**
	 * Gets the survey link and survey id and returns the survey script code
	 *
	 * @param $survey_link
	 * @param $survey_id
	 * @return string
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.8
	 * @last_update Version 5.8
	 *
	 * TODO: Remove rand from survey guid link before release
	 */
	public function create_survey_script($survey_link, $survey_id) {
		$survey_script = '<script>
					var remind_me_later = true;
					// check for browser support
					if ( window.addEventListener ) {
						// message handler
						window.addEventListener("message", function (e) {
							// check message origin
							var response = e.data["button_status"]; // response received in postMessage
							if(response == "hide"){
								remind_me_later = false;
								jQuery("#product-survey-iframe").hide();
								jQuery(".product-survey.sweet-alert button").hide();
								jQuery(".product-survey .mka-spinner").show();
							}
						}, false);
					}

					jQuery( document ).ready(function() { 

					swal({
					customClass: "product-survey",
					allowOutsideClick: true,
					showConfirmButton: true,
					showCancelButton: true,
					cancelButtonText: "Never show this again",
					confirmButtonColor: "#c1c1c1",
					confirmButtonText: "Remind me Later",
					title: \'<svg style="display:none;" class="mka-spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="mka-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg><a href="#" class="mka-modal-close-btn"></a><iframe id="product-survey-iframe" src="'.$survey_link.'" frameborder="0" width="520" height="430" style="overflow:hidden"></iframe>\',
					html: true });

					jQuery(".product-survey.sweet-alert button").hide();
					var product_survey_button_visibility = false;
					jQuery("#product-survey-iframe").load(function() {
						console.log("product-survey iframe loaded");
						if(product_survey_button_visibility == false) {
							jQuery(".product-survey.sweet-alert button").show();
							product_survey_button_visibility = true;
						} else if(product_survey_button_visibility == true) {
							jQuery(".product-survey.sweet-alert button").hide();
							jQuery(".product-survey .mka-spinner").hide();
							jQuery("#product-survey-iframe").show();
							product_survey_button_visibility = false;
						}
					});

					jQuery( ".mka-modal-close-btn" ).click(function() {
						swal.close();
						jQuery(\'#product-survey\').unbind(\'load\');
					});

					jQuery( ".product-survey .mka-modal-close-btn, .product-survey button.confirm" ).click(function() {
						if(remind_me_later) {
							jQuery.post(ajaxurl, {
								action: "abb_remind_survey_later",
							}).done(function(response) {
								console.log(\' Survey remind later successful. \');
							}).fail(function(data) {
								console.log(\'Request failed : \', data);
							});
						} else {
							console.log(\'Remind me later is disabled\');
						}
					});

					jQuery( ".product-survey.sweet-alert button.cancel" ).click(function() {
							jQuery.post(ajaxurl, {
							action: \'abb_never_show_survey_again\',
							survey_id: \''.$survey_id.'\',
						}).done(function(response) {
							console.log(\'Response : \', response);
						}).fail(function(data) {
							console.log(\'Request failed : \', data);
						});
					});
					});
					</script>';
		return $survey_script;
	}
}

global $abb_phpunit;
if ( empty( $abb_phpunit ) || $abb_phpunit == false ) {
	new mk_survey_management();
}
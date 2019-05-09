<?php
$api_key = get_option( 'artbees_api_key' );
$is_apikey = empty( $api_key ) ? false : true;
$has_api_key = empty( $api_key ) ? 'is-active' : '';
$no_api_key = empty( $has_api_key ) ? 'is-active' : '';

?>

<div class="mka-cp-pane-box" id="mk-cp-register-product">
	<div class="mka-cp-pane-title">
		<?php esc_html_e( 'Register', 'mk_framework' ); ?>
		<?php esc_html_e( THEME_NAME ); ?>
	</div>	

	<ul class="mka-cp-register-steps">
		<ol>
			<li>
			<?php
			printf(
				__( '%1$sSign up%2$s to %3$sArtbees Themes Portal%4$s', 'mk_framework' ),
				'<a target="_blank" href="https://themes.artbees.net/sign-up/">','</a>','<strong>','</strong>'
			);
			?>
			.</li>
			<li>
			<?php
			printf(
				__( 'Generate an API Key. Get your %1$spurchase code%2$s from themeforest and enter it in your dashboard in %3$sArtbees Themes website%4$s', 'mk_framework' ),
				'<a target="_blank" href="https://themeforest.net/downloads">','</a>','<a href="https://themes.artbees.net/">','</a>'
			);
			?>
			.</li>
			<li>
			<?php
			printf(
				__( 'Register your %1$sAPI Key%2$s. Enter your API key in the field below. All set!', 'mk_framework' ),
				'<a target="_blank" href="https://themes.artbees.net/dashboard/register-product/">','</a>'
			);
			?>
			</li>
		</ol>
	</ul>

	<div class="mka-cp-register-api register-product-form <?php echo esc_attr( $has_api_key ); ?>">
		<span class="mka-cp-register-api-label"><?php esc_html_e( 'Artbees Api Key', 'mk_framework' ); ?></span>
		<input class="mka-textfield" type="text" id="mka-cp-register-api-input" placeholder="<?php esc_html_e( 'Enter your API key in here', 'mk_framework' ); ?>">
		<a href="#" id="js__regiser-api-key-btn" class="mka-button mka-button--blue mka-button--medium"><?php esc_html_e( 'Register', 'mk_framework' ); ?></a>   
		<?php wp_nonce_field( 'mka-cp-ajax-register-api', 'security' ); ?>
	</div>
	<div class="mka-cp-revoke-api register-product-form <?php echo esc_attr( $no_api_key ); ?>">
		<span class="mka-cp-register-api-label"><?php esc_html_e( 'Artbees Api Key', 'mk_framework' ); ?></span>
		<div class="mka-cp-api-key-text"><?php echo esc_html_e( $api_key ); ?></div>
		<a href="#" id="js__revoke-api-key-btn" class="mka-button mka-button--blue mka-button--medium"><?php esc_html_e( 'Revoke this API key', 'mk_framework' ); ?></a>
	</div>


	<div class="mka-cp-hr"></div>

	<div class="mka-fsize-14"><?php esc_html_e( 'Any problem?', 'mk_framework' ); ?> <a target="_blank" href="https://www.youtube.com/watch?v=g6JdwtaK0Tk"><?php esc_html_e( 'View the tutorial here', 'mk_framework' ); ?></a></div><br>
	<iframe width="400" height="225" src="https://www.youtube.com/embed/g6JdwtaK0Tk?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe><br><br><br>
	<strong class="mka-fsize-14"><?php esc_html_e( 'Common issues', 'mk_framework' ); ?></strong>
	<ul class="mka-cp-common-issues">
		<li><a target="_blank" href="http://themes.artbees.net/faq/why-i-need-to-register-my-theme/"><?php esc_html_e( 'Why I need to register my theme?', 'mk_framework' ); ?></a></li>
		<li><a target="_blank" href="http://themes.artbees.net/faq/how-can-i-verify-my-api-key/"><?php esc_html_e( 'How can I verify my API Key?', 'mk_framework' ); ?></a></li>
		<li><a target="_blank" href="http://themes.artbees.net/faq/why-my-api-key-inactive/"><?php esc_html_e( 'Why my API key is inactive?', 'mk_framework' ); ?></a></li>
		<li><a target="_blank" href="http://themes.artbees.net/faq/what-are-the-benefits-of-registration/"><?php esc_html_e( 'What are the benefits of registration?', 'mk_framework' ); ?></a></li>
		<li><a target="_blank" href="http://themes.artbees.net/faq/how-can-i-obtain-my-purchase-code/"><?php esc_html_e( 'How can I obtain my Purchase code?', 'mk_framework' ); ?></a></li>
		<li><a target="_blank" href="http://themes.artbees.net/faq/i-get-this-error-when-registering-my-theme-duplicated-purchase-key-detected/"><?php esc_html_e( 'I get this error when registering my theme: Duplicated Purchase Key detected?', 'mk_framework' ); ?></a></li>
	</ul>
</div>
<!-- End of Pane -->

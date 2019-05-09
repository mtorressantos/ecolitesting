<?php

/**
 * Template part for header toolbar. views/header/holders
 *
 * @author Artbees
 * @package jupiter/views
 * @since 5.0.0
 * @since 5.9.3 Add Polylang plugin check.
 */

global $mk_options;

?>

<div class="mk-header-toolbar">

	<?php if ( $mk_options['header_grid'] == 'true' ) { ?>
		<div class="mk-grid header-grid">
	<?php } ?>

		<div class="mk-header-toolbar-holder">

		<?php

		do_action( 'header_toolbar_before' );

		if ( has_nav_menu( 'toolbar-menu' ) ) {
			mk_get_header_view( 'toolbar', 'nav' );
		}

		if ( ! empty( $mk_options['enable_header_date'] ) && $mk_options['enable_header_date'] === 'true' ) {
			mk_get_header_view( 'toolbar', 'date' );
		}

		if ( ! empty( $mk_options['header_toolbar_phone'] ) || ! empty( $mk_options['header_toolbar_email'] ) ) {
			mk_get_header_view( 'toolbar', 'contact' );
		}

		if ( ! empty( $mk_options['header_toolbar_tagline'] ) ) {
			mk_get_header_view( 'toolbar', 'tagline' );
		}

		if ( ( is_plugin_active( 'polylang/polylang.php' ) || defined( 'ICL_SITEPRESS_VERSION' ) ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			mk_get_header_view( 'toolbar', 'language-nav' );
		}

		if ( ! empty( $mk_options['header_search_location'] ) && $mk_options['header_search_location'] === 'toolbar' ) {
			mk_get_header_view(
				'global', 'search', [
					'location' => 'toolbar',
				]
			);
		}

		if ( ! empty( $mk_options['header_social_location'] ) && $mk_options['header_social_location'] === 'toolbar' ) {
			mk_get_header_view(
				'global', 'social', [
					'location' => 'toolbar',
				]
			);
		}

		if ( ! empty( $mk_options['header_toolbar_login'] ) && $mk_options['header_toolbar_login'] === 'true' ) {
			mk_get_header_view( 'toolbar', 'login' );
		}

		if ( ! empty( $mk_options['header_toolbar_subscribe'] ) && $mk_options['header_toolbar_subscribe'] === 'true' ) {
			mk_get_header_view( 'toolbar', 'subscribe' );
		}

		do_action( 'header_toolbar_after' );

		?>

		</div>

	<?php if ( $mk_options['header_grid'] == 'true' ) { ?>
		</div>
	<?php } ?>

</div>

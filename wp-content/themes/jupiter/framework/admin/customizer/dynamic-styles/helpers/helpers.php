<?php
/**
 * Helper functions for dynamic styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Typography helper for dynamic styles.
 *
 * @param string $setting  Customizer control setting name.
 * @param array  $excludes  Properties field keys to exludes.
 */
function mk_cs_typography( $setting, array $excludes = array() ) {
	$typography = mk_maybe_json_decode( mk_cz_get_option( $setting ) );
	$css = '';
	$excludes[] = 'source';

	if ( $typography ) {
		foreach ( $typography as $key => $value ) {
			if ( ! in_array( $key, $excludes, true ) ) {
				switch ( $key ) {
					case 'color':
						$css .= $key . ':' . $value . ';';
						break;
					case 'size':
						$css .= 'font-' . $key . ':' . $value . 'px;';
						break;

					default:
						$css .= 'font-' . $key . ':' . $value . ';';
						break;
				}
			}
		}

		// Check and add google fonts to filter.
		if ( ! empty( $typography->source ) && 'google-font' === $typography->source ) {
			add_filter( 'mk_google_fonts', function( $google_fonts ) use ( $typography ) {
				if ( ! in_array( $typography->family, $google_fonts, true ) ) {
					$google_fonts[] = $typography->family;
				}

				return $google_fonts;
			} );
		}
	}
	return $css;
}

/**
 * BoxModel helper for dynamic styles.
 *
 * @param string $setting  Customizer control setting name.
 * @param array  $excludes  Properties field keys to exludes.
 */
function mk_cs_box_model( $setting, array $excludes = array() ) {
	$box_model = mk_maybe_json_decode( mk_cz_get_option( $setting ) );
	$css = '';
	if ( $box_model ) {
		foreach ( $box_model as $key => $value ) {
			if ( ! in_array( $key, $excludes, true ) ) {
				$css .= str_replace( '_', '-', $key ) . ':' . $value . 'px;';
			}
		}
	}
	return $css;
}

/**
 * Get container width based on product layout.
 *
 * @todo Improve logic to get width automatically not hard coded value per layout.
 * @since 5.9.4
 * @since 6.0.3 Improve logic to handle layout 9/10.
 * @param string $setting  Customizer control setting name.
 */
function mk_get_image_gallery_width( $setting ) {
	$product_layout = (int) mk_cz_get_option( $setting );
	$container_width = 48; // Default value.

	if ( 3 === $product_layout ) {
		$container_width = 100;
	}

	if ( 9 === $product_layout || 10 === $product_layout ) {
		$container_width = 61;
	}

	return $container_width;
}

<?php
// @codingStandardsIgnoreFile
/**
 * VC Accordions shortcode.
 *
 * @package Jupiter
 * @subpackage Visual_Composer
 * @since 5.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Modified version of original WPBakeryShortCode_VC_Accordion class.
 * 1. Name changed to WPBakeryShortCode_VC_Accordions.
 * 2. In first line of `contentAdmin` method, added `$this->settings['base'] = 'vc_accordion';`
 * to fix styling issues.
 *
 * @since 5.9.7
 *
 * @SuppressWarnings(PHPMD)
 */
if ( WPB_VC_VERSION > '5.4' ) {

	class WPBakeryShortCode_VC_Accordions extends WPBakeryShortCode {
		protected $controls_css_settings = 'out-tc vc_controls-content-widget';

		public function __construct( $settings ) {
			parent::__construct( $settings );
		}

		public function contentAdmin( $atts, $content = null ) {
			$this->settings['base'] = 'vc_accordion';
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( 'content' !== $param['param_name'] ) {
					$shortcode_attributes[ $param['param_name'] ] = isset( $param['value'] ) ? $param['value'] : null;
				} elseif ( 'content' === $param['param_name'] && null === $content ) {
					$content = $param['value'];
				}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );

			$elem = $this->getElementHolder( $width );

			$inner = '';
			foreach ( $this->settings['params'] as $param ) {
				$param_value = isset( ${$param['param_name']} ) ? ${$param['param_name']} : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[ $first_key ];
				}
				$inner .= $this->singleParamHtmlHolder( $param, $param_value );
			}

			$tmp = '';

			if ( isset( $this->settings['custom_markup'] ) && '' !== $this->settings['custom_markup'] ) {
				if ( '' !== $content ) {
					$custom_markup = str_ireplace( '%content%', $tmp . $content, $this->settings['custom_markup'] );
				} elseif ( '' === $content && isset( $this->settings['default_content_in_template'] ) && '' !== $this->settings['default_content_in_template'] ) {
					$custom_markup = str_ireplace( '%content%', $this->settings['default_content_in_template'], $this->settings['custom_markup'] );
				} else {
					$custom_markup = str_ireplace( '%content%', '', $this->settings['custom_markup'] );
				}
				$inner .= do_shortcode( $custom_markup );
			}
			$output = str_ireplace( '%wpb_element_content%', $inner, $elem );

			return $output;
		}
	}

}

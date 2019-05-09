<?php

global $mk_options;

$fonts = ! empty( $mk_options['fonts'] ) ? $mk_options['fonts'] : array();
$fonts_css = '';

foreach ( $fonts as $font ) {

	if ( empty( $font['fontFamily'] ) || empty( $font['elements'] ) ) {
		continue;
	}

		$fonts_css .= implode( ', ', $font['elements'] );
	$fonts_css .= ' { font-family: ' . $font['fontFamily'] . ' } ';

}

Mk_Static_Files::addLocalStyle(
	"
    {$fonts_css}
"
);

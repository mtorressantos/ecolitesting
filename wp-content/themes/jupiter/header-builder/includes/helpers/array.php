<?php
/**
 * Array helper functions.
 *
 * @package Header_Builder
 * @subpackage Utility
 * @since 5.9.0
 */

/**
 * Safely get an item from an associative array using an array key,
 * and provide a default value if key does not exist.
 *
 * @since 5.9.0
 * @since 5.9.7 Delete data type array on $arr and check the data type is array or not
 *              instead. Force to get data array in parameter sometime cause fatal error.
 *              With unexpected data structure (old vs new) this is the best solution.
 *
 * @param array  $arr Array of any conceivable structure.
 * @param string $key Array key to use in retrieving value.
 * @param mixed  $default Default value to provide in case key is not in array.
 * @param mixed  $overrides An array of return values to be overwritten by defaul value.
 * @return mixed Any value or another array.
 */
function array_safe_get( $arr, $key, $default = null, $overrides = '' ) {
	// If the data is not array, return default value.
	if ( ! is_array( $arr ) ) {
		return $default;
	}

	$output = array_key_exists( (string) $key, $arr ) ? $arr[ $key ] : $default;

	if ( ! is_array( $overrides ) ) {
		$overrides = array( $overrides );
	}

	return in_array( $output, $overrides, true ) ? $default : $output;
}

/**
 * Count 2 arrays with the same key.
 *
 * @since 5.9.0
 * @since 5.9.7 Remove array type of the parameter to avoid fatal error.
 *
 * @param array $array1 Any associative array.
 * @param array $array2 Any associative array.
 *
 * @return array Copy of $array1 with values from $array2 added to it.
 */
function array_shallow_sum_values( $array1 = array(), $array2 = array() ) {
	$sum = array();

	if ( ! is_array( $array1 ) || ! is_array( $array2 ) ) {
		return $sum;
	}

	foreach ( $array1 as $key => $value ) {
		$sum[ $key ] = floatval( $value ) + floatval( $array2[ $key ] );
	}

	return $sum;
}

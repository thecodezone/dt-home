<?php

/**
 * Patch any functions that don't work well with scoped namespaces.
 */
if ( ! function_exists( 'value' ) ) {
	/**
	 * Returns the result of the given value if it is a closure, otherwise returns the value itself.
	 *
	 * @param mixed $value The value to check.
	 * @param mixed ...$args Optional additional arguments to pass to the closure if the value is a closure.
	 *
	 * @return mixed The result of the closure if value is a closure, otherwise the original value.
	 */
	function value( $value, ...$args ) {
		return $value instanceof Closure ? $value( ...$args ) : $value;
	}
}

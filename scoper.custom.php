<?php

/**
 * WordPress global function are already exposed by wpify/scoper.
 * We still need to expose laravel helpers to keep them
 * from being namespaced.
 *
 * @param array $config
 *
 * @return array
 */
function customize_php_scoper_config( array $config ): array {
	$config['expose-functions'] = array_merge( $config['expose-functions'] ?? [], [] );

	return $config;
}

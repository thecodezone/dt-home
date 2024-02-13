<?php

namespace DT\Home\Conditions;

use DT\Home\CodeZone\Router\Conditions\Condition;

class Backend implements Condition {

	/**
	 * Determines if the current path is an admin path.
	 *
	 * @return bool True if the current path is an admin path.
	 */
	public function test(): bool {
		return is_admin();
	}
}
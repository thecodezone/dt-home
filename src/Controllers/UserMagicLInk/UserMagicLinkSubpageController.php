<?php

namespace DT\Launcher\Controllers\UserMagicLInk;

use DT\Launcher\MagicLinks\UserMagicLink;
use function DT\Launcher\plugin;

class UserMagicLinkSubpageController {

	public function __construct( UserMagicLink $magic_link ) {
		$this->magic_link = $magic_link;
	}

	public function show() {
		$home_url = $this->magic_link->url;
		include plugin()->templates_path . '/user-magic-link/subpage.php';
	}
}

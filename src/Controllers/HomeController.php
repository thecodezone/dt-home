<?php

namespace DT\Launcher\Controllers;

use function DT\Launcher\view;
use function DT\Launcher\template;


class HomeController {
	/**
	 * index file
	 *
	 */
	public function index() {

		$option_value = get_option('require_user_option');
	
		if(is_user_logged_in()){
			$name = 'Friend';
			template('index', compact('name'));
		}else{
			dd('Setting is unchecked');
			exit;
		}
	}
}
 
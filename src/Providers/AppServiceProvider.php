<?php

namespace DT\Home\Providers;

use DT\Home\Apps\Autolink;
use DT\Home\Apps\BiblePlugin;
use DT\Home\Apps\ShareApp;
use DT\Home\Apps\ThreeThirdsMeetings;

class AppServiceProvider extends ServiceProvider {
	protected $apps = [
		Autolink::class,
		BiblePlugin::class,
		ShareApp::class,
		ThreeThirdsMeetings::class
	];

	public function register(): void {
	}

	public function boot(): void {
		foreach ( $this->apps as $app ) {
			$this->container->make( $app );
		}
	}
}

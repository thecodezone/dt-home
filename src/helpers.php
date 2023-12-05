<?php

namespace DT\Launcher;

function container() {
	return Plugin::$instance->container;
}

function plugin() {
	return Plugin::$instance;
}

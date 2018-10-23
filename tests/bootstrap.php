<?php

declare(strict_types=1);

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');
$_GET = $_POST = $_COOKIE = [];


// create temporary directory
define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());
// garbage collector
$__lock = fopen(__DIR__ . '/lock', 'w');
if (rand(0, 100)) {
	flock($__lock, LOCK_SH);
	@mkdir(dirname(TEMP_DIR));
} elseif (flock($__lock, LOCK_EX)) {
	Tester\Helpers::purge(dirname(TEMP_DIR));
}
@mkdir(TEMP_DIR);


if (extension_loaded('xdebug')) {
	xdebug_disable();
}


function test(\Closure $function): void
{
	$function();
}

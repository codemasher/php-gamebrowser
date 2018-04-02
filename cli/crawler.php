<?php
/**
 * @filesource   crawler.php
 * @created      02.04.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

use chillerlan\Database\{
	Database, DatabaseOptions, Drivers\MySQLiDrv
};
use chillerlan\GameBrowser\Games\{
	Q3A, ioq3, WoP
};
use chillerlan\Traits\DotEnv;

require_once __DIR__.'/../vendor/autoload.php';

$env = (new DotEnv(__DIR__.'/../config', '.env', false))->load();

$options = [
	// DatabaseOptions
	'driver'           => MySQLiDrv::class,
	'host'             => $env->MYSQL_HOST,
	'port'             => $env->MYSQL_PORT,
	'database'         => $env->MYSQL_DATABASE,
	'username'         => $env->MYSQL_USERNAME,
	'password'         => $env->MYSQL_PASSWORD,
];

$db = new Database(new DatabaseOptions($options));

$games = [
	Q3A::class,
	ioq3::class,
	WoP::class,
];

foreach($games as $game){

	/** @var \chillerlan\GameBrowser\ServerQueryInterface $queryInterface */
	$queryInterface = new $game;

	$gamename   = $queryInterface->getGameName();
	$serverlist = $queryInterface->getServers();

	foreach($serverlist as $version => $servers){
		foreach($servers as $server){
			[$ip, $port] = $server;

			var_dump([$gamename, $version, $ip, $port]);

			// @todo... WIP
		}
	}

}


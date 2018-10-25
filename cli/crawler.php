<?php
/**
 * @filesource   crawler.php
 * @created      02.04.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

use chillerlan\Database\{Database, DatabaseOptionsTrait, Drivers\MySQLiDrv};
use chillerlan\DotEnv\DotEnv;
use chillerlan\GameBrowser\Games\{ioq3, Q3A, Tremulous, WolfET, WoP};
use chillerlan\GameBrowser\ServerQueryOptionsTrait;
use chillerlan\Logger\{Log, LogOptionsTrait, Output\ConsoleLog};
use chillerlan\Settings\SettingsContainerAbstract;

require_once __DIR__.'/../vendor/autoload.php';

$env = (new DotEnv(__DIR__.'/../config', '.env', false))->load();

$o = [
	// DatabaseOptions
	'driver'           => MySQLiDrv::class,
	'host'             => $env->DB_HOST,
	'port'             => $env->DB_PORT,
	'database'         => $env->DB_DATABASE,
	'username'         => $env->DB_USERNAME,
	'password'         => $env->DB_PASSWORD,
	// log
	'minLogLevel'      => 'debug',
];

$options = new class($o) extends SettingsContainerAbstract{
	use DatabaseOptionsTrait, LogOptionsTrait, ServerQueryOptionsTrait;
};

$logger = (new Log)->addInstance(new ConsoleLog($options), 'console');

$db = new Database($options);
$db->connect();

$games = [
	ioq3::class,
	Q3A::class,
	Tremulous::class,
	WolfET::class,
	WoP::class,
];

foreach($games as $game){

	/** @var \chillerlan\GameBrowser\Engines\ServerQueryInterface $queryInterface */
	$queryInterface = new $game($options);

	$gamename   = $queryInterface->getGameName();
	$serverlist = $queryInterface->getServers();

	foreach($serverlist as $version => $servers){
		foreach($servers as $server){
			[$ip, $port] = $server;

			try{
				$info = $queryInterface->getInfo($ip, $port);

				$values = [
					'id'       => md5($ip.':'.$port),
					'ip'       => $ip,
					'port'     => $port,
					'game'     => $gamename,
					'version'  => $version,
					'response' => 1,
					'data'     => json_encode($info),
				];

				$db->insert->into('servers', 'ignore')->values($values)->query();
				$logger->info($ip.':'.$port.' - '.$info['hostname']);
			}
			catch(\Exception $e){
				// we pretend to catch errors here...
				$logger->debug($ip.':'.$port);
			}
		}
	}

}


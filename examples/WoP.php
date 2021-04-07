<?php
/**
 *
 * @filesource   WoP.php
 * @created      02.04.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowserExamples;

use chillerlan\GameBrowser\Games\WoP;

require_once __DIR__.'/../vendor/autoload.php';

$q = new WoP;

$serverlist = $q->getServers();

foreach($serverlist as $version => $servers){
#	print_r($version);

	foreach($servers as $server){
		[$ip, $port] = $server;

		print_r($q->getInfo($ip, $port));
#		print_r($q->getStatus($ip, $port));
	}

}

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

print_r($q->getServers());
print_r($q->getInfo('78.46.188.33', 27961));
print_r($q->getStatus('78.46.188.33', 27961));

<?php
/**
 * Class ioq3
 *
 * @filesource   ioq3.php
 * @created      01.04.2018
 * @package      chillerlan\GameBrowser\Games
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Games;

use chillerlan\GameBrowser\Protocols\idtech3;

class ioq3 extends idtech3{

	protected $masterHost = 'master.ioquake3.org';
	protected $masterPort = 27950;
	protected $protocols  = [
		'1.36' => '68',
		// ... @todo
	];

}

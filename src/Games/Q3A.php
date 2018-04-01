<?php
/**
 * Class Q3A
 *
 * @filesource   Q3A.php
 * @created      31.03.2018
 * @package      chillerlan\GameBrowser\Games
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Games;

use chillerlan\GameBrowser\Protocols\idtech3;

class Q3A extends idtech3{

	protected $masterHost = 'master.quake3arena.com';
	protected $masterPort = 27950;
	protected $protocols  = [
		'1.32' => '68',
		'1.31' => '67',
		'1.30' => '66',
		// ... @todo
	];

}

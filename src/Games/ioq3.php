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

use chillerlan\GameBrowser\Engines\idtech3;

class ioq3 extends idtech3{

	protected string $masterHost = 'master.ioquake3.org';
	protected array  $protocols  = [
		'1.36-68' => '68',
		'1.36-71' => '71',
	];

}

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

use chillerlan\GameBrowser\Engines\idtech3;

class Q3A extends idtech3{

	protected string $masterHost = 'master.quake3arena.com';
	protected array $protocols = [
		'1.16' => '43',
		'1.17' => '45',
		'1.2x' => '48',
		'1.30' => '66',
		'1.31' => '67',
		'1.32' => '68',
	];

}

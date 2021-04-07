<?php
/**
 * Class WolfET
 *
 * @filesource   WolfET.php
 * @created      24.10.2018
 * @package      chillerlan\GameBrowser\Games
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Games;

use chillerlan\GameBrowser\Engines\idtech3;

class WolfET extends idtech3{

	protected string $masterHost = 'etmaster.idsoftware.com';

	protected array $protocols = [
		'1.1'   => '50',
		'1.3'   => '57',
		'1.41'  => '60',
		'2.60'  => '82',
		'2.60b' => '84',
	];
}

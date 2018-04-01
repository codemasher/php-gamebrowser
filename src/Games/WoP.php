<?php
/**
 * Class WoP
 *
 * @filesource   WoP.php
 * @created      31.03.2018
 * @package      chillerlan\GameBrowser\Games
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser\Games;

use chillerlan\GameBrowser\Protocols\idtech3;

class WoP extends idtech3{

	protected $masterHost = 'master.worldofpadman.com';
	protected $masterPort = 27955;
	protected $protocols  = [
		'1.2' => '68', // there's no reliable way to tell 1.1 and 1.2 apart... ty for not listening to me. ~smiley
		'1.5' => '69',
		'1.6' => 'WorldofPadman 71', // 1.5.2+ actually - what a mess...
	];

	/**
	 * @inheritdoc
	 */
	protected function parsePlayers(array $playerlist, array $cvars):array {
		$protocol = (int)($cvars['protocol'] ?? $cvars['com_protocol']);

		// wop 1.5.2+
		// teams: 0=free, 1=red, 2=blue 3=spectator
		if($protocol === 71){
			$teams = explode(' ', $cvars['Players_Team']);
			$bots  = explode(' ', $cvars['Players_Bot']);
		}
		// ... @todo

		$players = [];

		foreach($playerlist as $i => $player){
			$player    = explode(' ', $player);
			$player[2] = trim($player[2], '"'); // strip double quotes from the name

			// assign team and bot indicators
			$player[3] = null;
			$player[4] = null;

			if($protocol === 71){
				$player[3] = (int)$teams[$i];
				$player[4] = (bool)$bots[$i];
			}

			$players[$i] = array_combine(['score', 'ping', 'name', 'team', 'is_bot'], $player);
		}

		return $players;
	}

}
